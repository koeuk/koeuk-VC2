<x-app-layout>
   <div>
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200" style="margin-top:90px">
            <div class="container mx-auto px-6 py-1">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-warning shadow flex items-center border mt-2 mb-1 border-none hover:bg-warning-600 transition-colors">
                <i class="bx bx-arrow-back mr-2 animate-pulse"></i>
                Back
              </a>
              <div class="bg-white shadow-md rounded my-6 p-5">
                <form method="POST" action="{{ route('admin.permissions.store')}}">
                  @csrf
                  @method('post')
                <div class="flex flex-col space-y-2">
                  <label for="role_name" class="text-gray-700 select-none font-medium">Permission Name</label>
                  <input
                    id="role_name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter permission"
                    class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200"
                  />
                </div>
                <div class="text-center mt-16">
                  <button type="submit" class="bg-warning shadow hover:bg-warning-600 font-medium py-1 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-warning-500 focus:ring-opacity-50 transition-colors">Submit</button>
                </div>
              </div>
            </div>
        </main>
    </div>
</div>
</x-app-layout>
