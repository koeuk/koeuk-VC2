<x-app-layout>
     <main class="flex-1 bg-gray-100 px-4 py-4 sm:px-6 lg:px-8" style="margin-top:90px">
       <div class="flex justify-between items-center mb-3">
         <a href="{{ route('admin.discounts.index') }}" class="btn btn-warning shadow flex items-center border -mt-2 mb-1 border-none hover:bg-warning-600 transition-colors">
           <i class="bx bx-arrow-back mr-2 animate-pulse"></i>
           Back
         </a>
         <h1 class="text-xl text-warning shadow p-1 ">CREATE DISCOUNT</h1>
       </div>
       <div class="shadow-sm rounded-md p-3 pt-2">
         <form method="POST" action="{{ route('admin.discounts.store') }}" class="space-y-3">
           @csrf
           <div class='shadow bg-white p-8 border around mb-4' >
           <div class="mb-2">
             <label for="discount" class="block text-gray-700 font-medium mb-1">Discount</label>
             <input id="discount" type="number" name="discount" value="{{ old('discount') }}" placeholder="Enter discount" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent" required>
           </div>
           <div>
             <label for="description" class="block text-gray-700 font-medium mb-1">Description</label>
             <textarea name="description" id="description" placeholder="Enter description" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent" rows="2">{{ old('description') }}</textarea>
           </div>
           <div class="grid grid-cols-3 gap-3">
             <div>
               <label for="start_date" class="block text-gray-700 font-medium mb-1">Start Date</label>
               <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}" placeholder="Enter price" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent">
             </div>
             <div>
               <label for="end_date" class="block text-gray-700 font-medium mb-1">End Date</label>
               <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" placeholder="Enter end date" class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent">
             </div>
           {{-- @if (count($categories) > 0)
           <div>
             <label for="category_id" class="block text-gray-700 font-medium mb-1">Category</label>
             <div class="relative">
               <select class="w-full border border-gray-300 rounded-md py-1 px-2 pr-6 text-gray-700 focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-transparent appearance-none" name="category_id" required>
                 <option value="">Select a category</option>
                 @foreach ($categories as $category)
                   <option value="{{ $category->id }}">{{ $category->name }}</option>
                 @endforeach
               </select>
             </div>
           </div>
           @else
           <div class="text-gray-700">
             No categories available. Please create a category first.
           </div>
           @endif --}}
           </div>
           </div>
           <div class="text-center">
             <button type="submit" class=" bg-warning shadow hover:bg-warning-600 font-medium py-1 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-warning-500 focus:ring-opacity-50 transition-colors">
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
   </x-app-layout>