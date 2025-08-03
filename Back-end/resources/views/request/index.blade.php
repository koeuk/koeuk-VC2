<x-app-layout>
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom-ui.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container" style="margin-top:60px">
        <main class="py-8">
            <!-- Page Header -->
            <div class="mb-8 fade-in">
                <h1 class="text-3xl font-bold text-gray-900">Booking Requests</h1>
                <p class="text-gray-600 mt-2">Manage and respond to customer booking requests</p>
            </div>

            <!-- Filter Controls -->
            <div class="modern-card mb-6 fade-in" style="animation-delay: 0.1s;">
                <div class="flex flex-wrap items-center gap-4">
                    <button id="immediately" class="btn-modern btn-primary">
                        <i class='bx bxs-user-voice'></i>
                        Immediately
                        <span class="badge-modern ml-2">{{ $bookings->filter(fn($b) => $b->action === 'request' && $b->type === 'immediately')->count() }}</span>
                    </button>
                    <button id="dead" class="btn-modern btn-primary">
                        <i class='bx bxs-calendar'></i>
                        Deadline
                        <span class="badge-modern ml-2">{{ $bookings->filter(fn($b) => $b->action === 'request' && $b->type === 'deadline')->count() }}</span>
                    </button>
                    <button id='showAll' class="btn-modern bg-gray-200 text-gray-700 hover:bg-gray-300">
                        <i class='bx bx-show'></i>
                        Show All
                    </button>
                    <div class="ml-auto flex items-center gap-3">
                        <div class="relative">
                            <input type="text" id="searchInput" class="form-control-modern pl-10" placeholder="Search requests...">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requests List -->
            <div class="modern-card fade-in" style="animation-delay: 0.2s;">
                <div class="mb-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Pending Requests</h2>
                    <span class="text-sm text-gray-500">{{ $bookings->filter(fn($b) => $b->action === 'request')->count() }} total requests</span>
                </div>
                
                <div class="space-y-4" id="requestsList">
                    @can('Request access')
                        @forelse ($bookings->filter(function($booking) {
                            return $booking->action === 'request';
                        }) as $booking)
                            @php
                                $service_name = 'No Service selected';
                                $customer = $users->where('id', $booking->user_id)->first();
                                $fixer = $users->where('id', $booking->fixer_id)->first();
                                if($booking->fixer_id !=null){
                                    $fixer_id = $booking->fixer_id;
                                    $fixer_name = $users->where('id', $fixer_id)->pluck('name')->first();
                                }else{
                                    $fixer_name = 'No Fixer selected';
                                }

                                if ($booking->type == 'immediately') {
                                    $booking_date = $immediatelys->where('id', $booking->booking_type_id)->pluck('created_at')->first();
                                    $deadline = 'Fix now';
                                    $customer_message = $immediatelys->where('id', $booking->booking_type_id)->pluck('message')->first();
                                    $customer_imagesend = $immediatelys->where('id', $booking->booking_type_id)->pluck('image')->first();
                                    $service_id = $immediatelys->where('id', $booking->booking_type_id)->pluck('service_id')->first();
                                } elseif ($booking->type == 'deadline') {
                                    $customer_message = $deadlines->where('id', $booking->booking_type_id)->pluck('message')->first();
                                    $customer_imagesend = $deadlines->where('id', $booking->booking_type_id)->pluck('image')->first();
                                    $booking_date = $deadlines->where('id', $booking->booking_type_id)->pluck('created_at')->first();
                                    $deadline = $deadlines->where('id', $booking->booking_type_id)->pluck('date_todo')->first();
                                    $service_id = $deadlines->where('id', $booking->booking_type_id)->pluck('service_id')->first();
                                }

                                if (isset($service_id) && $service_id != null) {
                                    $service_name = $services->where('id', $service_id)->pluck('name')->first();
                                }
                            @endphp
                            
                            <!-- Request Card -->
                            <div class="request-item {{ $booking->type == 'immediately' ? 'immediate' : 'deadline' }} bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-300 hover:border-yellow-400" data-customer="{{ strtolower($customer->name ?? '') }}" data-service="{{ strtolower($service_name) }}">
                                <div class="flex items-start gap-4">
                                    <!-- Customer Avatar -->
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $customer->profile ?? asset('images/default-avatar.png') }}" 
                                             class="w-14 h-14 rounded-full object-cover border-2 border-gray-200"
                                             alt="{{ $customer->name ?? 'Customer' }}">
                                        @if ($booking->type == 'immediately')
                                            <span class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1">
                                                <i class='bx bxs-zap text-white text-xs'></i>
                                            </span>
                                        @else
                                            <span class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-1">
                                                <i class='bx bxs-time text-white text-xs'></i>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Request Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $customer->name ?? 'Unknown Customer' }}</h4>
                                                <p class="text-sm text-gray-500">{{ $customer->email ?? '' }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if ($booking->type == 'immediately')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class='bx bxs-zap mr-1'></i>
                                                        Immediate
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class='bx bxs-calendar mr-1'></i>
                                                        Scheduled
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Service Info -->
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center text-gray-600">
                                                <i class='bx bx-wrench mr-2 text-gray-400'></i>
                                                <span class="font-medium">Service:</span>
                                                <span class="ml-1">{{ $service_name }}</span>
                                            </div>
                                            <div class="flex items-center text-gray-600">
                                                <i class='bx bx-time mr-2 text-gray-400'></i>
                                                <span class="font-medium">Requested:</span>
                                                <span class="ml-1">{{ $booking->created_at->diffForHumans() }}</span>
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
                                    <div class="flex items-center gap-2">
                                        <button class="btn-modern bg-yellow-100 text-yellow-700 hover:bg-yellow-200 py-2 px-4"
                                            data-bs-toggle="modal" data-bs-target="#bookingDetailsModal"
                                            data-booking-image="{{$customer->profile ?? ''}}"
                                            data-booking-stars="{{$service_name}}"
                                            data-booking-type="{{$booking->type}}"
                                            data-booking-date="{{$booking_date}}"
                                            data-booking-deadline="{{$deadline}}"
                                            data-booking-fixname="{{$fixer_name}}"
                                            data-booking-customername="{{$customer->name ?? ''}}"
                                            data-booking-customeremail="{{$customer->email ?? ''}}"
                                            data-booking-customerphone="{{$customer->phone ?? ''}}"
                                            data-booking-customeraddress="{{$customer->address ?? ''}}"
                                            @if($fixer) 
                                                data-booking-fixername="{{$fixer->name}}"
                                                data-booking-fixeremail="{{$fixer->email}}"
                                                data-booking-fixerphone="{{$fixer->phone}}"
                                                data-booking-fixeraddress="{{$fixer->address}}"
                                            @else
                                                data-booking-fixername="No fixer selected"
                                                data-booking-fixeremail="N/A"
                                                data-booking-fixerphone="N/A"
                                                data-booking-fixeraddress="N/A"
                                            @endif
                                            @if($customer_message || $customer_imagesend)
                                                data-booking-customermessage="{{$customer_message}}"
                                                data-booking-customerimage="{{$customer_imagesend}}"
                                                data-booking-nomessage="Customer has sent a message"
                                            @else
                                                data-booking-nomessage="No message from customer"
                                            @endif
                                            data-booking-additional-info="Booking request details">
                                            <i class="bx bx-show"></i>
                                            View Details
                                        </button>
                                        
                                        @can('Request delete')
                                            <button type="button" class="btn-modern bg-red-100 text-red-700 hover:bg-red-200 py-2 px-4"
                                                    onclick="confirmDelete({{ $booking->id }})">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $booking->id }}" 
                                                  action="{{ route('admin.requests.destroy', $booking->id) }}" 
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
                                <i class='bx bx-inbox text-6xl text-gray-300 mb-4'></i>
                                <p class="text-gray-500 text-lg">No booking requests at the moment</p>
                                <p class="text-gray-400 text-sm mt-2">New requests will appear here</p>
                            </div>
                        @endforelse
                    @endcan
                </div>
            </div>
        </main>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade modal-modern" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">
                        <i class='bx bx-detail mr-2'></i>
                        Booking Request Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close'></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Navigation Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <button class="tab-button active" data-tab="overview">
                                <i class='bx bx-info-circle mr-2'></i>
                                Overview
                            </button>
                            <button class="tab-button" data-tab="customer">
                                <i class='bx bx-user mr-2'></i>
                                Customer
                            </button>
                            <button class="tab-button" data-tab="fixer">
                                <i class='bx bx-wrench mr-2'></i>
                                Fixer
                            </button>
                            <button class="tab-button" data-tab="message" id="message-tab">
                                <i class='bx bx-message-detail mr-2'></i>
                                Message
                            </button>
                        </nav>
                    </div>
                    
                    <div class="p-6">
                        <!-- Overview Tab -->
                        <div class="tab-content" id="overview-tab">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h3>
                                    <dl class="space-y-3">
                                        <div class="flex items-center">
                                            <dt class="text-sm font-medium text-gray-500 w-24">Type:</dt>
                                            <dd class="text-sm text-gray-900" id="booking-type-display"></dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-sm font-medium text-gray-500 w-24">Service:</dt>
                                            <dd class="text-sm text-gray-900" id="booking-stars"></dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-sm font-medium text-gray-500 w-24">Date:</dt>
                                            <dd class="text-sm text-gray-900" id="booking-date"></dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-sm font-medium text-gray-500 w-24">Deadline:</dt>
                                            <dd class="text-sm text-gray-900" id="booking-deadline"></dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-sm font-medium text-gray-500 w-24">Assigned to:</dt>
                                            <dd class="text-sm text-gray-900" id="booking-fixName"></dd>
                                        </div>
                                    </dl>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                                    <div class="space-y-3">
                                        <button class="w-full btn-modern btn-primary">
                                            <i class='bx bx-user-check'></i>
                                            Assign Fixer
                                        </button>
                                        <button class="w-full btn-modern bg-green-100 text-green-700 hover:bg-green-200">
                                            <i class='bx bx-check-circle'></i>
                                            Accept Request
                                        </button>
                                        <button class="w-full btn-modern bg-gray-100 text-gray-700 hover:bg-gray-200">
                                            <i class='bx bx-message'></i>
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Tab -->
                        <div class="tab-content hidden" id="customer-tab">
                            <div class="flex items-start gap-6">
                                <img src="" id="booking-image" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900" id="booking-customerName"></h3>
                                    <p class="text-gray-600" id="booking-customerEmail"></p>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center text-sm">
                                            <i class='bx bx-phone mr-2 text-gray-400'></i>
                                            <span id="booking-customerPhone"></span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class='bx bx-map mr-2 text-gray-400'></i>
                                            <span id="booking-customerAddress"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fixer Tab -->
                        <div class="tab-content hidden" id="fixer-tab">
                            <div class="flex items-start gap-6">
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class='bx bx-user text-4xl text-gray-400'></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900" id="booking-fixerName"></h3>
                                    <p class="text-gray-600" id="booking-fixerEmail"></p>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center text-sm">
                                            <i class='bx bx-phone mr-2 text-gray-400'></i>
                                            <span id="booking-fixerPhone"></span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class='bx bx-map mr-2 text-gray-400'></i>
                                            <span id="booking-fixerAddress"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Message Tab -->
                        <div class="tab-content hidden" id="message-tab-content">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Message</h3>
                                <div id="message-content">
                                    <p class="text-gray-600 mb-4" id="booking-customerMessage"></p>
                                    <div id="customer-image-container" class="hidden">
                                        <img src="" id="booking-customerSendImage" class="max-w-full rounded-lg shadow-md">
                                    </div>
                                </div>
                                <div id="no-message" class="text-center py-8 hidden">
                                    <i class='bx bx-message-x text-4xl text-gray-300 mb-2'></i>
                                    <p class="text-gray-500" id="booking-nomessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('showAlertDelete'))
        <script>
            Swal.fire({
                title: 'Booking deleted successfully!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff9800',
                showCloseButton: true,
            });
        </script>
    @endif

    <style>
        /* Additional Styles */
        .tab-button {
            white-space: nowrap;
            padding: 1rem 0.25rem;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            font-size: 0.875rem;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
            background: none;
            border-radius: 0;
            display: inline-flex;
            align-items: center;
        }
        
        .tab-button:hover {
            color: #f59e0b;
            border-color: #fef3c7;
        }
        
        .tab-button.active {
            color: #f59e0b;
            border-color: #f59e0b;
        }
        
        .tab-content {
            animation: fadeIn 0.3s ease-out;
        }
        
        .request-item {
            position: relative;
            overflow: hidden;
        }
        
        .request-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: transparent;
            transition: background 0.3s;
        }
        
        .request-item:hover::before {
            background: #f59e0b;
        }
        
        /* Utility Classes */
        .space-y-4 > * + * {
            margin-top: 1rem;
        }
        
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
        
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }
        
        .grid {
            display: grid;
        }
        
        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
        
        @media (min-width: 768px) {
            .md\\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        
        .gap-6 {
            gap: 1.5rem;
        }
        
        .gap-4 {
            gap: 1rem;
        }
        
        .ml-auto {
            margin-left: auto;
        }
        
        .ml-2 {
            margin-left: 0.5rem;
        }
        
        .ml-1 {
            margin-left: 0.25rem;
        }
        
        .mr-2 {
            margin-right: 0.5rem;
        }
        
        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mt-2 {
            margin-top: 0.5rem;
        }
        
        .mt-4 {
            margin-top: 1rem;
        }
        
        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }
        
        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }
        
        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        
        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }
        
        .text-6xl {
            font-size: 3.75rem;
            line-height: 1;
        }
        
        .text-4xl {
            font-size: 2.25rem;
            line-height: 2.5rem;
        }
        
        .font-bold {
            font-weight: 700;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        .font-medium {
            font-weight: 500;
        }
        
        .text-gray-900 {
            color: #111827;
        }
        
        .text-gray-700 {
            color: #374151;
        }
        
        .text-gray-600 {
            color: #4b5563;
        }
        
        .text-gray-500 {
            color: #6b7280;
        }
        
        .text-gray-400 {
            color: #9ca3af;
        }
        
        .text-gray-300 {
            color: #d1d5db;
        }
        
        .text-green-800 {
            color: #166534;
        }
        
        .text-blue-800 {
            color: #1e40af;
        }
        
        .text-red-700 {
            color: #b91c1c;
        }
        
        .text-yellow-700 {
            color: #a16207;
        }
        
        .text-white {
            color: #ffffff;
        }
        
        .bg-gray-50 {
            background-color: #f9fafb;
        }
        
        .bg-gray-200 {
            background-color: #e5e7eb;
        }
        
        .bg-green-100 {
            background-color: #dcfce7;
        }
        
        .bg-blue-100 {
            background-color: #dbeafe;
        }
        
        .bg-red-100 {
            background-color: #fee2e2;
        }
        
        .bg-yellow-100 {
            background-color: #fef3c7;
        }
        
        .bg-yellow-500 {
            background-color: #f59e0b;
        }
        
        .bg-green-500 {
            background-color: #10b981;
        }
        
        .bg-blue-500 {
            background-color: #3b82f6;
        }
        
        .border {
            border-width: 1px;
        }
        
        .border-2 {
            border-width: 2px;
        }
        
        .border-4 {
            border-width: 4px;
        }
        
        .border-gray-200 {
            border-color: #e5e7eb;
        }
        
        .border-b {
            border-bottom-width: 1px;
        }
        
        .rounded-lg {
            border-radius: 0.5rem;
        }
        
        .rounded-full {
            border-radius: 9999px;
        }
        
        .p-4 {
            padding: 1rem;
        }
        
        .p-6 {
            padding: 1.5rem;
        }
        
        .p-1 {
            padding: 0.25rem;
        }
        
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .px-2\\.5 {
            padding-left: 0.625rem;
            padding-right: 0.625rem;
        }
        
        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .py-12 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .py-0\\.5 {
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
        }
        
        .pl-10 {
            padding-left: 2.5rem;
        }
        
        .flex {
            display: flex;
        }
        
        .inline-flex {
            display: inline-flex;
        }
        
        .hidden {
            display: none;
        }
        
        .flex-wrap {
            flex-wrap: wrap;
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
        
        .w-14 {
            width: 3.5rem;
        }
        
        .h-14 {
            height: 3.5rem;
        }
        
        .w-24 {
            width: 6rem;
        }
        
        .h-24 {
            height: 6rem;
        }
        
        .w-full {
            width: 100%;
        }
        
        .max-w-full {
            max-width: 100%;
        }
        
        .relative {
            position: relative;
        }
        
        .absolute {
            position: absolute;
        }
        
        .-bottom-1 {
            bottom: -0.25rem;
        }
        
        .-right-1 {
            right: -0.25rem;
        }
        
        .left-3 {
            left: 0.75rem;
        }
        
        .top-1\\/2 {
            top: 50%;
        }
        
        .transform {
            transform: translateX(0) translateY(0) rotate(0) skewX(0) skewY(0) scaleX(1) scaleY(1);
        }
        
        .-translate-y-1\\/2 {
            transform: translateY(-50%);
        }
        
        .object-cover {
            object-fit: cover;
        }
        
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        
        .hover\\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        .hover\\:border-yellow-400:hover {
            border-color: #fbbf24;
        }
        
        .hover\\:bg-gray-300:hover {
            background-color: #d1d5db;
        }
        
        .hover\\:bg-yellow-200:hover {
            background-color: #fde68a;
        }
        
        .hover\\:bg-green-200:hover {
            background-color: #bbf7d0;
        }
        
        .hover\\:bg-red-200:hover {
            background-color: #fecaca;
        }
        
        .hover\\:bg-gray-200:hover {
            background-color: #e5e7eb;
        }
        
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .duration-300 {
            transition-duration: 300ms;
        }
        
        .text-center {
            text-align: center;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        // Delete confirmation
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
        
        // Filter functionality
        const immediatelyButton = document.querySelector('#immediately');
        const deadButton = document.querySelector('#dead');
        const showAllButton = document.querySelector('#showAll');
        const searchInput = document.querySelector('#searchInput');
        const requestItems = document.querySelectorAll('.request-item');
        
        function filterRequests(type) {
            requestItems.forEach(item => {
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
                btn.classList.remove('bg-yellow-500', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            if (type === 'immediate') {
                immediatelyButton.classList.remove('bg-gray-200', 'text-gray-700');
                immediatelyButton.classList.add('bg-yellow-500', 'text-white');
            } else if (type === 'deadline') {
                deadButton.classList.remove('bg-gray-200', 'text-gray-700');
                deadButton.classList.add('bg-yellow-500', 'text-white');
            } else {
                showAllButton.classList.remove('bg-gray-200', 'text-gray-700');
                showAllButton.classList.add('bg-yellow-500', 'text-white');
            }
        }
        
        immediatelyButton.addEventListener('click', () => filterRequests('immediate'));
        deadButton.addEventListener('click', () => filterRequests('deadline'));
        showAllButton.addEventListener('click', () => filterRequests('all'));
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            requestItems.forEach(item => {
                const customerName = item.getAttribute('data-customer');
                const serviceName = item.getAttribute('data-service');
                
                if (customerName.includes(searchTerm) || serviceName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        
        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                
                // Update active states
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Show/hide content
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(`${targetTab}-tab${targetTab === 'message' ? '-content' : ''}`).classList.remove('hidden');
            });
        });

        // Modal data population
        document.getElementById('bookingDetailsModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Reset to overview tab
            document.querySelector('.tab-button[data-tab="overview"]').click();
            
            // Populate data
            document.getElementById('booking-image').src = button.dataset.bookingImage || 'https://via.placeholder.com/150';
            document.getElementById('booking-customerSendImage').src = button.dataset.bookingCustomerimage || '';
            document.getElementById('booking-nomessage').textContent = button.dataset.bookingNomessage;
            document.getElementById('booking-stars').textContent = button.dataset.bookingStars;
            document.getElementById('booking-type-display').textContent = button.dataset.bookingType === 'immediately' ? 'Immediate Service' : 'Scheduled Service';
            document.getElementById('booking-date').textContent = button.dataset.bookingDate;
            document.getElementById('booking-deadline').textContent = button.dataset.bookingDeadline;
            document.getElementById('booking-fixName').textContent = button.dataset.bookingFixname;
            document.getElementById('booking-customerName').textContent = button.dataset.bookingCustomername;
            document.getElementById('booking-customerEmail').textContent = button.dataset.bookingCustomeremail;
            document.getElementById('booking-customerPhone').textContent = button.dataset.bookingCustomerphone || 'Not provided';
            document.getElementById('booking-customerAddress').textContent = button.dataset.bookingCustomeraddress || 'Not provided';
            document.getElementById('booking-customerMessage').textContent = button.dataset.bookingCustomermessage || 'No message';
            document.getElementById('booking-fixerName').textContent = button.dataset.bookingFixername;
            document.getElementById('booking-fixerEmail').textContent = button.dataset.bookingFixeremail;
            document.getElementById('booking-fixerPhone').textContent = button.dataset.bookingFixerphone;
            document.getElementById('booking-fixerAddress').textContent = button.dataset.bookingFixeraddress;
            
            // Handle message tab
            const hasMessage = button.dataset.bookingCustomermessage || button.dataset.bookingCustomerimage;
            const messageContent = document.getElementById('message-content');
            const noMessage = document.getElementById('no-message');
            const customerImage = document.getElementById('customer-image-container');
            
            if (hasMessage) {
                messageContent.classList.remove('hidden');
                noMessage.classList.add('hidden');
                if (button.dataset.bookingCustomerimage) {
                    customerImage.classList.remove('hidden');
                } else {
                    customerImage.classList.add('hidden');
                }
            } else {
                messageContent.classList.add('hidden');
                noMessage.classList.remove('hidden');
            }
        });

    </script>
</x-app-layout>
