<x-app-layout>
    <div style="margin-top:60px"> 
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto -mt-6 px-6 py-4">
                <div class="text-right">
                    <div class="bg-white shadow-lg rounded-lg p-6 my-6">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-600 border-b border-gray-200 w-2/12">Role Name</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-600 border-b border-gray-200">Permissions</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-600 border-b border-gray-200 text-right w-2/12">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @can('Role access')
                                    @foreach($roles as $role)
                                        <tr class="hover:bg-gray-100 transition-colors duration-200">
                                            <td class="py-4 px-6 border-b border-gray-200">{{ $role->name }}</td>
                                            <td class="py-4 px-6 border-b border-gray-200">
                                                @foreach($role->permissions as $permission)
                                                    <span class="inline-flex items-center justify-center px-3 py-1 mr-2 text-xs font-semibold text-white bg-blue-500 rounded-full">{{ $permission->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="py-4 border-b border-gray-200 text-right pl-2">
                                                @can('Role edit')
                                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm d-flex align-items-center justify-content-center">
                                                        <i class="bx bx-cog me-2"></i>
                                                        Edit
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endcan
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('showAlertEdit'))
<script>
    Swal.fire({
        title: 'Role edited Success!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800',
        showCloseButton: true,
    });
</script>
@endif
</x-app-layout>
