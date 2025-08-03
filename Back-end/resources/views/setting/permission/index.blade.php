<x-app-layout>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">       
<div class="container" style="margin-top:120px">
    <div class="d-flex justify-content-between -mt-5 mb-3">
        <div class=" mb-2 shadow">
            @can('Permission create')
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-warning d-flex align-items-center">
                    <i class='bx bxs-plus-circle' style='font-size:30px'></i>
                    Create New
                </a>
            @endcan
        </div>
            <h4 class='shadow text-center p-2 border around-50'><i class='bx bx-lock-alt'></i>PERMISSION MANAGEMENT</h4>
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
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="bg-warning">
                <tr>
                    <th scope="col" class='text-center'>Permission Name</th>
                    <th scope="col" class='text-center'>Actions</th>
                </tr>
            </thead>
            <tbody id="permission-table-body">
                <!-- Example row, replace with dynamic content -->
                @can('Permission access')
                @foreach ($permissions as $permission)
                <tr>
                    <td class='text-center'>{{ $permission->name }}</td>
                    <td class="table-actions d-flex justify-content-center gap-3 text-center">
                        @can('Permission edit')
                            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @endcan
                        @can('Permission delete')
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $permission->id }})">Delete</button>

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

                        <form id="delete-form-{{ $permission->id }}" action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-none">
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
    @can('Permission access')
    <div class="text-right mt-6">
        {{ $permissions->links() }}
    </div>
    @endcan
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('showAlertCreate'))
<script>
    Swal.fire({
        title: 'Permission created Success!',
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
        title: 'Permission edited Success!',
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
        title: 'Permission deleted Success!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif


<!-- Include Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    // Handle live search
    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#permission-table-body tr');

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const description = row.children[1].textContent.toLowerCase();

            if (name.includes(query) || description.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</x-app-layout>
