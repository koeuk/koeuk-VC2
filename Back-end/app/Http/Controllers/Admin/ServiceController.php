<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Auth as FacadesAuth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('role_or_permission:Service access|Service create|Service edit|Service delete', ['only' => ['index','show']]);
        $this->middleware('role_or_permission:Service create', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Service edit', ['only' => ['edit','update']]);
        $this->middleware('role_or_permission:Service delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Service= Service::paginate(5);
        $services = Service::with('category')->get();
        return view('service.index',['services'=>$Service]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $categories = Category::all();
        return view('service.new', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::user()->id;
    
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageContents = file_get_contents($image->getRealPath());
                $base64String = 'data:' . $image->getMimeType() . ';base64,' . base64_encode($imageContents);
                $data['image'] = $base64String;
            } else {
                $data['image'] = null;
            }
    
            $service = Service::create($data);
    
            // Display success alert popup
            return redirect('admin/services')
                ->with('showAlertCreate', true);
        } catch (\Exception $e) {
            // Log the error or display a user-friendly error message
            return redirect('admin/services')
                ->with('error', 'An error occurred while uploading the image.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
    //    return view('service.edit',['service' => $service]);
        $categories = Category::all();
            
            return view('service.edit',['service' => $service], compact('categories'));
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $service->update($request->all());
        return redirect('admin/services')->with('showAlertEdit', true);
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->back()->with('showAlertDelete', true);
    }
    
    public function upload(Request $request){

        $user_id =Auth::id();
        $user = User::find($user_id);
    
        
    }
}
