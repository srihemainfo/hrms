@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', [$user->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $user->name) }}" required>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="email" value="{{ old('email', $user->email) }}" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="password">Change Password</label>
                    <input class="form-control" type="password" value="" name="password" id="password">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="required" for="roles">Role Type</label>
                    <select class="form-control select2" name="role_type" id="role_type" required disabled>
                        @foreach ($role_type as $id => $type)
                            <option value="{{ $id }}" {{ $user->roles[0]->type_id == $id ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="required" for="roles">Roles</label>
                    <select class="form-control select2" name="roles" id="roles" required disabled>
                        <option value="">Select Role</option>
                        @foreach ($roles as $id => $role)
                            <option value="{{ $id }}" {{ $user->roles[0]->id == $id ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $('#role_type').change(function() {
            let type = $(this).val()
            let role = $('#roles')
            role.empty().html(`<option value="">Loading...</option>`)
            if (type != '') {
                $.ajax({
                    url: "{{ route('admin.users.fetch_role') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'type': type
                    },
                    success: function(data) {
                        if (data != '' || data != null) {
                            let role = $('#roles')
                            role.empty()
                            role.append(`<option value="">Select Role</option>`)
                            console.log(data)
                            $.each(data, function(index, d) {
                                role.append(`<option value="${index}">${d}</option>`)
                            })

                        }
                    }
                });
            }
        })
    </script>
@endsection
