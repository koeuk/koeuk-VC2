<x-app-layout>
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100" style="margin-top:70px">
        <div class="container mx-auto px-4 mt-4 py-1 pb-16">
            <div class="flex justify-between items-center mb-3">
                <a href="{{ route('admin.discounts.index') }}"
                    class="btn btn-warning shadow flex items-center border -mt-2 mb-1 border-none hover:bg-warning-600 transition-colors">
                    <i class="bx bx-arrow-back mr-2 animate-pulse"></i>
                    Back
                </a>
                <h1 class="text-xl text-warning shadow p-1 ">EDIT DISCOUNT</h1>
            </div>
            <div class=" shadow-md rounded p-3 pt-2">
                <form method="POST" action="{{ route('admin.discounts.update', $discount->id) }}">
                    @csrf
                    @method('put')
                    <div class='shadow bg-white p-7 border around mb-4'>
                        <div class="space-y-3">
                            <div>
                                <label for="discount" class="text-gray-700 font-medium text-sm">Discount</label>
                                <input id="discount" type="number" name="discount"
                                    value="{{ old('discount', $discount->discount) }}" placeholder="Enter discount"
                                    class="w-full px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 text-sm" />
                            </div>
                            <div>
                                <label for="description" class="text-gray-700 font-medium text-sm">Description</label>
                                <textarea name="description" id="description" placeholder="Enter description"
                                    class="w-full px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 text-sm"
                                    rows="3">{{ old('description', $discount->description) }}</textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label for="star_date" class="text-gray-700 font-medium text-sm">Start Date</label>
                                    <input id="star_date" type="date" name="star_date"
                                        value="{{ old('start_date', $discount->start_date) }}" placeholder="Enter start date"
                                        class="w-full px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 text-sm" />
                                </div>
                                <div>
                                    <label for="end_date" class="text-gray-700 font-medium text-sm">End Date</label>
                                    <input id="end_date" type="date" name="end_date"
                                        value="{{ old('end_date', $discount->end_date) }}" placeholder="Enter end_date" required
                                        class="w-full px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 text-sm" />
                                </div>
                                <div class="mb-3">
                                    <label for="image"
                                        class="block text-gray-700 font-medium mb-1 text-sm">Image</label>
                                    <input id="image" type="file" name="image" value="{{ old('image') }}"
                                        placeholder="Enter image"
                                        class="w-full border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500 text-sm">
                                </div>
                                {{-- <div class="form-group">
                   <label for="category">Category</label>
                   <select class="form-control" id="category" name="category_id">
                       <option value="">Select a Category</option>
                       @foreach ($categories as $category)
                           <option value="{{ $category->id }}" {{$service->category_id == $category->id ? 'selected' : '' }}>
                               {{ $category->name }}
                           </option>
                       @endforeach
                   </select>
               </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-6">
                        <button type="submit"
                            class="bg-warning shadow hover:bg-warning-600 font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-warning-500 focus:ring-opacity-50 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline-block animate-pulse" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        @keyframes pulse {

            0%,
            100% {
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
