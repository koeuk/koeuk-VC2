<x-app-layout>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<div class="container" style="margin-top:120px">
    <div class="d-flex justify-content-between -mt-5 mb-3">
        <div class="mb-2 shadow">
            @can('Payment create')
                <a href="{{ route('admin.payments.create') }}" class="btn btn-warning d-flex align-items-center">
                    <i class='bx bxs-plus-circle' style='font-size:30px'></i>
                    Create Payment
                </a>
            @endcan
        </div>
        <h4 class='shadow text-center p-2 border rounded-50'><i class='bx bx-list-check'></i> PAYMENT MANAGEMENT</h4>
        <div class="w-60 bg-white rounded d-flex align-items-center shadow" style="height: 40px;">
            <div class="input-group h-100">
                <input type="text" id="search-input" class="form-control border-0 shadow-none h-100" placeholder="Search" aria-label="Search">
                <div class="input-group-append h-100">
                    <div class="btn bg-warning pt-2 h-100">
                        <i class='bx bx-search-alt'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3 d-flex gap-6">
        <div class="dropdown">
            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Select date
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="height: 300px; overflow-y: scroll;">
            @can('Payment access')
                @php
                    $uniqueDates = $payments->pluck('datepay')->unique();
                @endphp
                @foreach ($uniqueDates as $date)
                    <li><a class="dropdown-item" href="#" data-value="{{ \Carbon\Carbon::parse($date)->format('Y-M') }}">{{ \Carbon\Carbon::parse($date)->format('Y-M') }}</a></li>
                @endforeach
            </ul>
        </div>
        <?php
        function formatNumber($number) {
            if ($number >= 1000) {
                return number_format($number / 1000, 1) . 'k';
            }
            return $number;
        }

        $successCount = formatNumber(count($payments->where('status','done')));
        $incompleteCount = formatNumber(count($payments->where('status','no')));
        $allCount = formatNumber(count($payments));
        ?>

        <!-- HTML -->
        <button id="btn-success" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Succeeded <span>{{ $successCount }}</span>
        </button>
        <button id="btn-incomplete" class="btn btn-warning">
            <i class="bi bi-exclamation-circle"></i> Incomplete <span>{{ $incompleteCount }}</span>
        </button>
        <button id="btn-all" class="btn btn-secondary">
            <i class="bi bi-list"></i> All <span>{{ $allCount }}</span>
        </button>

    </div>
    @endcan

    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
            <thead class="bg-warning">
                <tr>
                    <th scope="col" class='text-center'>Customer Name</th>
                    <th scope="col" class='text-center'>Number fixing</th>
                    <th scope="col" class='text-center'>Amount/1service</th>
                    <th scope="col" class='text-center'>Total</th>
                    <th scope='col' class='text-center'>Date</th>
                    <th scope='col' class='text-center'>Dateline</th>
                    <th scope='col' class='text-center'>Status</th>
                </tr>
            </thead>
            <tbody id="service-table-body">
                @can('Payment access')
                    @foreach ($payments as $payment)
                        @php
                            $customer = $users->where('id', $payment->fixer_id)->first();
                        @endphp
                        <tr>
                            <td class='text-center'>{{$customer->name}}</td>
                            <td class='text-center'>{{$payment->number_fixed}}</td>
                            <td class='text-center'>{{ $payment->amount }}$</td>
                            <td class='text-center text-warning'><strong>{{ $payment->total }}$</strong></td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($payment->datepay)->format('Y-M-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($payment->dateline)->format('Y-M-d') }}</td>
                            <td class='text-center'>
                                @if($payment->status == 'no')
                                <span class='border rounded bg-white text-center d-flex align-items-center justify-content-center gap-2'>Incomplete<i class='bx bx-time-five'></i></span>
                                @else
                                <span class='border rounded bg-success text-white d-flex align-items-center justify-content-center gap-2'>Succeeded<i class='bx bx-check' style='font-size:25px'></i></span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endcan
            </tbody>
        </table>
    </div>
</div>

<!-- -----------service detail------ -->
<div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-labelledby="serviceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="serviceDetailsModalLabel">Service Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="service-image-container" style="height: 230px;">
                            <img src="" class="img-fluid rounded" style="object-fit: cover;width:100%;height:100%" alt="Base64 Image" id="service-image">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="shadow p-2 text-center" style='background-color: #f8f9fa;'>
                            <h4 class="text-warning" id="service-title"><i class='bx bxs-star'></i> Premium Service</h4>
                            <p id="service-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ac diam at magna tempus volutpat.</p>
                            <div class="rating mb-3">
                                <p><i class='bx bxs-star'></i> Stars: <span id="service-stars"></span></p>
                            </div>
                            <div class="service-details">
                                <p><i class='bx bxs-category'></i> <strong>Category:</strong> <span id="service-category"></span></p>
                                <p><i class='bx bxs-dollar-circle'></i> <strong>Price:</strong> <span id="service-price"></span>$</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="additional-info mt-3">
                    <h5><i class='bx bx-info-circle'></i> Additional Information</h5>
                    <p id="service-additional-info">This is where additional information about the service can be displayed.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@if(session('showAlertCreate'))
<script>
    Swal.fire({
        title: 'You already created a new payment successfully!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif

@if(session('showAlertNo'))
<script>
    Swal.fire({
        title: 'Nothing fixing for this month!!',
        text: '{{ session("fail") }}',
        icon: 'fail',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle dropdown selection
        $('.dropdown-item').click(function() {
            var selectedValue = $(this).data('value');
            $('#dropdownMenuButton').text(selectedValue);
            filterTable(selectedValue);
        });

        function filterTable(selectedValue) {
            const rows = document.querySelectorAll('#service-table-body tr');
            rows.forEach(row => {
                const dateCell = row.children[4]; // Adjust the index based on your table structure
                const cellDate = dateCell.textContent.trim();
                if (cellDate.includes(selectedValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function filterTableByStatus(status) {
            const rows = document.querySelectorAll('#service-table-body tr');
            rows.forEach(row => {
                const statusCell = row.children[6]; // Adjust the index based on your table structure
                const cellStatus = statusCell.textContent.trim();
                if (status === 'all' || cellStatus.includes(status)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('btn-success').addEventListener('click', function () {
            filterTableByStatus('Succeeded');
        });

        document.getElementById('btn-incomplete').addEventListener('click', function () {
            filterTableByStatus('Incomplete');
        });

        document.getElementById('btn-all').addEventListener('click', function () {
            filterTableByStatus('all');
        });

        document.getElementById('serviceDetailsModal').addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('service-image').src = button.dataset.serviceImage;
            document.getElementById('service-title').textContent = button.dataset.serviceTitle;
            document.getElementById('service-description').textContent = button.dataset.serviceDescription;
            document.getElementById('service-stars').textContent = button.dataset.serviceStars;
            document.getElementById('service-category').textContent = button.dataset.serviceCategory;
            document.getElementById('service-price').textContent = button.dataset.servicePrice;
            document.getElementById('service-additional-info').textContent = button.dataset.serviceAdditionalInfo;
        });

        document.getElementById('search-input').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#service-table-body tr');
            rows.forEach(row => {
                const name = row.children[0].textContent.toLowerCase();
                const description = row.children[1].textContent.toLowerCase();
                const price = row.children[2].textContent.toLowerCase();
                const category = row.children[3].textContent.toLowerCase();
                if (name.includes(query) || description.includes(query) || price.includes(query) || category.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

<style>
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
