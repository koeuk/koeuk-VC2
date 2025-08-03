<x-app-layout fade>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <div class="container" style="margin-top:110px">
        <div class="d-flex justify-content-between -mt-5 mb-3">
            <div class=" mb-2 shadow">
                @can('Discount create')
                    <a href="{{ route('admin.discounts.create') }}" class="btn btn-warning d-flex align-items-center">
                        <i class='bx bxs-plus-circle' style='font-size:30px'></i>
                        Create New
                    </a>
                @endcan
            </div>
            <h4 class='shadow text-center p-2 border around-50'><i class='bx bx-tag'></i> DISCOUNT MANAGEMENT</h4>
            <div class="w-60 bg-white rounded d-flex align-items-center shadow" style="height: 40px;">
                <div class="input-group h-100">
                    <input type="text" id="search-input" class="form-control border-0 shadow-none h-100"
                        placeholder="Search" aria-label="Search">
                    <div class="input-group-append h-100">
                        <div class="btn bg-warning pt-2 h-100">
                            <i class='bx bx-search-alt'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-warning">
                    <tr>
                        <th scope="col" class='text-center'>#</th>
                        <th scope="col" class='text-center'>Discount</th>
                        <th scope="col" class='text-center'>Description</th>
                        {{-- <th scope="col" class='text-center'>Fixer</th> --}}
                        <th scrope='col' class='text-center'>Start Date</th>
                        <th scrope='col' class='text-center'>End Date</th>
                        <th scope="col" class='text-center'>Actions</th>
                    </tr>
                </thead>
                <tbody id="discount-table-body">
                    <!-- Example row, replace with dynamic content -->
                    @can('Discount access')
                        @php $h=1 @endphp
                        @foreach ($discounts as $discount)
                            <tr>
                                <td class='text-center'>{{ $h++ }}</td>
                                <td class='text-center'>{{ $discount->discount }}%</td>
                                <td class='text-center'
                                    style="width:5px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 150px;">
                                    {{ strlen($discount->description) > 15 ? substr($discount->description, 0, 15) . '...' : $discount->description }}
                                </td>
                                <td class='text-center'>{{ $discount->start_date }}</td>
                                <td class='text-center'>{{ $discount->end_date }}</td>
                                <td class="table-actions d-flex justify-content-around text-center">
                                    <a class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#discountDetailsModal"
                                        data-discount-title="{{ $discount->discount }}%"
                                        data-discount-description="{{ $discount->description }}" {{-- data-service-category="{{ $discount->category->name }}" --}}
                                        data-discount-start_date="{{ $discount->start_date }}"
                                        data-discount-end_date="{{ $discount->end_date }}" data-discount-stars="999">
                                        Details
                                    </a>
                                    @can('Discount edit')
                                        <a href="{{ route('admin.discounts.edit', $discount->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('Discount delete')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $discount->id }})">Delete</button>

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
                                                        // Perform the delete action
                                                        document.getElementById(`delete-form-${id}`).submit();
                                                    }
                                                });
                                            }
                                        </script>

                                        <form id="delete-form-{{ $discount->id }}"
                                            action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endcan
                    <!-- Add more rows here -->
                </tbody>
            </table>
        </div>
        @can('Discount access')
            <div class="text-right mt-6">
                {{ $discounts->links() }}
            </div>
        @endcan
    </div>

    <!-- -----------discount detail------ -->
    <div class="modal fade" id="discountDetailsModal" tabindex="-1" aria-labelledby="discountDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left:350px">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="discountDetailsModalLabel">Discount Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-warning" id="discount-title"><i class='bx bxs-star'></i> Premium Service
                            </h4>
                            <p id="discount-description">Get access to our premium service with exclusive features and
                                benefits. But hurry, this offer is only available for a limited time!</p>
                            <div class="rating mb-3">
                                <p><i class='bx bxs-star'></i> Rating: <span id="discount-stars">4.8</span> (based on
                                    1,234 reviews)</p>
                            </div>
                            <div class="discount-details">
                                <p><i class='bx bxs-calendar'></i> <strong>Start Date:</strong> <span
                                        id="discount-start_date">2023-06-01</span></p>
                                <p><i class='bx bxs-calendar'></i> <strong>End Date:</strong> <span
                                        id="discount-end_date">2023-12-31</span></p>
                                <p><i class='bx bxs-dollar-circle'></i> <strong>Discount:</strong> <span
                                        id="discount-percentage">25%</span> off regular price</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="additional-info mt-3">
                        <h5><i class='bx bx-info-circle'></i> Additional Information</h5>
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
    @if (session('showAlertCreate'))
        <script>
            Swal.fire({
                title: 'Discount created Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff9800',
                showCloseButton: true,
            });
        </script>
    @endif
    @if (session('showAlertEdit'))
        <script>
            Swal.fire({
                title: 'Discount edited Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff9800',
                showCloseButton: true,
            });
        </script>
    @endif
    @if (session('showAlertDelete'))
        <script>
            Swal.fire({
                title: 'Discount deleted Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff9800',
                showCloseButton: true,
            });
        </script>
    @endif
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

        .discount-image-container {
            overflow: hidden;
            border-radius: 10px;
        }

        .discount-image-container img {
            transition: transform 0.3s ease;
        }

        .discount-image-container img:hover {
            transform: scale(1.1);
        }

        .discount-details p,
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
    <script>
        document.getElementById('discountDetailsModal').addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            var button = event.relatedTarget;

            // Update the modal content with the data attributes
            document.getElementById('discount-title').textContent = button.dataset.discountTitle;
            document.getElementById('discount-description').textContent = button.dataset.discountDescription;
            document.getElementById('discount-stars').textContent = button.dataset.discountStars;
            document.getElementById('discount-start_date').textContent = button.dataset.discountStart_date;
            document.getElementById('discount-end_date').textContent = button.dataset.discountEnd_date;
            document.getElementById('discount-additional-info').textContent = button.dataset.discountAdditionalInfo;
        });

        // Handle live search
        document.getElementById('search-input').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#discount-table-body tr');

            rows.forEach(row => {
                const discount = row.children[0].textContent.toLowerCase();
                const description = row.children[1].textContent.toLowerCase();
                const start_date = row.children[2].textContent.toLowerCase();
                const end_date = row.children[3].textContent.toLowerCase();

                if (discount.includes(query) || description.includes(query) || start_date.includes(query) ||
                    end_date.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

</x-app-layout>
