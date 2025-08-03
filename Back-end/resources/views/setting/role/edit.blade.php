<x-app-layout>
    <div style="margin-top:90px">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-6 py-4">
                <div class="mb-4">
                    <a href="{{ route('admin.roles.index') }}"
                        class=" btn btn-warning me-2 hover-fade shadow d-flex align-items-center border -mt-3 mb-2 border-none w-20">
                        <i class="bx bx-arrow-back mr-2"></i>
                        Back
                    </a>
            <h4 class='shadow text-center p-2 border around-50'>Setting {{ old('name',$role->name) }} Permission</h4> 
                </div>
                <div class="bg-white shadow-md rounded">
                    <form method="POST" action="{{ route('admin.roles.update',$role->id) }}"
                        class="p-6 space-y-4">
                        @csrf
                        @method('put')
                        <div class="flex flex-col">
                            <input id="role_name" type="text" name="name" value="{{ old('name',$role->name) }}"
                                placeholder="Placeholder"
                                class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200"
                                hidden />
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="permissions[]"
                                    value="{{ $permission->id }}"
                                    @if(count($role->permissions->where('id',$permission->id))) checked @endif>
                                <label class="ml-2 text-gray-700">{{ $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow hover:shadow-lg transition-colors duration-200">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>