<x-app-layout>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    
<div class="container" style="margin-top:120px">
    <div class="d-flex justify-content-between -mt-5 mb-3">
        <div class=" mb-2 shadow">
            @can('User create')
                <a href="{{ route('admin.users.create') }}" class="btn btn-warning d-flex align-items-center">
                    <i class='bx bxs-plus-circle' style='font-size:30px'></i>
                    Create New
                </a>
            @endcan
        </div>
        <h4 class='shadow text-center p-2 border around-50'><i class='bx bx-user'></i> USER MANAGEMENT</h4>        <div class="w-60 bg-white rounded d-flex align-items-center shadow" style="height: 40px;">
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
                    <th scope="col" class='text-center'>User Name</th>
                    <th scope="col" class='text-center'>Email</th>
                    <th scope="col" class='text-center'>Role</th>
                    <th scope="col" class='text-center'>Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <!-- Example row, replace with dynamic content -->
                @can('User access')
                @foreach($users as $user)
                <tr>
                    <td class='text-center'>{{ $user->name }}</td>
                    <td class='text-center'>{{ $user->email }}</td>
                    <td class='text-center'>
                        <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-gray-500 rounded-full">{{ $user->role }}</span>
                    </td>
                    <td class="table-actions d-flex justify-content-around text-center">
                        <a class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#userDetailsModal"
                            data-user-name="{{$user->name}}"
                            data-user-email="{{$user->email}}"
                            data-user-phone="{{$user->phone}}"
                            data-user-address="{{$user->address}}"
                            data-user-profile="{{$user->profile}}"
                            data-user-role="{{ $user->role }}"
                            >
                            Details
                        </a>
                        @can('User edit')
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @endcan
                        @can('User delete')
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">Delete</button>

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

                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
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
    @can('User access', $users)
    <div class="text-right mt-6">
        {{ $users->links() }}
    </div>
@endcan
</div>

<!-- -----------user detail------ -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left:350px">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="userDetailsModalLabel"><i class='bx bxs-user'></i> User Details</h5>
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('showAlertCreate'))
<script>
    Swal.fire({
        title: 'User created successfully!',
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
        title: 'User edited successfully!',
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
        title: 'User deleted successfully!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif
<style>
.user-profile-container {
        overflow: hidden;
        border-radius: 10px;
    }

    .user-profile-container img {
        transition: transform 0.3s ease;
    }

    .user-profile-container img:hover {
        transform: scale(1.1);
    }

.user-details-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.user-details-header {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.user-details-header i {
    font-size: 24px;
    color: #ffc107;
    margin-right: 10px;
}

.user-details-body p,
.user-details {
    margin-bottom: 10px;
}

.user-details-body i {
    font-size: 18px;
    color: #6c757d;
    margin-right: 8px;
}

.user-profile-card {
    position: relative;
    overflow: hidden;
    border-radius: 50%;
    max-width: 200px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.user-profile-card img {
    width: 100%;
    height: auto;
}

.user-profile-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 193, 7, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.user-profile-overlay i {
    font-size: 3rem;
    color: #fff;
}

.user-profile-card:hover .user-profile-overlay {
    opacity: 1;
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

    // Handle live search
    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#user-table-body tr');

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const email = row.children[1].textContent.toLowerCase();
            const role = row.children[2].textContent.toLowerCase();

            if (name.includes(query) || email.includes(query) || role.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</x-app-layout>
