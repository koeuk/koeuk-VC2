  <x-app-layout>
  <div style="margin-top:60px">
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
      <div class="container mx-auto px-6 py-8">
        <div class="product w-100 h-20 d-flex justify-content-around">
          <div class="product-card bg-white w-44 p-4 rounded-lg border-l-4 border-yellow-500 shadow-md hover:scale-110 transition-all duration-300 flex justify-between items-center">
            <div class="content flex flex-col items-start">
              <h6 class="text-lg font-medium text-gray-800 mb-1">Categories</h6>
              <p class="text-gray-600 text-sm">{{count($Categories)}}</p>
            </div>
            <div class="icon">
              <i class='bx bxs-category text-yellow-500 text-3xl hover:text-4xl transition-all duration-400'></i>
            </div>
          </div>
          <div class="product-card bg-white w-44 p-4 rounded-lg border-l-4 border-yellow-500 shadow-md flex justify-between items-center">
            <div class="content flex flex-col items-start">
              <h6 class="text-lg font-medium text-gray-800 mb-1">Services</h6>
              <p class="text-gray-600 text-sm">{{count($Service)}}</p>
            </div>
            <div class="icon">
              <i class='bx bxs-briefcase-alt-2 text-yellow-500 text-3xl'></i>
            </div>
          </div>
          <div class="product-card bg-white w-44 p-4 rounded-lg border-l-4 border-yellow-500 shadow-md hover:scale-110 transition-transform duration-300 flex justify-between items-center">
          <div class="content flex flex-col items-start">
            <h6 class="text-lg font-medium text-gray-800 mb-1">Revenues</h6>
            @php 
              // Retrieve the total amounts where status is 'done'
              $totals = $payments->where('status', 'done')->pluck('total');

              // Calculate the sum of the totals
              $totalSum = $totals->sum();

              // Format the total sum based on the value
              if ($totalSum >= 10000) {
                  $formattedTotal = number_format($totalSum / 1000, 0) . 'k';
              } else {
                  $formattedTotal = $totalSum;
              }
          @endphp

          <strong class="text-warning text-sm">${{ $formattedTotal }}</strong>

          </div>
          <div class="icon">
            <i class='bx bxs-archive-in text-yellow-500 text-3xl transition-transform duration-400 hover:scale-110'></i>
          </div>
        </div>
          <div class=" product-card bg-white w-44 p-4 rounded-lg border-l-4 border-yellow-500 shadow-md hover:scale-110 transition-all duration-300 flex justify-between items-center">
            <div class="content flex flex-col items-start">
              <h6 class="text-lg font-medium text-gray-800 mb-1">Users</h6>
              <p class="text-gray-600 text-sm">{{count($users)}}</p>
            </div>
            <div class="icon">
              <i class='bx bx-user text-yellow-500 text-3xl hover:text-4xl transition-all duration-400'></i>
            </div>
          </div>
        </div>
        <div class="data p-2 pt-4 row">
          <div class="left col-md-6">
            <!-- ----------------top service----------------- -->
            <section id="top-service">
              <div class="top-services bg-white p-3 rounded-lg shadow">
                <div class="title d-flex justify-content-between items-center mb-2">
                  <h6 class="text-lg font-medium text-gray-800 mb-1">Top Service</h6>
                </div>
                <div class="top-service-info p-3 flex flex-col space-y-2" style="height: 300px; overflow-y: scroll;">
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
                      $booking_id = $FixingProgress->where('status', 'done')->pluck('booking_id');

                      // Initialize book_service variable
                      $book_service = collect();

                      foreach ($bookings as $booking) {
                          if ($booking->type == 'immediately') {
                              $book_service = $bookin_immediatelies->where('service_id', $service->id);
                          } else {
                              $book_service = $bookin_deadlines->where('service_id', $service->id);
                          }
                          
                          if($book_service->isEmpty()){
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
              @foreach ($topFiveData  as $item)
                @php 
                  $topservice = $Service->where('id', $item['id'])->first();
                @endphp
                  <!-- ------------------------- -->
                  <div class="top-service-card d-flex justify-content-between items-center p-2 rounded-lg shadow-md hover:scale-105 transition-all duration-300">
                    <div class="title relative">
                      <p class="text-gray-800 font-medium text-sm">{{$topservice->name}}</p>
                      <div class="d-flex items-center">
                      <i class='bx bxs-group'></i><span>+{{$item['number']}}</span>
                      </div>
                    </div>
                    <div class="evaluation">
                      <i class='bx bxs-star text-yellow-500 text-xl hover:text-4xl transition-all duration-400'></i>
                      <i class='bx bxs-star text-yellow-500 text-xl hover:text-4xl transition-all duration-400'></i>
                      <i class='bx bxs-star text-yellow-500 text-xl hover:text-4xl transition-all duration-400'></i>
                    </div>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#topServiceDetailModel" 
                    data-service-image="{{ $service->image }}"
                            data-service-title="{{ $service->name }}"
                            data-service-description="{{ $service->description }}"
                            data-service-category="{{ $service->category->name }}"
                            data-service-price="{{ $service->price }}"
                            data-service-stars="3">
                      View more
                    </button>
                  </div>
                  <!-- ---------------------------------- -->
                @endforeach
                @if(count($topFiveData)==0)
                    <div class="feedback mt-12" style="text-align: center;">
                      Nothing Top Service !!
                  </div>
                  @endif
                </div>
              </div>
            </section>
            <!-- ----------------top service end----------------- -->

            <!-- ----------------low service----------------- -->
            <div class="low-services bg-white p-3 rounded-lg shadow mt-4">
              <div class="title">
                <h6 class="text-lg font-medium text-gray-800 mb-1">Low Service</h6>
              </div>
              <div class="low-service-info p-3 flex flex-col space-y-2" style="height: 300px; overflow-y: scroll;">
                @php $mama =0; @endphp
              @foreach ($Service as $service)
              @php
                  $booking_id = $FixingProgress->where('status', 'done')->pluck('booking_id');

                  foreach ($bookings as $booking) {
                      if ($booking->type == 'immediately') {
                          $book_service = $bookin_immediatelies->where('service_id', $service->id);
                      } else {
                          $book_service = $bookin_deadlines->where('service_id', $service->id);
                      }
                  }
              @endphp
              @if($book_service->isEmpty())
               @php $mama++ @endphp
              <!-- ------------------------- -->
                  <div class="low-service-card d-flex justify-content-between items-center p-2 rounded-lg shadow-md hover:scale-105 transition-all duration-300">
                    <div class="title relative">
                      <p class="text-gray-800 font-medium text-sm">{{$service->name}}</p>
                      <p class="text-red-600 text-sm">Nothing booking!</p>
                    </div>
                    <div class="evaluation">
                    <button class="btn btn-outline-warning btn-sm text-center" data-bs-toggle="modal" data-bs-target="#topServiceDetailModel" 
                    data-service-image="{{ $service->image }}"
                            data-service-title="{{ $service->name }}"
                            data-service-description="{{ $service->description }}"
                            data-service-category="{{ $service->category->name }}"
                            data-service-price="{{ $service->price }}"
                            data-service-stars="0">
                        <i class="bx bx-info-circle"></i> view more
                    </button>
                    </div>
                  </div>
                  <!-- ---------------------------------- -->
                  @endif
                  @endforeach
                  @if($mama==0)
                    <div class="feedback mt-12" style="text-align: center;">
                      Nothing Low Service !!
                  </div>
                  @endif
              </div>
            </div>
            <!-- ----------------low service end----------------- -->
          </div>
          <div class="right col-md-6 p-3 pt-0 flex flex-col space-y-6">
            <div class="service-performent bg-white p-3 rounded-lg shadow" style="height: 270px;">
              <div class="title d-flex align-items-center justify-content-between">
                <h6 class="text-lg font-medium text-gray-800 mb-0">Services performent</h6>
                <div class="date-input">
                  <input type="date" class="form-control" placeholder="Select a date">
                  <i class="bi bi-calendar3"></i>
                </div>
              </div>
              <canvas id="myChart"></canvas>
            </div>
            <div class="top-than bg-white p-3 rounded-lg shadow " style="height:250px;">
              <div class="title">
                <h6 class="text-lg font-medium text-gray-800">Top Fixer</h6>
              </div>
              <div class="topthan-info d-flex justify-content-center">
              @php 
                $topFixers = \App\Models\FixingProgress::selectRaw('fixer_id, count(*) as count')
                    ->groupBy('fixer_id')
                    ->orderBy('count', 'desc')
                    ->limit(3)
                    ->get();
            @endphp

            @foreach ($topFixers as $top)
                @php 
                    $fixer = $users->where('id', $top->fixer_id)->first();
                @endphp
                <!-- ...................................... -->
                <div class="card-top p-2 shadow-sm mx-2" style="width: 8rem; height: 11rem;">
                    <div class="d-flex justify-content-center">
                        <img src="{{ $fixer->profile }}" class="card rounded-circle" alt="..." style="height: 4rem; width: 4rem; object-fit: cover;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold mb-1" style="font-size:13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $fixer->name }}
                        </h5>
                        <p class="card-text text-muted xs-font mb-2" style="font-size:12px;">99+ stars</p>
                        <div class="evaluation d-flex justify-content-center mb-2">
                            <i class='bx bxs-star text-warning fs-6 me-1'></i>
                            <i class='bx bxs-star text-warning fs-6 me-1'></i>
                            <i class='bx bxs-star text-warning fs-6 me-1'></i>
                        </div>
                        <button class="btn btn-warning btn-sm btn-sm detail-button d-none" data-bs-toggle="modal" data-bs-target="#userDetailsModal"
                            data-user-name="{{$fixer->name}}"
                            data-user-email="{{$fixer->email}}"
                            data-user-phone="{{$fixer->phone}}"
                            data-user-address="{{$fixer->address}}"
                            data-user-profile="{{$fixer->profile}}"
                            data-user-role="{{ $fixer->role }}">
                            <small>View more</small></button>
                    </div>
                </div>
                <!-- ...................................... -->
            @endforeach
            @if ( count ($topFixers) == 0)
                <div class="feedback mt-12" style="text-align: center;">
                  Nothing Top Fixer!!
                </div>
                
                @endif


              </div>

            </div>
            <!-- ----------------Customer feedback----------------- -->
            <div class="customer-feedback bg-white p-3 rounded-lg shadow mt-4">
              <div class="title">
                <h6 class="text-lg font-medium text-gray-800 mb-1">Customer feedback</h6>
              </div>
              <div class="customer-feedback-info p-3 flex flex-col gap-3 space-y-2" style="height: 200px; overflow-y: scroll;">
                <!-- ------------------------- -->
                @if ( count ($feedbacks) == 0)
                <div class="feedback mt-12" style="text-align: center;">
                  Nothing Feedback from Customer
                </div>
                
                @endif
                @foreach ($feedbacks as $feedback)
                @php
                $user = $users->where('id',$feedback->user_id)->first()
                @endphp
                <div class="customer-feedback-card d-flex justify-content-between items-center p-2 rounded-lg h-12 shadow-md hover:scale-105 transition-all duration-300">
                  <img src="{{$user->profile}}" class="card rounded-circle" alt="..." style="height: 2rem; width: 2rem;">
                  <div class="title relative w-50 d-flex flex-col align-item-center pt-2">
                    <h5 class="card-title fw-bold mb-0" style="font-size:13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{$user->name}}</h5>
                    <p class="text-gray-600 text-sm truncate-text" style="font-size:10px;">
                      {{$feedback->content}}
                    </p>
                  </div>
                  <div class="evaluation">
                    <button class="btn btn-outline-warning btn-sm text-center" data-bs-toggle="modal" data-bs-target="#feedbackDetail" data-service-image="https://i.pinimg.com/564x/ed/75/7f/ed757f7b67b716facd211f1733965417.jpg" data-service-title="Premium Service" data-service-description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ac diam at magna tempus volutpat." data-service-stars="0" data-feedback-image="{{ $user->profile }}" data-feedback-name="{{ $user->name }}" data-feedback-content="{{ $feedback->content }}"><i class="bx bx-love"></i>view more</button>
                    @can('Feedback delete')
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $feedback->id }})">Delete</button>

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
                    <form id="delete-form-{{ $feedback->id }}" action="{{ route('admin.feedbacks.destroy', $feedback->id) }}" method="POST" class="d-none">
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
            <!-- ---------------- customer feedback end----------------- -->
          </div>
          <div class="data -pr-3 mt-4">
            <iframe class="map bg-white  rounded-lg shadow" style='height:250px;' src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d31273.788411035468!2d104.88050064999999!3d11.5358151!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2skh!4v1719023181034!5m2!1sen!2skh" width="930" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>

    </main>
  </div>
  </div>
