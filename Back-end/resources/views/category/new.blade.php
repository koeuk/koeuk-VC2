<x-app-layout>
  <main class="flex-1 bg-gray-200 px-4 py-6 sm:px-6 lg:px-8" style="margin-top:90px">
  <a href="{{ route('admin.categories.index') }}" class="btn btn-warning me-2 hover-fade shadow d-flex align-items-center border -mt-3 mb-2 border-none w-20 ">
      <i class="bx bx-arrow-back me-2"></i>
      Back
    </a>
    <div class="bg-white shadow-sm rounded-md p-4">
      <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <div class="mb-4">
          <label for="name" class="block text-warning-700 font-medium mb-1">Name</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter name" class="w-full border border-warning-300 rounded-md py-2 px-3 focus:outline-none focus:border-warning-500 focus:ring-1 focus:ring-warning-500">
        </div>
        <div class="mb-4">
          <label for="description" class="block text-warning-700 font-medium mb-1">Description</label>
          <textarea name="description" id="description" placeholder="Enter description" class="w-full border border-warning-300 rounded-md py-2 px-3 focus:outline-none focus:border-warning-500 focus:ring-1 focus:ring-warning-500" rows="2">{{ old('description') }}</textarea>
        </div>
        <div class="text-center mt-4">
        <button type="submit" class="bg-warning shadow hover:bg-warning-600 font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-warning-500 focus:ring-opacity-50 transition-colors">
          <svg class="w-4 h-4 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
</x-app-layout>