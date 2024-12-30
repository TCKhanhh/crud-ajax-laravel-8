@extends('User.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8">
                <h2>Danh sách người dùng</h2>
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm người dùng...">
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach ($users as $key => $user)
                            <tr id="user-row-{{ $user->id }}">
                                <td class="user-index">{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role == 0 ? 'Admin' : 'Người dùng' }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#viewUserModal{{ $user->id }}">Chi tiết</button>

                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal{{ $user->id }}">Sửa</button>

                                    <button type="button" class="btn btn-danger btn-sm delete-user"
                                        data-id="{{ $user->id }}">Xóa</button>
                                </td>
                            </tr>

                            <!-- Modal xem chi tiết người dùng -->
                            <div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="viewUserModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewUserModalLabel{{ $user->id }}">Chi tiết
                                                người dùng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tên: {{ $user->name }}</label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Email: {{ $user->email }}</label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Vai trò:
                                                    {{ $user->role == 0 ? 'Admin' : 'Người dùng' }}</label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Ngày tạo:
                                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y H:i:s') }}</label>

                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Cập nhật lần cuối:
                                                    {{ \Carbon\Carbon::parse($user->updated_at)->format('d-m-Y H:i:s') }}</label>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal chỉnh sửa người dùng -->
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Chỉnh sửa
                                                người dùng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form class="editUserForm" data-id="{{ $user->id }}">
                                            <div class="modal-body">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Tên</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Vai trò</label>
                                                    <select class="form-select" name="role" required>
                                                        <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>
                                                            Người dùng</option>
                                                        <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>
                                                            Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>

            <div class="col-4">
                <h2>Thêm người dùng mới</h2>
                <form id="addUserForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="1">Người dùng</option>
                            <option value="0">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Thiết lập CSRF token cho tất cả các yêu cầu AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //---------------------------------------------------

        // Xử lý xem chi tiết người dùng
        $('.view-user').on('click', function() {
            let userId = $(this).data('id');

            $.ajax({
                url: `/viewUser/${userId}`,
                method: 'GET',
                success: function(response) {
                    $('#viewUserModal-' + userId).modal('show');
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi tải thông tin người dùng');
                }
            });
        });

        // Xử lý thêm người dùng
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('storeUser') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = '';

                    if (xhr.status === 422) {
                        for (var field in errors) {
                            errorMessages += errors[field].join(', ') + '\n';
                        }
                        alert(errorMessages);
                    } else {
                        alert('Có lỗi xảy ra khi thêm người dùng');
                    }
                }
            });
        });

        // Xử lý chỉnh sửa người dùng
        $('.editUserForm').on('submit', function(e) {
            e.preventDefault();

            let userId = $(this).data('id');
            let formData = $(this).serialize();

            $.ajax({

                url: `/updateUser/${userId}`,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi chỉnh sửa người dùng');
                }
            });
        });

        // Xử lý xóa người dùng
        $('.delete-user').on('click', function() {
            if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
                let userId = $(this).data('id');
                let row = $(`#user-row-${userId}`);

                $.ajax({
                    url: `/deleteUser/${userId}`,
                    method: 'POST',
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                            // Cập nhật lại STT
                            let startIndex = ($('.pagination .active').text() - 1) *
                                {{ $users->perPage() }} + 1;
                            $('.user-index').each(function(index) {
                                $(this).text(startIndex + index);
                            });
                        }
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra khi xóa người dùng');
                    }
                });
            }
        });
        //---------------------------------------------------
    </script>
@endsection
