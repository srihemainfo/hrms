@extends('layouts.admin')
@section('content')
    <div class="row gutters">
        <div class="col-3"></div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Block User
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.block_user') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group required">
                            <label class="required" for="role_type">Role Type</label>
                            <select id="role_types" class="form-control select2" name="role_type" required
                                onchange="get_roles(this)">
                                <option value="">Select Role Type</option>
                                @foreach ($role_type as $id => $entry)
                                    <option value="{{ $id }}">{{ $entry }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group required">
                            <label class="required" for="role">Role</label>
                            <select id="roles" class="form-control select2" name="role" required
                                onchange="get_users(this)">
                                <option value="">Select Role</option>
                            </select>
                        </div>
                        <div class="form-group required">
                            <label class="required" for="users">Users</label>
                            <select id="users" class="form-control select2" name="user" required>
                                <option value="">Select User</option>

                            </select>
                        </div>
                        <div class="form-group required">
                            <label class="required" for="block_reason">Block Reason</label>
                            <input type="text" class="form-control" id="block_reason" name="block_reason" required>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-danger" type="submit">
                                Block
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-3"></div>
    </div>
@endsection
@section('scripts')
    <script>
        // var role = 1;

        function get_roles(element) {
            let select = $('#roles')
            select.empty()
            select.html(`<option>Loading...</option>`)
            let role_type = $(element).val()
            if (role_type != '') {
                $.ajax({
                    url: "{{ route('admin.users.fetch_roles') }}",
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'role_type': role_type
                    },
                    success: function(respond) {
                        let roles = respond.roles;
                        let select = $('#roles')
                        select.empty()
                        if (roles.length > 0) {
                            select.append(`<option value="">Select Role</option>`)
                            $.each(roles, function(index, d) {
                                select.append(`<option value="${d.id}">${d.title}</option>`)
                            })
                        } else {
                            select.html(`<option value="">Roles Not Found</option>`)
                        }
                    }
                })
            }
        }

        function get_users(element) {
            let select = $('#users')
            select.empty()
            select.html(`<option>Loading...</option>`)
            let role_user = $(element).val()
            if (role_user != '') {
                $.ajax({
                    url: "{{ route('admin.users.fetch_users') }}",
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'role_user': role_user
                    },
                    success: function(respond) {
                        let select = $('#users')
                            select.empty()
                        if (respond[0] != '') {
                            select.append(`<option value="">Select User</option>`)
                            $.each(respond, function(index, data) {
                                $.each(data, function(index, d) {
                                    select.append(`<option value="${d.id}">${d.name}</option>`)
                                })
                            })
                        } else {
                            select.html(`<option value="">User Not Found</option>`)
                        }
                    }
                })
            }
        }
    </script>
@endsection
