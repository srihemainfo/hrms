@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        #loading {
            z-index: 999;
        }
    </style>
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
        </div>
        <div class="card-body" id="one">
            <div class="row gutters">

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                    <label class="required" for="roles">Role Type</label>
                    <select id="role_type" class="form-control select2" name="role_type" required>
                        <option value="">Select Role Type</option>
                        @foreach ($RoleTypes as $i => $type)
                            <option value="{{ $i }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <span id="role_type_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                    <select id="myDropdown" class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}"
                        name="roles" required>
                        <option value="">Select Role</option>
                    </select>
                    <span id="role_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                    <label for="name" class="required">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <span id="name_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group">
                    <label for="email" class="required">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <span id="email_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="worktype_div">
                    <label for="worktype" class="required">Work Type</label>
                    <select name="worktype" id="worktype" class="form-control select2">
                        <option value="">Select Worktype</option>
                        @foreach ($worktypes as $id => $worktype)
                            <option value="{{ $id }}">{{ $worktype }}</option>
                        @endforeach
                    </select>
                    <span id="worktype_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="designation_div">
                    <label for="designation" class="required">Designation</label>
                    <select name="designation" id="designation" class="form-control select2">
                        <option value="">Select Designation</option>
                        @foreach ($designations as $id => $designation)
                            <option value="{{ $id }}">{{ $designation }}</option>
                        @endforeach
                    </select>
                    <span id="designation_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>



                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="phone_number_div">
                    <label for="phone_number" class="required">Phone Number</label>
                    <input type="number" class="form-control" id="phone_number" name="phone_number">
                    <span id="phone_number_span" class="text-danger text-center"
                        style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="password_div" style="display: none">
                    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                        name="password" id="password">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                    {{-- <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span> --}}
                    <span id="password_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="gender_div">
                    <label for="gender" class="required">Gender</label>
                    <select name="gender" id="gender" class="form-control select2">
                        <option value="">Select Gender</option>
                        <option value="MALE">Male</option>
                        <option value="FEMALE">Female</option>
                    </select>
                    <span id="gender_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 form-group" id="days_div" style="display: none;">
                    <label for="days" class="required">Select Days</label>
                    <select name="days" id="days" class="form-control select2" multiple>
                        <option value="">Select Days</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                    </select>
                    <span id="days_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

            </div>
            <div class="form-group">
                <button class="btn btn-danger" id="btnsave">
                    {{ trans('global.save') }}
                </button>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $("#btnsave").click(function() {

            let role_type = $("#role_type").val();
            let password = $("#password").val();
            let designation = $("#designation").val();
            let phone_number = $("#phone_number").val();
            let gender = $("#gender").val();
            let days = $("#days").val();
            let id = $("#user_id").val();
            let worktype = $("#worktype").val();
            let name = $("#name").val();
            let role = $("#myDropdown").val();
            let daysJson = JSON.stringify(days);

            if (phone_number == '') {
                phone_number = password;
            }

            if (role == '') {
                $("#role_span").html('Please select role');
                $("#role_span").show();
                $("#role").focus();
                return false;
            } else {
                $("#role_span").hide();
            }

            if (name == '') {
                $("#name").focus();
                $("#name_span").html('Please enter your name');
                $("#name_span").show();
                return false;
            } else {
                $("#name_span").hide();
            }

            let email = $("#email").val();
            let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (email == '') {
                $("#email_span").html('Please enter your email');
                $("#email_span").show();
                return false;
            } else if (!emailRegex.test(email)) {
                $("#email_span").html('Please enter a valid email address');
                $("#email_span").show();
                return false;
            } else {
                $("#email_span").hide();
            }

            if (role_type == '') {
                $("#role_type_span").html("Please Choose Role Type");
                $("#role_type_span").show();
                $("#role_type").focus();
                return false;
            } else if (role_type == '2') {

                if (worktype == '') {
                    $("#worktype_span").html("Please choose work type");
                    $("#worktype_span").show();
                    $("#role_type_span").hide();
                    $("#designation_span").hide();
                    $("#gender_span").hide();
                    $("#phone_number_span").hide();
                    return false;
                } else if (designation == '') {
                    $("#designation_span").html("Please choose designation");
                    $("#designation_span").show();
                    $("#gender_span").hide();
                    $("#worktype_span").hide();
                    $("#role_type_span").hide();
                    $("#phone_number_span").hide();
                    return false;
                } else if (phone_number == '') {
                    $("#phone_number_span").html("Please enter your number");
                    $("#phone_number_span").show();
                    $("#worktype_span").hide();
                    $("#gender_span").hide();
                    $("#role_type_span").hide();
                    $("#designation_span").hide();
                    return false;
                } else if (gender == '') {
                    $("#gender_span").html('Please choose gender');
                    $("#gender_span").show();
                    $("#worktype_span").hide();
                    $("#role_type_span").hide();
                    $("#phone_number_span").hide();
                    $("#designation_span").hide();
                    return false;
                } else {
                    $("#worktype_span").hide();
                    $("#role_type_span").hide();
                    $("#designation_span").hide();
                    $("#gender_span").hide();
                    $("#phone_number_span").hide();
                }
            } else if (role_type == '1') {
                if (password == '') {
                    $("#password_span").html("Please Enter Your Password");
                    $("#password_span").show();
                    return false;
                } else {
                    $("#password_span").hide();
                }
            } else {
                $("#worktype_span").hide();
                $("#role_type_span").hide();
                $("#designation_span").hide();
                $("#phone_number_span").hide();
                $("#gender_span").hide();

            }

            if (worktype == '2') {
                if (days == '') {
                    $("#days_span").html("Please Choose any one Day");
                    $("#days_span").show();
                    return false;
                } else {
                    $("#days_span").hide();
                }
            }


            $("#loading").show();

            $.ajax({
                url: "{{ route('admin.users.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                    'role': role,
                    'role_type': role_type,
                    'name': name,
                    'worktype': worktype,
                    'email': email,
                    'phone_number': phone_number,
                    'gender': gender,
                    'designation': designation,
                    'daysJson': daysJson
                },
                success: function(response) {
                    $("#loading").hide();
                    let status = response.status;
                    if (status == true) {
                        Swal.fire('', response.data, 'success');
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loading").hide();
                    if (jqXHR.status) {
                        if (jqXHR.status == 500) {
                            Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                        } else {
                            Swal.fire('', jqXHR.status, 'error');
                        }
                    } else if (textStatus) {
                        Swal.fire('', textStatus, 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                    }
                }
            });

            return false;
        });

        $('#role_type').change(function() {


            let role_type = $("#role_type").val();
            if (role_type == 3 || role_type == 1) {
                $("#password_div").show();
                $("#phone_number_div").hide();
                $("#designation_div").hide();
                $("#gender_div").hide();
                $("#worktype_div").hide();
            } else {
                $("#password_div").hide();
                $("#phone_number_div").show();
                $("#gender_div").show();
                $("#designation_div").show();
                $("#worktype_div").show();
            }

            $('#namefull').hide();
            $('.main').hide();
            $(".staff").hide();
            $('.phone').hide();
            $(".dept").hide();
            $(".doj").hide();

            $('.studentDiv').hide();
            let type = $(this).val()
            let roles = $("#myDropdown")
            roles.empty()
            roles.html(`<option value="">Loading...</option>`)
            resetinputs()

            $.ajax({
                type: "POST",
                url: "{{ route('admin.users.fetch_role') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'type': type
                },

                success: function(data) {
                    let roles = $("#myDropdown")
                    roles.empty()
                    roles.prepend(`<option value="">Please Select</option>`)
                    $.each(data, function(index, role) {
                        roles.append(`<option value="${index}">${role}</option>`)
                    })
                },
                error: function(xhr, status, error) {}
            });
        })

        function resetinputs() {

            $('#name').val('')
            $('#fname').val('')
            $('#firstname').val('')
            $('#last_name').val('')
            $('#Dept').val('')
            $('#Designation').val('')
            $('#StaffCode').val('')
            $('#email').val('')
            $('#password').val('')
            $('#register_no').val('')
            $('#enroll_master_id').val('')
            $('#rollNumber').val('')
            $('#phone').val('')
        }

        $("#worktype").change(function() {

            let worktype = $("#worktype").val();

            if (worktype == '2') {
                $("#days_div").show();
            } else {
                $("#days_div").hide();
            }
        })
    </script>
@endsection
