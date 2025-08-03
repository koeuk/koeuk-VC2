    <x-app-layout>
        <!-- Include Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/custom-ui.css') }}">
        
        <div style="margin-top:60px">
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    <!-- Dashboard Header -->
                    <div class="mb-8 fade-in">
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
                        <p class="text-gray-600 mt-2">Welcome back! Here's what's happening with your business today.</p>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Categories Card -->
                        <div class="stat-card fade-in" style="animation-delay: 0.1s;">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Categories</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ count($Categories) }}</p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <span class="text-green-600 font-medium">+12%</span> from last month
                                    </p>
                                </div>
                                <div class="p-3 bg-yellow-100 rounded-lg">
                                    <i class='bx bxs-category text-yellow-600 text-2xl'></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Services Card -->
                        <div class="stat-card fade-in" style="animation-delay: 0.2s;">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Services</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ count($Service) }}</p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <span class="text-green-600 font-medium">+8%</span> from last month
                                    </p>
                                </div>
                                <div class="p-3 bg-blue-100 rounded-lg">
                                    <i class='bx bxs-briefcase-alt-2 text-blue-600 text-2xl'></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Revenue Card -->
                        <div class="stat-card fade-in" style="animation-delay: 0.3s;">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Revenue</p>
                                    @php
                                        $totals = $payments->where('status', 'done')->pluck('total');
                                        $totalSum = $totals->sum();
                                        if ($totalSum >= 10000) {
                                            $formattedTotal = number_format($totalSum / 1000, 0) . 'k';
                                        } else {
                                            $formattedTotal = number_format($totalSum, 0);
                                        }
                                    @endphp
                                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ $formattedTotal }}</p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <span class="text-green-600 font-medium">+23%</span> from last month
                                    </p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-lg">
                                    <i class='bx bxs-dollar-circle text-green-600 text-2xl'></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Users Card -->
                        <div class="stat-card fade-in" style="animation-delay: 0.4s;">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Users</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ count($users) }}</p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <span class="text-green-600 font-medium">+5%</span> from last month
                                    </p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-lg">
                                    <i class='bx bx-user text-purple-600 text-2xl'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Top Services Card -->
                            <div class="modern-card fade-in" style="animation-delay: 0.5s;">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900">Top Services</h2>
                                    <span class="text-sm text-gray-500">Last 30 days</span>
                                </div>
                                <div class="space-y-3" style="max-height: 350px; overflow-y: auto;">
                                        @php
                                            // Initialize the $data array
                                            $data = [];

                                            // Fill $data with service information
                                            foreach ($Service as $service) {
                                                $data[$service->id] = [
                                                    'id' => $service->id,
                                                    'name' => $service->name,
                                                    'number' => 0,
                                                ];

                                                // Get booking IDs where status is 'done'
                                                $booking_id = $FixingProgress
                                                    ->where('status', 'done')
                                                    ->pluck('booking_id');

                                                // Initialize book_service variable
                                                $book_service = collect();

                                                foreach ($bookings as $booking) {
                                                    if ($booking->type == 'immediately') {
                                                        $book_service = $bookin_immediatelies->where(
                                                            'service_id',
                                                            $service->id,
                                                        );
                                                    } else {
                                                        $book_service = $bookin_deadlines->where(
                                                            'service_id',
                                                            $service->id,
                                                        );
                                                    }

                                                    if ($book_service->isEmpty()) {
                                                        // Increment the number for the corresponding service ID
                                                        if (isset($data[$service->id])) {
                                                            $data[$service->id]['number']++;
                                                        }
                                                    }
                                                }
                                            }
                                            // Convert $data array to a collection
                                            $dataCollection = collect($data);

                                            // Filter out items where 'number' is zero
                                            $filteredData = $dataCollection->filter(function ($item) {
                                                return $item['number'] != 0;
                                            });

                                            // Sort the filtered collection by 'number' field in descending order
                                            $sortedData = $filteredData->sortByDesc('number');

                                            // Limit the results to the top 5 items
                                            $topFiveData = $sortedData->take(5);

                                        @endphp
                                        @foreach ($topFiveData as $item)
                                            @php
                                                $topservice = $Service->where('id', $item['id'])->first();
                                            @endphp
                                            <!-- Service Item -->
                                            <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-all duration-200 cursor-pointer group">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors">{{ $topservice->name }}</h4>
                                                        <div class="flex items-center gap-4 mt-2">
                                                            <span class="flex items-center text-sm text-gray-600">
                                                                <i class='bx bxs-group mr-1'></i>
                                                                {{ $item['number'] }} bookings
                                                            </span>
                                                            <div class="flex items-center">
                                                                @for($i = 0; $i < 3; $i++)
                                                                    <i class='bx bxs-star text-yellow-400 text-sm'></i>
                                                                @endfor
                                                                @for($i = 3; $i < 5; $i++)
                                                                    <i class='bx bx-star text-gray-300 text-sm'></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-modern btn-primary py-2 px-4 text-sm"
                                                        data-bs-toggle="modal" data-bs-target="#topServiceDetailModel"
                                                        data-service-image="{{ $topservice->image }}"
                                                        data-service-title="{{ $topservice->name }}"
                                                        data-service-description="{{ $topservice->description }}"
                                                        data-service-category="{{ $topservice->category->name }}"
                                                        data-service-price="{{ $topservice->price }}" data-service-stars="3">
                                                        Details
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if (count($topFiveData) == 0)
                                            <div class="text-center py-8">
                                                <i class='bx bx-info-circle text-4xl text-gray-300'></i>
                                                <p class="text-gray-500 mt-2">No top services available</p>
                                            </div>
                                        @endif
                                </div>
                            </div>

                            <!-- Low Performing Services -->
                            <div class="modern-card fade-in" style="animation-delay: 0.6s;">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900">Services Needing Attention</h2>
                                    <span class="text-sm text-gray-500">No bookings</span>
                                </div>
                                <div class="space-y-3" style="max-height: 350px; overflow-y: auto;">
                                    @php $mama =0; @endphp
                                    @foreach ($Service as $service)
                                        @php
                                            $booking_id = $FixingProgress->where('status', 'done')->pluck('booking_id');

                                            foreach ($bookings as $booking) {
                                                if ($booking->type == 'immediately') {
                                                    $book_service = $bookin_immediatelies->where(
                                                        'service_id',
                                                        $service->id,
                                                    );
                                                } else {
                                                    $book_service = $bookin_deadlines->where(
                                                        'service_id',
                                                        $service->id,
                                                    );
                                                }
                                            }
                                        @endphp
                                        @if ($book_service->isEmpty())
                                            @php $mama++ @endphp
                                            <!-- Low Service Item -->
                                            <div class="bg-red-50 rounded-lg p-4 hover:bg-red-100 transition-all duration-200 cursor-pointer group">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-gray-900">{{ $service->name }}</h4>
                                                        <p class="text-sm text-red-600 mt-1">
                                                            <i class='bx bx-error-circle mr-1'></i>
                                                            No bookings yet
                                                        </p>
                                                    </div>
                                                    <button class="btn-modern bg-red-100 text-red-700 hover:bg-red-200 py-2 px-4 text-sm"
                                                        data-bs-toggle="modal" data-bs-target="#topServiceDetailModel"
                                                        data-service-image="{{ $service->image }}"
                                                        data-service-title="{{ $service->name }}"
                                                        data-service-description="{{ $service->description }}"
                                                        data-service-category="{{ $service->category->name }}"
                                                        data-service-price="{{ $service->price }}"
                                                        data-service-stars="0">
                                                        <i class="bx bx-info-circle"></i> Details
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if ($mama == 0)
                                        <div class="text-center py-8">
                                            <i class='bx bx-check-circle text-4xl text-green-400'></i>
                                            <p class="text-gray-500 mt-2">All services are performing well!</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Service Performance Chart -->
                            <div class="modern-card fade-in" style="animation-delay: 0.5s;">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900">Service Performance</h2>
                                    <div class="relative">
                                        <input type="date" class="form-control-modern pr-10" placeholder="Select a date">
                                        <i class="bi bi-calendar3 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>
                                <div class="relative" style="height: 250px;">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                            <!-- Top Fixers -->
                            <div class="modern-card fade-in" style="animation-delay: 0.6s;">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900">Top Fixers</h2>
                                    <span class="text-sm text-gray-500">This month</span>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    @php
                                        $topFixers = \App\Models\FixingProgress::selectRaw(
                                            'fixer_id, count(*) as count',
                                        )
                                            ->groupBy('fixer_id')
                                            ->orderBy('count', 'desc')
                                            ->limit(3)
                                            ->get();
                                    @endphp

                                    @foreach ($topFixers as $top)
                                        @php
                                            $fixer = $users->where('id', $top->fixer_id)->first();
                                        @endphp
                                        <!-- Fixer Card -->
                                        <div class="text-center group cursor-pointer">
                                            <div class="relative mb-3">
                                                <img src="{{ $fixer->profile }}" 
                                                    class="w-20 h-20 rounded-full mx-auto object-cover border-4 border-white shadow-lg group-hover:border-yellow-400 transition-all duration-300"
                                                    alt="{{ $fixer->name }}">
                                                <div class="absolute -bottom-1 -right-1 bg-yellow-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </div>
                                            <h5 class="font-semibold text-gray-900 text-sm mb-1 truncate px-2">{{ $fixer->name }}</h5>
                                            <p class="text-xs text-gray-500 mb-2">{{ $top->count }} fixes</p>
                                            <div class="flex justify-center mb-2">
                                                @for($i = 0; $i < 3; $i++)
                                                    <i class='bx bxs-star text-yellow-400 text-xs'></i>
                                                @endfor
                                            </div>
                                            <button class="btn-modern btn-primary py-1 px-3 text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                                data-bs-toggle="modal" data-bs-target="#userDetailsModal"
                                                data-user-name="{{ $fixer->name }}"
                                                data-user-email="{{ $fixer->email }}"
                                                data-user-phone="{{ $fixer->phone }}"
                                                data-user-address="{{ $fixer->address }}"
                                                data-user-profile="{{ $fixer->profile }}"
                                                data-user-role="{{ $fixer->role }}">
                                                View Profile
                                            </button>
                                        </div>
                                    @endforeach
                                    @if (count($topFixers) == 0)
                                        <div class="col-span-3 text-center py-8">
                                            <i class='bx bx-user-x text-4xl text-gray-300'></i>
                                            <p class="text-gray-500 mt-2">No fixers available yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Customer Feedback -->
                            <div class="modern-card fade-in" style="animation-delay: 0.7s;">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900">Recent Feedback</h2>
                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ count($feedbacks) }} New
                                    </span>
                                </div>
                                <div class="space-y-3" style="max-height: 300px; overflow-y: auto;">
                                    @if (count($feedbacks) == 0)
                                        <div class="text-center py-8">
                                            <i class='bx bx-message-square-dots text-4xl text-gray-300'></i>
                                            <p class="text-gray-500 mt-2">No feedback received yet</p>
                                        </div>
                                    @endif
                                    @foreach ($feedbacks as $feedback)
                                        @php
                                            $user = $users->where('id', $feedback->user_id)->first();
                                        @endphp
                                        <!-- Feedback Item -->
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-all duration-200 group">
                                            <div class="flex items-start gap-3">
                                                <img src="{{ $user->profile }}" 
                                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                                    alt="{{ $user->name }}">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <h5 class="font-semibold text-gray-900 text-sm">{{ $user->name }}</h5>
                                                        <span class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-gray-600 text-sm line-clamp-2">{{ $feedback->content }}</p>
                                                    <div class="flex items-center gap-2 mt-2">
                                                        <button class="text-yellow-600 hover:text-yellow-700 text-sm font-medium"
                                                            data-bs-toggle="modal" data-bs-target="#feedbackDetail"
                                                            data-feedback-image="{{ $user->profile }}"
                                                            data-feedback-name="{{ $user->name }}"
                                                            data-feedback-content="{{ $feedback->content }}">
                                                            Read more
                                                        </button>
                                                @can('Feedback delete')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $feedback->id }})">Delete</button>

                                                    <!-- JavaScript for confirmation dialog -->
                                                    <script>
                                                        function confirmDelete(id) {
                                                            Swal.fire({
                                                                title: 'Are you sure?',
                                                                text: "You won't be able to revert this!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#d33',
                                                                cancelButtonColor: '#3085d6',
                                                                confirmButtonText: 'Yes, delete it!'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    // Perform the delete action by submitting the form
                                                                    document.getElementById(`delete-form-${id}`).submit();
                                                                }
                                                            });
                                                        }
                                                    </script>

                                                    <!-- Form for deletion -->
                                                    <form id="delete-form-{{ $feedback->id }}"
                                                        action="{{ route('admin.feedbacks.destroy', $feedback->id) }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                @endcan

                                            </div>
                                        </div>
                                        <!-- ---------------------------------- -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map Section -->
                    <div class="modern-card fade-in mt-6" style="animation-delay: 0.8s;">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Service Locations</h2>
                            <button class="btn-modern btn-primary text-sm">
                                <i class='bx bx-fullscreen'></i>
                                Full Screen
                            </button>
                        </div>
                        <div class="rounded-lg overflow-hidden" style="height: 400px;">
                            <iframe class="w-full h-full"
                                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d31273.788411035468!2d104.88050064999999!3d11.5358151!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2skh!4v1719023181034!5m2!1sen!2skh"
                                style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- User Details Modal -->
        <div class="modal fade modal-modern" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="userDetailsModalLabel"><i class='bx bxs-user'></i> Top fixer
                            Profile</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="user-details-card">
                                    <div class="user-details-header d-flex align-items-center">
                                        <i class='bx bxs-user'></i>
                                        <h4 class="text-warning" id="user-name"></h4>
                                    </div>
                                    <div class="user-details-body">
                                        <p class="d-flex align-items-center">
                                            <i class='bx bxs-envelope'></i>
                                            <span class="ms-2">Email: <span id="user-email"></span></span>
                                        </p>
                                        <div class="user-details d-flex align-items-center">
                                            <i class='bx bxs-user-detail'></i>
                                            <span class="ms-2"><strong>Role:</strong> <span
                                                    id="user-role"></span></span>
                                        </div>
                                        <div class="user-details d-flex align-items-center">
                                            <i class='bx bxs-map'></i>
                                            <span class="ms-2"><strong>Address:</strong> <span
                                                    id="user-address"></span></span>
                                        </div>
                                        <div class="user-details d-flex align-i4tems-center">
                                            <i class='bx bxs-phone'></i>
                                            <span class="ms-2"><strong>Phone:</strong> <span
                                                    id="user-phone"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 d-flex justify-content-center align-items-center">
                                <div class="user-profile-container" style="height: 230px;width:400px">
                                    <img src="" class="img-fluid rounded"
                                        style="object-fit: cover;width:100%;height:100%" alt="Base64 profile"
                                        id="user-profile">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="additional-info mt-3">
                            <h5 class="d-flex align-items-center">
                                <i class='bx bx-info-circle'></i>
                                <span class="ms-2">Additional Information</span>
                            </h5>
                            <p id="user-additional-info">This is where additional information about the user can be
                                displayed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Details Modal -->
        <div class="modal fade modal-modern" id="topServiceDetailModel" tabindex="-1"
            aria-labelledby="topServiceDetailModelLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="topServiceDetailModelLabel"> Service Detail</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="service-image-container" style="height: 230px;">
                                    <img src="" class="img-fluid rounded"
                                        style="object-fit: cover; width:100%; height:100%" alt="Service Image"
                                        id="service-image">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="shadow p-2 text-center" style="background-color: #f8f9fa;">
                                    <h4 class="text-warning" id="service-title"><i class="bx bxs-star"></i> Premium
                                        Service</h4>
                                    <p id="service-description">Lorem ipsum dolor sit amet, consectetur adipiscing
                                        elit.
                                        Vivamus ac diam at magna tempus volutpat.</p>
                                    <div class="rating mb-3">
                                        <p><i class="bx bxs-star"></i> Stars: <span id="service-stars"></span></p>
                                    </div>
                                    <div class="service-details">
                                        <p><i class="bx bxs-category"></i> <strong>Category:</strong> <span
                                                id="service-category"></span></p>
                                        <p><i class="bx bxs-dollar-circle"></i> <strong>Price:</strong> <span
                                                id="service-price"></span>$</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="additional-info mt-3">
                            <h5><i class="bx bx-info-circle"></i> Additional Information</h5>
                            <p id="service-additional-info">This is where additional information about the service can
                                be
                                displayed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ------------end Top service detail ------------------------ -->
        <!-- Low Service Details Modal -->
        <div class="modal fade modal-modern" id="lowServiceDetailModel" tabindex="-1"
            aria-labelledby="lowServiceDetailModelLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="lowServiceDetailModelLabel">Low Service Detail</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="service-image-container" style="height: 230px;">
                                    <img src="" class="img-fluid rounded"
                                        style="object-fit: cover; width:100%; height:100%" alt="Service Image"
                                        id="service-image">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="shadow p-2 text-center" style="background-color: #f8f9fa;">
                                    <h4 class="text-warning" id="service-title"></h4>
                                    <p id="service-description"></p>
                                    <div class="rating mb-3">
                                        <p><i class="bx bxs-star"></i> Stars: <span id="service-stars"></span></p>
                                    </div>
                                    <div class="service-details">
                                        <p><i class="bx bxs-category"></i> <strong>Category:</strong> <span
                                                id="service-category"></span></p>
                                        <p><i class="bx bxs-dollar-circle"></i> <strong>Price:</strong> <span
                                                id="service-price"></span>$</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="additional-info mt-3">
                            <h5><i class="bx bx-info-circle"></i> Additional Information</h5>
                            <p id="service-additional-info">This is where additional information about the service can
                                be
                                displayed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ------------end low service detail ------------------------ -->

        <!-- Feedback Details Modal -->
        <div class="modal fade modal-modern" id="feedbackDetail" tabindex="-1" aria-labelledby="feedbackDetailLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="topfeedbackDetailLabel">Feedback detail</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="" class="img-fluid rounded" alt="feedback Image"
                                    id="feedback-image">
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-warning" id="feedback-name"></h4>
                                <p id="feedback-content"></p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
            <button type="button" class="btn btn-warning btn-sm" style="background-color: red;">Delete</button>
            </div> -->
                </div>
            </div>
        </div>

        <!-- ------------ low feedback detail ------------------------ -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <script>
            // Delete confirmation function
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            }
            
            // Chart configuration with modern styling
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Users', 'Services', 'Fixers', 'Customers', 'Categories'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            {{ count($users) }}, 
                            {{ count($Service) }}, 
                            {{ count($users->where('role','fixer')) }}, 
                            {{ count($users->where('role','customer')) }},
                            {{ count($Categories) }}
                        ],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(245, 158, 11, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(168, 85, 247, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#374151',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(229, 231, 235, 0.5)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });

            // -------------detail data--------------------------
            function updateModalContent(event) {
                // Get the button that triggered the modal
                var button = event.relatedTarget;

                // Update the modal content with the data attributes
                document.getElementById('service-image').src = button.dataset.serviceImage;
                document.getElementById('service-title').textContent = button.dataset.serviceTitle;
                document.getElementById('service-description').textContent = button.dataset.serviceDescription;
                document.getElementById('service-stars').textContent = button.dataset.serviceStars;
                document.getElementById('service-category').textContent = button.dataset.serviceCategory;
                document.getElementById('service-price').textContent = button.dataset.servicePrice;
                document.getElementById('service-additional-info').textContent = button.dataset.serviceAdditionalInfo;
            }

            // Attach the update function to both modals
            document.getElementById('lowServiceDetailModel').addEventListener('show.bs.modal', updateModalContent);
            document.getElementById('topServiceDetailModel').addEventListener('show.bs.modal', updateModalContent);


            /// feedback////

            document.getElementById('feedbackDetail').addEventListener('show.bs.modal', function(event) {

                var button = event.relatedTarget;

                document.getElementById('feedback-image').src = button.dataset.feedbackImage;
                document.getElementById('feedback-name').textContent = button.dataset.feedbackName;
                document.getElementById('feedback-content').textContent = button.dataset.feedbackContent;


            });


            document.getElementById('userDetailsModal').addEventListener('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                var button = event.relatedTarget;

                // Update the modal content with the data attributes
                document.getElementById('user-name').textContent = button.dataset.userName;
                document.getElementById('user-email').textContent = button.dataset.userEmail;
                document.getElementById('user-role').textContent = button.dataset.userRole;
                document.getElementById('user-address').textContent = button.dataset.userAddress;
                document.getElementById('user-phone').textContent = button.dataset.userPhone;
                document.getElementById('user-profile').src = button.dataset.userProfile;
                document.getElementById('user-additional-info').textContent = button.dataset.userAdditionalInfo;
            });
        </script>
        @if (session('showAlertDelete'))
            <script>
                Swal.fire({
                    title: "User's eedback deleted Success!",
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ff9800',
                    showCloseButton: true,
                });
            </script>
        @endif
        <style>
            /* Additional Custom Styles */
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .grid {
                display: grid;
            }
            
            .grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            
            .gap-4 {
                gap: 1rem;
            }
            
            .gap-6 {
                gap: 1.5rem;
            }
            
            .space-y-3 > * + * {
                margin-top: 0.75rem;
            }
            
            .space-y-6 > * + * {
                margin-top: 1.5rem;
            }
            
            .truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            @media (min-width: 1024px) {
                .lg\\:grid-cols-2 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                
                .lg\\:grid-cols-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }
            
            @media (min-width: 768px) {
                .md\\:grid-cols-2 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            /* Tablet styles */
            @media (max-width: 991px) and (min-width: 768px) {
                .product-card {
                    width: 9rem;
                    transition: all 0.3s ease-in-out;
                }

                .top-service-card {
                    transition: all 0.3s ease-in-out;
                }

                .top-service-card .evaluation i {
                    font-size: 0.6rem;
                }

                .top-service-card button {
                    font: 0.5em sans-serif;
                    margin-top: 4rem;
                }


                .low-service-card button {
                    font: 0.5em sans-serif;
                }

                .low-service-card {
                    transition: all 0.3s ease-in-out;
                }

                .card-top {
                    width: 8rem;
                    height: 11rem;
                    transition: all 0.3s ease-in-out;
                }

                .topthan-info {
                    overflow-y: auto;
                    height: 300px !important;
                    padding-left: 140px;
                }

                .top-than {
                    height: 270px !important;
                }

                .customer-feedback-card button {
                    font: 0.5em sans-serif;
                }

                .map {
                    width: 650px;
                }

                .bx-menu {
                    display: block;
                }

            }

            /* Mobile styles */
            @media (max-width: 320px) and (min-width:568px) {

                .product-card {
                    justify-Content: space-around;
                    gap: 2rem;
                    width: 5rem;
                    height: 2rem;
                    transition: all 0.1s ease-in-out;
                    font-size: 5px;
                }

                .product-card .content h6 {
                    font-size: 0.5rem;
                    margin: 0;
                }

                .product-card .content p {
                    justify-Content: space-between;
                    font-size: 0.5rem;
                    margin: 0;
                    margin-top: -10px;
                }

                .product-card .icon i {
                    font-size: 2rem;
                    margin-left: -20px;
                    font-size: 1.5rem;
                }

                .product-card .icon .bxs-briefcase-alt-2 {
                    font-size: 1.5rem;
                    margin-left: -20px;
                }

                .product-card .icon .bxs-star {
                    font-size: 1.5rem;
                    margin-left: -30px;
                }

                .product-card .content {
                    margin-left: -15px;
                }

                .product {
                    overflow-x: auto;
                }

                .product {
                    margin-top: -20px;
                }

                .data {
                    margin-top: -40px;
                }

                .top-services {
                    width: 290px;
                    margin-left: -20px;
                }

                .top-service-card {
                    width: 270px;
                    transition: all 0.3s ease-in-out;
                    margin: 0px;
                    margin-left: -20px;
                }

                .top-service-card .evaluation i {
                    font-size: 0.9rem;
                }

                .top-service-card button {
                    font: 0.5em sans-serif;
                    margin-top: 2rem;
                }

                .top-service-card .title .d-flex img {
                    width: 1.5rem !important;
                    height: 1.5rem !important;
                }

                .top-service-card .title .d-flex {
                    font-size: 1rem !important;
                }

                .top-service-info {
                    width: 300px;
                }


                .low-services {
                    width: 290px;
                    margin-left: -20px;
                }

                .low-service-card {
                    width: 270px;
                    transition: all 0.3s ease-in-out;
                    margin: 0px;
                    margin-left: -20px;
                }

                .low-service-card button {
                    font: 0.5em sans-serif;
                    margin-top: 2rem;
                }

                .service-performent {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                    height: 200px;
                }

                .service-performent .title h6 {
                    font-size: 15px;
                }

                .top-than {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                }

                .card-top {
                    width: 8rem;
                    height: 11rem;
                    transition: all 0.3s ease-in-out;
                }

                .topthan-info {
                    overflow-y: auto;
                    height: 300px !important;
                    padding-left: 140px;
                }

                .top-than {
                    height: 270px !important;
                }

                .customer-feedback {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                }

                .customer-feedback-card button {
                    font: 0.5em sans-serif;
                }

                .map {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                    height: 10rem !important;
                    margin-top: -20px;
                }

            }

            /* Mobile styles */
            @media (max-width: 600px) {


                .product-card {
                    justify-Content: space-around;
                    gap: 2rem;
                    width: 5rem;
                    height: 2rem;
                    transition: all 0.1s ease-in-out;
                    font-size: 5px;
                }

                .product-card .content h6 {
                    font-size: 0.5rem;
                    margin: 0;
                }

                .product-card .content p {
                    justify-Content: space-between;
                    font-size: 0.5rem;
                    margin: 0;
                    margin-top: -10px;
                }

                .product-card .icon i {
                    font-size: 2rem;
                    margin-left: -20px;
                    font-size: 1.5rem;
                }

                .product-card .icon .bxs-briefcase-alt-2 {
                    font-size: 1.5rem;
                    margin-left: -20px;
                }

                .product-card .icon .bxs-star {
                    font-size: 1.5rem;
                    margin-left: -30px;
                }

                .product-card .content {
                    margin-left: -15px;
                }

                .product {
                    overflow-x: auto;
                }

                .product {
                    margin-top: -20px;
                }

                .data {
                    margin-top: -40px;
                }

                .top-services {
                    width: 340px;
                    margin-left: -20px;
                }

                .top-service-card {
                    width: 310px;
                    transition: all 0.3s ease-in-out;
                    margin: 0px;
                    margin-left: -20px;
                }

                .top-service-card .evaluation i {
                    font-size: 0.9rem;
                }

                .top-service-card button {
                    font: 0.5em sans-serif;
                }

                .top-service-card .title .d-flex img {
                    width: 1.5rem !important;
                    height: 1.5rem !important;
                }

                .top-service-card .title .d-flex {
                    font-size: 1rem !important;
                }

                .top-service-info {
                    width: 300px;
                }


                .low-services {
                    width: 340px;
                    margin-left: -20px;
                }

                .low-service-card {
                    width: 310px;
                    transition: all 0.3s ease-in-out;
                    margin: 0px;
                    margin-left: -20px;
                }

                .low-service-card button {
                    font: 0.5em sans-serif;
                    margin-top: 2rem;
                }

                .service-performent {
                    margin-top: 20px;
                    width: 310px;
                    margin-left: -20px;
                    height: 200px;
                }

                .service-performent .title h6 {
                    font-size: 15px;
                }

                .top-than {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                }

                .card-top {
                    width: 8rem;
                    height: 11rem;
                    transition: all 0.3s ease-in-out;
                }

                .topthan-info {
                    overflow-y: auto;
                    height: 300px !important;
                    padding-left: 140px;
                }

                .top-than {
                    height: 270px !important;
                }

                .customer-feedback {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                }

                .customer-feedback-card button {
                    font: 0.5em sans-serif;
                }

                .map {
                    margin-top: 20px;
                    width: 290px;
                    margin-left: -20px;
                    height: 10rem !important;
                    margin-top: -20px;
                }

            }


            .modal-content {
                animation: fadeIn 0.5s;
            }

            .modal-header {
                position: relative;
            }

            .modal-header .btn-close {
                position: absolute;
                right: 20px;
                top: 20px;
            }

            .modal-header .modal-title {
                font-weight: bold;
            }

            .modal-body {
                padding: 20px;
            }

            .service-image-container {
                overflow: hidden;
                border-radius: 10px;
            }

            .service-image-container img {
                transition: transform 0.3s ease;
            }

            .service-image-container img:hover {
                transform: scale(1.1);
            }

            .service-details p,
            .rating p,
            .additional-info h5 {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </x-app-layout>
