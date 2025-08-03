<x-app-layout>
  <main class="flex-1 bg-gray-100 px-4 py-4 sm:px-6 lg:px-8" style="margin-top:90px">
    <div class="flex justify-between items-center mb-3">
      <a href="{{ route('admin.users.index') }}" class="btn btn-warning shadow flex items-center border -mt-2 mb-1 border-none hover:bg-warning-600 transition-colors">
        <i class="bx bx-arrow-back mr-2 animate-pulse"></i>
        Back
      </a>
      <h1 class="text-xl text-warning shadow p-1 ">CREATE USER</h1>
    </div>
    <div class="shadow-sm rounded-md p-3 pt-0">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-3" enctype="multipart/form-data">
    @csrf
    <div class='shadow bg-white p-8 border rounded mb-4'>
        <div class="mb-2">
            <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter name" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent" required>
        </div>
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
            <input name="email" id="email" placeholder="Enter email" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent" value="{{ old('email') }}" required>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label for="address" class="block text-gray-700 font-medium mb-1">Address</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}" placeholder="Enter address" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent">
            </div>
            <div>
                <label for="phone" class="block text-gray-700 font-medium mb-1">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent">
            </div>
            <div>
                <label for="role_id" class="block text-gray-700 font-medium mb-1">Role</label>
                <div class="relative">
                    <select class="w-full border border-gray-300 rounded-md py-1 px-2 pr-6 text-gray-700 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent appearance-none" name="role" required>
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="profile" class="block text-gray-700 font-medium mb-1">Profile</label>
                <input id="profile" type="file" name="profile" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent">
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Enter password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent" required>
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Password Confirm</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent" required>
                </div>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="show-password" class="mr-2 h-4 w-4 text-warning-500 border-gray-300 rounded focus:ring-warning-500 cursor-pointer" onchange="togglePasswordVisibility()">
                <label for="show-password" class="text-gray-600 cursor-pointer select-none">Show password</label>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="bg-warning shadow hover:bg-warning-600 font-medium py-1 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-warning-500 focus:ring-opacity-50 transition-colors">
            <svg class="animate-pulse w-4 h-4 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            Submit
        </button>
    </div>
</form>

    </div>
  </main>
  
  <!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <style>
    @keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
}

.animate-pulse {
  animation: pulse 1s ease-in-out infinite;
}
  </style>
  <script>
    function togglePasswordVisibility() {
  var passwordField = document.getElementById("password");
  var checkbox = document.getElementById("show-password");

  if (checkbox.checked) {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
}
  </script>
</x-app-layout>