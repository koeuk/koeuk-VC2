<x-app-layout>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    
<div class="container" style="margin-top:120px">
    <div class="d-flex justify-content-between -mt-5 mb-3">
        <div class=" mb-2 shadow">
            @can('Service create')
                <a href="{{ route('admin.services.create') }}" class="btn btn-warning d-flex align-items-center">
                    <i class='bx bxs-plus-circle' style='font-size:30px'></i>
                    Create New
                </a>
            @endcan
        </div>
            <h4 class='shadow text-center p-2 border around-50'><i class='bx bx-list-check'></i> SERVICE MANAGEMENT</h4>        <div class="w-60 bg-white rounded d-flex align-items-center shadow" style="height: 40px;">
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
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="bg-warning">
                <tr>
                    <th scope="col" class='text-center'>Service Name</th>
                    <th scope="col" class='text-center'>Description</th>
                    <th scrope='col' class='text-center'>Price</th>
                    <th scrope='col' class='text-center'>Category</th>
                    <th scope="col" class='text-center'>Actions</th>
                </tr>
            </thead>
            <tbody id="service-table-body">
                @can('Service access')
                    @foreach ($services as $service)
                        <tr>
                            <td class='text-center'>{{ $service->name }}</td>
                            <td class='text-center' style="width:5px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 150px;">
                                {{ strlen($service->description) > 15 ? substr($service->description, 0, 15) . '...' : $service->description }}
                            </td>
                            <td class='text-center'>{{ $service->price }}$</td>
                            <td class='text-center'>{{ $service->category->name }}</td>
                            <td class="table-actions d-flex justify-content-around text-center">
                                <a class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#serviceDetailsModal" 
                                    data-service-image="{{ $service->image }}"
                                    data-service-title="{{ $service->name }}"
                                    data-service-description="{{ $service->description }}"
                                    data-service-category="{{ $service->category->name }}"
                                    data-service-price="{{ $service->price }}"
                                    data-service-stars="999">
                                    Details
                                </a>
                                @can('Service edit')
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endcan
                                @can('Service delete')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $service->id }})">Delete</button>
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
                                    <form id="delete-form-{{ $service->id }}" action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('delete')
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @endcan
            </tbody>
            
    </div>
    @can('Service access')
    <div class="text-right mt-6">
        {{ $services->links() }}
    </div>
    @endcan
</div>

<!-- -----------service detail------ -->
<div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-labelledby="serviceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left:350px">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="serviceDetailsModalLabel">Service Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="service-image-container" style="height: 230px;">
                            <img src="" class="img-fluid rounded" style="object-fit: cover;width:100%;height:100%"  alt="Base64 Image" id="service-image">
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
@if(session('showAlertCreate'))
<script>
    Swal.fire({
        title: 'Service created Success!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif
@if(session('showAlertEdit'))
<script>
    Swal.fire({
        title: 'Service edited Success!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif
@if(session('showAlertDelete'))
<script>
    Swal.fire({
        title: 'Service deleted Success!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif
<script>
    function updateUser(userId) {
    // var name = $('#username').val();
    // var phone = $('#phone').val();

    var userData = {
      name: name,
      phone: phone,
      _token: '{{ csrf_token() }}'
    };

    $.ajax({
      url: '/admin/update/' + userId,
      type: 'PUT',
      data: userData,
      success: function(response) {
        $('#exampleModal').modal('hide');
        window.location.reload();
      },
      error: function(xhr) {
        console.error('Error updating information:', xhr.responseText);
      }
    });
  }
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
<script>

    document.getElementById('serviceDetailsModal').addEventListener('show.bs.modal', function (event) {
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
    });

    // Handle live search
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
</script>
</x-app-layout>
