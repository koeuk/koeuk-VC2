<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $feedback = Feedback::all();
        $transformedFeedback = FeedbackResource::collection($feedback);
        return response()->json([
            'feedback' => $transformedFeedback,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        $feedback = new Feedback();
        $feedback->user_id = $request->user_id; // Assuming you're using Laravel's built-in authentication
        $feedback->content = $request->input('content');
        $feedback->save();
        return $feedback;
    }
    public function showByUser($user_id)
    {
        $feedbacks = Feedback::where('user_id', $user_id)->get();

        return response()->json($feedbacks, 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }

        $feedback->content = $request->input('content');
        $feedback->save();

        return response()->json($feedback, 200);
    }
    
    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }

        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully'], 200);
    }
}