<!-- Example CDN includes for SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- ---------------------top service detail------------------ -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- -----------user detail------ -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left:350px">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="userDetailsModalLabel"><i class='bx bxs-user'></i> Top fixer Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <span class="ms-2"><strong>Role:</strong> <span id="user-role"></span></span>
                                </div>
                                <div class="user-details d-flex align-items-center">
                                    <i class='bx bxs-map'></i>
                                    <span class="ms-2"><strong>Address:</strong> <span id="user-address"></span></span>
                                </div>
                                <div class="user-details d-flex align-i4tems-center">
                                    <i class='bx bxs-phone'></i>
                                    <span class="ms-2"><strong>Phone:</strong> <span id="user-phone"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex justify-content-center align-items-center">
                        <div class="user-profile-container" style="height: 230px;width:400px">
                            <img src="" class="img-fluid rounded" style="object-fit: cover;width:100%;height:100%"  alt="Base64 profile" id="user-profile">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="additional-info mt-3">
                    <h5 class="d-flex align-items-center">
                        <i class='bx bx-info-circle'></i>
                        <span class="ms-2">Additional Information</span>
                    </h5>
                    <p id="user-additional-info">This is where additional information about the user can be displayed.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ----------- Top service detail------ -->
<div class="modal fade" id="topServiceDetailModel" tabindex="-1" aria-labelledby="topServiceDetailModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left:350px">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="topServiceDetailModelLabel"> Service Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="service-image-container" style="height: 230px;">
                            <img src="" class="img-fluid rounded" style="object-fit: cover; width:100%; height:100%" alt="Service Image" id="service-image">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="shadow p-2 text-center" style="background-color: #f8f9fa;">
                            <h4 class="text-warning" id="service-title"><i class="bx bxs-star"></i> Premium Service</h4>
                            <p id="service-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ac diam at magna tempus volutpat.</p>
                            <div class="rating mb-3">
                                <p><i class="bx bxs-star"></i> Stars: <span id="service-stars"></span></p>
                            </div>
                            <div class="service-details">
                                <p><i class="bx bxs-category"></i> <strong>Category:</strong> <span id="service-category"></span></p>
                                <p><i class="bx bxs-dollar-circle"></i> <strong>Price:</strong> <span id="service-price"></span>$</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="additional-info mt-3">
                    <h5><i class="bx bx-info-circle"></i> Additional Information</h5>
                    <p id="service-additional-info">This is where additional information about the service can be displayed.</p>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- ------------end Top service detail ------------------------ -->
