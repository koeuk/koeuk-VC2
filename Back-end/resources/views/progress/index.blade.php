<x-app-layout>
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom-ui.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container" style="margin-top:60px">
        <main class="py-8">
            <!-- Page Header -->
            <div class="mb-8 fade-in">
                <h1 class="text-3xl font-bold text-gray-900">Progress Tracking</h1>
                <p class="text-gray-600 mt-2">Monitor ongoing service requests and their progress</p>
            </div>

            <!-- Filter Controls -->
            <div class="modern-card mb-6 fade-in" style="animation-delay: 0.1s;">
                <div class="flex flex-wrap items-center gap-4">
                    <button id="immediately" class="btn-modern btn-primary">
                        <i class='bx bxs-user-voice'></i>
                        Immediate
                        <span class="badge-modern ml-2">{{ $fixing_progress->filter(fn($p) => $p->action === 'progress' && $bookings->where('id', $p->booking_id)->first()?->type === 'immediately')->count() }}</span>
                    </button>
                    <button id="dead" class="btn-modern btn-primary">
                        <i class='bx bxs-calendar'></i>
                        Scheduled
                        <span class="badge-modern ml-2">{{ $fixing_progress->filter(fn($p) => $p->action === 'progress' && $bookings->where('id', $p->booking_id)->first()?->type === 'deadline')->count() }}</span>
                    </button>
                    <button id='showAll' class="btn-modern bg-gray-200 text-gray-700 hover:bg-gray-300">
                        <i class='bx bx-show'></i>
                        Show All
                    </button>
                    <div class="ml-auto flex items-center gap-3">
                        <div class="relative">
                            <input type="text" id="searchInput" class="form-control-modern pl-10" placeholder="Search progress...">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $fixing_progress->where('action', 'progress')->count() }} active
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress List -->
            <div class="modern-card fade-in" style="animation-delay: 0.2s;">
                <div class="mb-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Active Services</h2>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class='bx bxs-wrench mr-1'></i>
                            In Progress
                        </span>
                    </div>
                </div>
                
                <div class="space-y-4" id="progressList">
                    @can('Progress access')
                        @php
                            $fixpro = $fixing_progress->where('action', 'progress');
                        @endphp
                        @forelse ($fixpro as $ha)
                            @php
                                $booking = $bookings->where('id', $ha->booking_id)->first();
                                if (empty($booking)) continue;
                                
                                $service_name = 'No Service selected';
                                $customer = $users->where('id', $booking->user_id)->first();
                                $fixer = $users->where('id', $booking->fixer_id)->first();
                                $fixer_name = $fixer ? $fixer->name : 'No Fixer assigned';

                                if ($booking->type == 'immediately') {
                                    $deadline = 'ASAP';
                                    $customer_message = $immediatelys->where('id', $booking->booking_type_id)->pluck('message')->first();
                                    $customer_imagesend = $immediatelys->where('id', $booking->booking_type_id)->pluck('image')->first();
                                    $service_id = $immediatelys->where('id', $booking->booking_type_id)->pluck('service_id')->first();
                                } else {
                                    $customer_message = $deadlines->where('id', $booking->booking_type_id)->pluck('message')->first();
                                    $customer_imagesend = $deadlines->where('id', $booking->booking_type_id)->pluck('image')->first();
                                    $deadline = $deadlines->where('id', $booking->booking_type_id)->pluck('date')->first();
                                    $service_id = $deadlines->where('id', $booking->booking_type_id)->pluck('service_id')->first();
                                }
                                
                                if (isset($service_id) && $service_id != null) {
                                    $service_name = $services->where('id', $service_id)->pluck('name')->first();
                                }
                                
                                $progress_percentage = rand(25, 85); // You can replace this with actual progress calculation
                            @endphp
                            
                            <!-- Progress Item -->
                            <div class="progress-item {{ $booking->type == 'immediately' ? 'immediate' : 'deadline' }} bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-300 hover:border-green-400" data-customer="{{ strtolower($customer->name ?? '') }}" data-service="{{ strtolower($service_name) }}">
                                <div class="flex items-start gap-4">
                                    <!-- Customer Avatar with Progress Ring -->
                                    <div class="relative flex-shrink-0">
                                        <div class="relative">
                                            <svg class="w-16 h-16 transform -rotate-90">
                                                <circle cx="32" cy="32" r="28" stroke="#e5e7eb" stroke-width="4" fill="none"/>
                                                <circle cx="32" cy="32" r="28" stroke="#10b981" stroke-width="4" fill="none" 
                                                        stroke-dasharray="{{ 2 * 3.14159 * 28 }}" 
                                                        stroke-dashoffset="{{ 2 * 3.14159 * 28 * (1 - $progress_percentage / 100) }}" 
                                                        stroke-linecap="round"/>
                                            </svg>
                                            <img src="{{ $customer->profile ?? asset('images/default-avatar.png') }}" 
                                                 class="w-12 h-12 rounded-full object-cover absolute top-2 left-2"
                                                 alt="{{ $customer->name ?? 'Customer' }}">
                                        </div>
                                        <span class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1">
                                            <i class='bx bxs-wrench text-white text-xs'></i>
                                        </span>
                                    </div>
                                    
                                    <!-- Progress Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $customer->name ?? 'Unknown Customer' }}</h4>
                                                <p class="text-sm text-gray-500">{{ $service_name }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if ($booking->type == 'immediately')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class='bx bxs-zap mr-1'></i>
                                                        Urgent
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class='bx bxs-calendar mr-1'></i>
                                                        Scheduled
                                                    </span>
                                                @endif
                                                <span class="text-sm font-medium text-green-600">{{ $progress_percentage }}%</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Progress Bar -->
                                        <div class="mb-3">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-600">Progress</span>
                                                <span class="text-gray-900 font-medium">{{ $progress_percentage }}% Complete</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Service Info -->
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div class="flex items-center text-gray-600">
                                                <i class='bx bx-user mr-2 text-gray-400'></i>
                                                <span class="font-medium">Fixer:</span>
                                                <span class="ml-1">{{ $fixer_name }}</span>
                                            </div>
                                            <div class="flex items-center text-gray-600">
                                                <i class='bx bx-time mr-2 text-gray-400'></i>
                                                <span class="font-medium">Started:</span>
                                                <span class="ml-1">{{ $ha->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if ($booking->type == 'deadline' && $deadline)
                                                <div class="flex items-center text-gray-600">
                                                    <i class='bx bx-calendar-check mr-2 text-gray-400'></i>
                                                    <span class="font-medium">Deadline:</span>
                                                    <span class="ml-1">{{ \Carbon\Carbon::parse($deadline)->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex flex-col gap-2">
                                        <button class="btn-modern bg-green-100 text-green-700 hover:bg-green-200 py-2 px-4"
                                                data-bs-toggle="modal" data-bs-target="#bookingDetailsModal"
                                                data-booking-image="{{ $customer->profile ?? '' }}"
                                                data-booking-stars="{{ $service_name }}"
                                                data-booking-type="{{ $booking->type }}"
                                                data-booking-date="{{ $booking->created_at }}"
                                                data-booking-deadline="{{ $deadline }}"
                                                data-booking-fixname="{{ $fixer_name }}"
                                                data-booking-customername="{{ $customer->name ?? '' }}"
                                                data-booking-customeremail="{{ $customer->email ?? '' }}"
                                                data-booking-customerphone="{{ $customer->phone ?? '' }}"
                                                data-booking-customeraddress="{{ $customer->address ?? '' }}"
                                                @if ($fixer)
                                                    data-booking-fixername="{{ $fixer->name }}"
                                                    data-booking-fixeremail="{{ $fixer->email }}"
                                                    data-booking-fixerphone="{{ $fixer->phone }}"
                                                    data-booking-fixeraddress="{{ $fixer->address }}"
                                                @else
                                                    data-booking-fixername="No fixer assigned"
                                                    data-booking-fixeremail="N/A"
                                                    data-booking-fixerphone="N/A"
                                                    data-booking-fixeraddress="N/A"
                                                @endif
                                                @if ($customer_message || $customer_imagesend)
                                                    data-booking-customermessage="{{ $customer_message }}"
                                                    data-booking-customerimage="{{ $customer_imagesend }}"
                                                    data-booking-nomessage="Customer has sent details"
                                                @else
                                                    data-booking-nomessage="No additional message"
                                                @endif
                                                data-booking-additional-info="Service progress details">
                                            <i class="bx bx-show"></i>
                                            View Details
                                        </button>
                                        
                                        @can('Request delete')
                                            <button type="button" class="btn-modern bg-red-100 text-red-700 hover:bg-red-200 py-2 px-4"
                                                    onclick="confirmDelete({{ $booking->id }})">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $booking->id }}" 
                                                  action="{{ route('admin.progresss.destroy', $booking->id) }}" 
                                                  method="POST" class="hidden">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class='bx bx-cog text-6xl text-gray-300 mb-4'></i>
                                <p class="text-gray-500 text-lg">No services in progress</p>
                                <p class="text-gray-400 text-sm mt-2">Active services will appear here</p>
                            </div>
                        @endforelse
                    @endcan
                </div>
            </div>
        </main>
    </div>

    <!-- Progress Details Modal -->
    <div class="modal fade modal-modern" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">
                        <i class='bx bx-trending-up mr-2'></i>
                        Service Progress Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close'></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5" id='image-customer'style='display:none'>
                            <div class="service-image-container" style="height: 230px;">
                                <img src="" class="img-fluid rounded"
                                    style="object-fit: cover;width:100%;height:100%" alt="Base64 Image"
                                    id="booking-customerSendImage">
                                <span class="bg-warning p-2 border rounded-lg text-white"
                                    id='booking-customerMessage'></span>
                            </div>
                            <div class="btn mt-5 text-center " id='back'>
                                <i class="bx bx-arrow-back mr-2 animate-pulse"></i>
                                Back
                            </div>
                        </div>
                        <!-- ---------------------customer profile------------------------ -->
                        <div class="col-md-5" id='profile-customer'>
                            <div class="booking-image-container" style="height: 230px;">
                                <div class="d-flex align-items-center gap-3  ">
                                    <img src="" class="card rounded-circle" alt="..."
                                        style="height: 5rem; width: 5rem;" alt="Booking Image" id="booking-image">
                                    <div>
                                        <h5 id='booking-customerName'></h5>
                                        <span class="text-gray-600 text-sm" style="font-size: 12px;"
                                            id='booking-customerEmail'></span>
                                    </div>
                                </div>
                                <div class="user-details-body mt-4">
                                    <div class="user-details d-flex align-items-center">
                                        <i class='bx bxs-user-detail'></i>
                                        <span class="ms-2"><strong>Role: </strong> <span
                                                id="user-role">Custommer</span></span>
                                    </div>
                                    <div class="user-details d-flex align-items-center">
                                        <i class='bx bxs-map'></i>
                                        <span class="ms-2 mt-3"><strong>Address:</strong> <span
                                                id='booking-customerAddress'></span></span>
                                    </div>
                                    <div class="user-details d-flex align-items-center">
                                        <i class='bx bxs-phone'></i>
                                        <span class="ms-2 mt-3"><strong>Phone:</strong> <span
                                                id='booking-customerPhone'></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="btn mt-3 text-center " id='customer_message'>
                                Custommer Message
                            </div>
                            <i class='bx bxs-message-rounded-dots bx-tada text-yellow-300 text-3xl -ml-3'></i>
                        </div>
                        <!-- --------------------------------customer profile end------------------------------- -->
                        <!-- --------------------------------fixer profile ------------------------------- -->
                        <div class="col-md-5" id='profile-fixer' style='display:none'>
                            <div class="booking-image-container" style="height: 230px;">
                                <div class="d-flex align-items-center gap-3  ">
                                    <img src="" class="card rounded-circle" alt="..."
                                        style="height: 5rem; width: 5rem;" alt="Booking Image" id="booking-profile">
                                    <div>
                                        <h5 id='booking-fixerName'></h5>
                                        <span class="text-gray-600 text-sm" style="font-size: 12px;"
                                            id='booking-fixerEmail'></span>
                                    </div>
                                </div>
                                <div class="user-details-body mt-4">
                                    <div class="user-details d-flex align-items-center">
                                        <i class='bx bxs-user-detail'></i>
                                        <span class="ms-2"><strong>Role: </strong> <span
                                                id="user-role">Fixer</span></span>
                                    </div>
                                    <div class="user-details d-flex align-items-center">
                                        <i class='bx bxs-map'></i>
                                        <span class="ms-2 mt-3"><strong>Address:</strong> <span
                                                id='booking-fixerAddress'></span></span>
                                    </div>
                                    <div class="user-details d-flex align-items-center">
                                        <i class='bx bxs-phone'></i>
                                        <span class="ms-2 mt-3"><strong>Phone:</strong> <span
                                                id='booking-fixerPhone'></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="btn  text-center " id='backs'>
                                <i class="bx bx-arrow-back mr-2 animate-pulse"></i>
                                Back
                            </div>
                        </div>
                        <div class="col-md-7 mt-4" id='booking-info'>
                            <div class="shadow p-2 " style='background-color: #f8f9fa;'>
                                <h4 class="text-warning" id="booking-type">
                                </h4>
                                <div class="rating mb-3">
                                    <p><i class='bx bxs-star'></i> <strong>Service: </strong> <span
                                            id="booking-stars"></span></p>
                                </div>
                                <div class="booking-details">
                                    <p><i class='bx bxs-calendar'></i> <strong>Booking date: </strong><span
                                            id="booking-date"></span></p>
                                    <p><i class='bx bxs-calendar-check'></i> <strong>Deadline: </strong><span
                                            id="booking-deadline"></span></p>
                                    <p><i class='bx bxs-user'></i> <strong>Fixer: </strong><span
                                            id="booking-fixName"></span><span class="btn text-center"
                                            id='fixer_view'><i
                                                class="bx bx-right-arrow-alt animate-pulse "style='font-size: 2rem;'></i></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="additional-info m-3">
                    <h5><i class='bx bx-info-circle'></i> Additional Information</h5>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('showAlertDelete'))
        <script>
            Swal.fire({
                title: 'Booking deleted successfully!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff9800',
                showCloseButton: true,
            });
        </script>
    @endif

    <style>
        /* Additional Styles for Progress Page */
        .progress-item {
            position: relative;
            overflow: hidden;
        }
        
        .progress-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: transparent;
            transition: background 0.3s;
        }
        
        .progress-item:hover::before {
            background: #10b981;
        }
        
        .space-y-4 > * + * {
            margin-top: 1rem;
        }
        
        .grid {
            display: grid;
        }
        
        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        .gap-4 {
            gap: 1rem;
        }
        
        .gap-2 {
            gap: 0.5rem;
        }
        
        .flex {
            display: flex;
        }
        
        .flex-col {
            flex-direction: column;
        }
        
        .items-center {
            align-items: center;
        }
        
        .items-start {
            align-items: flex-start;
        }
        
        .justify-between {
            justify-content: space-between;
        }
        
        .flex-1 {
            flex: 1 1 0%;
        }
        
        .flex-shrink-0 {
            flex-shrink: 0;
        }
        
        .min-w-0 {
            min-width: 0;
        }
        
        .ml-1 {
            margin-left: 0.25rem;
        }
        
        .mr-2 {
            margin-right: 0.5rem;
        }
        
        .mb-3 {
            margin-bottom: 0.75rem;
        }
        
        .w-16 {
            width: 4rem;
        }
        
        .h-16 {
            height: 4rem;
        }
        
        .w-12 {
            width: 3rem;
        }
        
        .h-12 {
            height: 3rem;
        }
        
        .w-2 {
            width: 0.5rem;
        }
        
        .h-2 {
            height: 0.5rem;
        }
        
        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        
        .font-medium {
            font-weight: 500;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        .text-gray-400 {
            color: #9ca3af;
        }
        
        .text-gray-500 {
            color: #6b7280;
        }
        
        .text-gray-600 {
            color: #4b5563;
        }
        
        .text-gray-900 {
            color: #111827;
        }
        
        .text-green-600 {
            color: #059669;
        }
        
        .text-red-800 {
            color: #991b1b;
        }
        
        .text-blue-800 {
            color: #1e40af;
        }
        
        .text-white {
            color: #ffffff;
        }
        
        .bg-gray-200 {
            background-color: #e5e7eb;
        }
        
        .bg-green-500 {
            background-color: #10b981;
        }
        
        .bg-red-100 {
            background-color: #fee2e2;
        }
        
        .bg-blue-100 {
            background-color: #dbeafe;
        }
        
        .rounded-full {
            border-radius: 9999px;
        }
        
        .absolute {
            position: absolute;
        }
        
        .relative {
            position: relative;
        }
        
        .top-2 {
            top: 0.5rem;
        }
        
        .left-2 {
            left: 0.5rem;
        }
        
        .-bottom-1 {
            bottom: -0.25rem;
        }
        
        .-right-1 {
            right: -0.25rem;
        }
        
        .p-1 {
            padding: 0.25rem;
        }
        
        .px-2\.5 {
            padding-left: 0.625rem;
            padding-right: 0.625rem;
        }
        
        .py-0\.5 {
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
        }
        
        .inline-flex {
            display: inline-flex;
        }
        
        .object-cover {
            object-fit: cover;
        }
        
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .duration-300 {
            transition-duration: 300ms;
        }
    </style>

    <script>
        // Delete confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will stop the service progress!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, stop it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
        
        // Filter functionality
        const immediatelyButton = document.querySelector('#immediately');
        const deadButton = document.querySelector('#dead');
        const showAllButton = document.querySelector('#showAll');
        const searchInput = document.querySelector('#searchInput');
        const progressItems = document.querySelectorAll('.progress-item');
        
        function filterProgress(type) {
            progressItems.forEach(item => {
                if (type === 'all') {
                    item.style.display = 'block';
                } else if (type === 'immediate' && item.classList.contains('immediate')) {
                    item.style.display = 'block';
                } else if (type === 'deadline' && item.classList.contains('deadline')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Update button states
            [immediatelyButton, deadButton, showAllButton].forEach(btn => {
                btn.classList.remove('bg-green-500', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            if (type === 'immediate') {
                immediatelyButton.classList.remove('bg-gray-200', 'text-gray-700');
                immediatelyButton.classList.add('bg-green-500', 'text-white');
            } else if (type === 'deadline') {
                deadButton.classList.remove('bg-gray-200', 'text-gray-700');
                deadButton.classList.add('bg-green-500', 'text-white');
            } else {
                showAllButton.classList.remove('bg-gray-200', 'text-gray-700');
                showAllButton.classList.add('bg-green-500', 'text-white');
            }
        }
        
        immediatelyButton.addEventListener('click', () => filterProgress('immediate'));
        deadButton.addEventListener('click', () => filterProgress('deadline'));
        showAllButton.addEventListener('click', () => filterProgress('all'));
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            progressItems.forEach(item => {
                const customerName = item.getAttribute('data-customer');
                const serviceName = item.getAttribute('data-service');
                
                if (customerName.includes(searchTerm) || serviceName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        let close = document.querySelector('#close');
        let back = document.querySelector('#back');
        let backs = document.querySelector('#backs');
        let customer_message = document.querySelector('#customer_message');
        let image_customer = document.querySelector('#image-customer');
        let profile_customer = document.querySelector('#profile-customer');
        let profile_fixer = document.querySelector('#profile-fixer');
        let booking_info = document.querySelector('#booking-info');
        let fixer_view = document.querySelector('#fixer_view');

        backs.addEventListener('click', function() {
            profile_fixer.style.display = 'none';
            booking_info.style.display = 'block';
        });
        close.addEventListener('click', function() {
            profile_fixer.style.display = 'none';
            booking_info.style.display = 'block';
        });
        back.addEventListener('click', function() {
            image_customer.style.display = 'none';
            profile_customer.style.display = 'block';
        });
        close.addEventListener('click', function() {
            image_customer.style.display = 'none';
            profile_customer.style.display = 'block';
        });
        customer_message.addEventListener('click', function() {
            image_customer.style.display = 'block';
            profile_customer.style.display = 'none';
        });

        fixer_view.addEventListener('click', function() {
            profile_fixer.style.display = 'block';
            booking_info.style.display = 'none';
        });

        document.getElementById('bookingDetailsModal').addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            document.getElementById('booking-image').src = button.dataset.bookingImage;
            document.getElementById('booking-customerSendImage').src = button.dataset.bookingCustomerimage;
            document.getElementById('customer_message').textContent = button.dataset.bookingNomessage;
            document.getElementById('booking-stars').textContent = button.dataset.bookingStars;
            document.getElementById('booking-type').textContent = button.dataset.bookingType;
            document.getElementById('booking-date').textContent = button.dataset.bookingDate;
            document.getElementById('booking-deadline').textContent = button.dataset.bookingDeadline;
            document.getElementById('booking-fixName').textContent = button.dataset.bookingFixname;
            document.getElementById('booking-customerName').textContent = button.dataset.bookingCustomername;
            document.getElementById('booking-customerEmail').textContent = button.dataset.bookingCustomeremail;
            document.getElementById('booking-customerPhone').textContent = button.dataset.bookingCustomerphone;
            document.getElementById('booking-customerAddress').textContent = button.dataset.bookingCustomeraddress;
            document.getElementById('booking-customerMessage').textContent = button.dataset.bookingCustomermessage;
            document.getElementById('booking-fixerName').textContent = button.dataset.bookingFixername;
            document.getElementById('booking-fixerEmail').textContent = button.dataset.bookingFixeremail;
            document.getElementById('booking-fixerPhone').textContent = button.dataset.bookingFixerphone;
            document.getElementById('booking-fixerAddress').textContent = button.dataset.bookingFixeraddress;
            document.getElementById('booking-customerMessage').textContent = button.dataset.bookingCustomermessage;
            document.getElementById('booking-additional-info').textContent = button.dataset.bookingAdditionalInfo;
        });
    </script>
</x-app-layout>