<!-- ----------- low service detail------ -->
<div class="modal fade" id="lowServiceDetailModel" tabindex="-1" aria-labelledby="lowServiceDetailModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left:350px">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="lowServiceDetailModelLabel">Low Service Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="service-image-container" style="height: 230px;">
                            <img src="" class="img-fluid rounded" style="object-fit: cover; width:100%; height:100%" alt="Service Image" id="service-image">
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
                                <p><i class="bx bxs-category"></i> <strong>Category:</strong> <span id="service-category"></span></p>
                                <p><i class="bx bxs-dollar-circle"></i> <strong>Price:</strong> <span id="service-price"></span>$</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="additional-info mt-3">
                    <h5><i class="bx bx-info-circle"></i> Additional Information</h5>
                    <p id="service-additional-info">This is where additional information about the service can be displayed.</p>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- ------------end low service detail ------------------------ -->

  <!-- ---------------- feedback-------------------------------- -->
  <div class="modal fade" id="feedbackDetail" tabindex="-1" aria-labelledby="feedbackDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom-width">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="topfeedbackDetailLabel">Feedback detail</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <img src="" class="img-fluid rounded" alt="feedback Image" id="feedback-image">
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
    // ......................graph building....................
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Users', 'Services', 'Fixers', 'Customers', 'Categories'],
        datasets: [{
          label: '# of perform',
          data: [ @php echo count($users) @endphp,@php echo count($Service) @endphp, @php echo count($users->where('role','fixer')) @endphp, @php echo count($users->where('role','customer')) @endphp, @php echo count($Categories) @endphp, 3],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
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


    document.getElementById('userDetailsModal').addEventListener('show.bs.modal', function (event) {
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
  @if(session('showAlertDelete'))
<script>
    Swal.fire({
        title: "User's eedback deleted Success!",
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif
  <style>
    .card-top:hover .detail-button {
      display: inline-block !important;
    }

    .card-top {
      width: 8rem;
      height: 11rem;
      transition: all 0.3s ease-in-out;
    }

    .card-top:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .product-card {
      transition: all 0.3s ease-in-out;
    }

    .product-card:hover {
      transform: scale(1.1);
      background-color: #f5f5f5;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .product-card .icon i {
      transition: all 0.1s ease-in-out;
    }

    .product-card:hover .icon i {
      font-size: 2rem;
    }

    /* Custom CSS for modal dialog width */
    .modal-custom-width {
      max-width: 600px;
    }

    .truncate-text {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
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