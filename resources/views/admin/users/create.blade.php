@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
        </div>
        <div class="card-body" id="one">
            <div>

                <div class="form-group required">
                    <label class="required" for="roles">Role Type</label>
                    <select id="role_type" class="form-control select2" name="role_type" required>
                        <option value="">Select Role Type</option>
                        @foreach ($TeachingType as $i => $type)
                            <option value="{{ $i }}">{{ $type }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group required">
                    <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                    <select id="myDropdown" class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}"
                        name="roles" required>
                        <option value="">Select Role</option>

                    </select>

                </div>
                <div class="form-group studentDiv" style="display: none" id="AdminDiv">
                    <label class="required" for="name">Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        style="text-transform:uppercase;" type="text" name="name" id="name"
                        value="{{ old('name', '') }}">
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                </div>
                <div class="form-group" style="display: none" id="namefull">
                    <label class="required" for="name">Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        style="text-transform:uppercase;" type="text" name="fullname" id="fname"
                        value="{{ old('fullname', '') }}">
                    @if ($errors->has('fullname'))
                        <span class="text-danger">{{ $errors->first('fullname') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                </div>
                <div class="form-group staff" style="display: none">
                    <label class="required" for="name">{{ 'First Name' }}</label>
                    <input class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }} "
                        style="text-transform:uppercase;" type="text" name="firstname" id="firstname"
                        value="{{ old('firstname', '') }}">
                    @if ($errors->has('firstname'))
                        <span class="text-danger">{{ $errors->first('firstname') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.teachingStaff.fields.name_helper') }}</span>
                </div>
                <div class="form-group staff" style="display: none">
                    <label class="required" for="last_name">{{ 'Last Name' }}</label>
                    <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                        style="text-transform:uppercase;" type="text" name="last_name" id="last_name"
                        value="{{ old('last_name', '') }}">
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.teachingStaff.fields.name_helper') }}</span>
                </div>
                <div class="form-group dept" style="display: none">
                    <label class="required" for="Dept">{{ 'Department' }}</label>
                    <select class="form-control select2 {{ $errors->has('Dept') ? 'is-invalid' : '' }} " name="Dept"
                        id="Dept">
                        <option value="">Please Select</option>

                        @foreach ($department as $id => $entry)
                            <option value="{{ $id }}" {{ old('Dept') == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('Dept'))
                        <span class="text-danger">{{ $errors->first('Dept') }}</span>
                    @endif
                </div>
                @if ($shift)
                    <div class="form-group shift" style="display: none">
                        <label class="" for="shift">{{ 'Shift' }}</label>
                        {{-- <input class="form-control" type="text" id="shift" name="shift" value=""> --}}
                        <select class="form-control select2 {{ $errors->has('shift') ? 'is-invalid' : '' }} " name="shift"
                            id="shift">
                            <option value="">Select Shift</option>
                            @foreach ($shift as $id => $entry)
                                <option value="{{ $id }}" {{ old('shift') == $id ? 'selected' : '' }}>
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('shift'))
                            <span class="text-danger">{{ $errors->first('shift') }}</span>
                        @endif
                    </div>
                @endif


                <div class="form-group staff" style="display: none">
                    <label class="" for="Designation">{{ 'Designation' }}</label>
                    <input class="form-control" type="text" id="Designation" name="Designation" value="">
                    @if ($errors->has('Designation'))
                        <span class="text-danger">{{ $errors->first('Designation') }}</span>
                    @endif
                </div>
                <div class="form-group doj" style="display: none;">
                    <label for="doj" class="required">Date Of Joining</label>
                    <input type="text" id="doj" class="form-control date" name="doj"
                        placeholder="Enter Date Of Joining" required>
                </div>

                <div class="form-group staff" style="display: none">
                    <label class="required" for="staff_code">{{ 'Staff Code' }}</label>
                    <input class="form-control {{ $errors->has('StaffCode') ? 'is-invalid' : '' }}" type="text"
                        name="StaffCode" id="StaffCode" value="{{ old('StaffCode', '') }}">
                    @if ($errors->has('StaffCode'))
                        <span class="text-danger">{{ $errors->first('StaffCode') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="email" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                </div>
                <div class="form-group main" style="display: none">
                    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                        name="password" id="password">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                </div>
                <div class="form-group studentDiv" style="display: none">
                    <label class="required" for="register_no">Register Number</label>
                    <input class="form-control {{ $errors->has('register_no') ? 'is-invalid' : '' }}" type="number"
                        name="register_no" id="register_no" value="{{ old('register_no', '') }}" step="1">
                    @if ($errors->has('register_no'))
                        <span class="text-danger">{{ $errors->first('register_no') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.reg_no_helper') }}</span>
                </div>
                <div class="form-group studentDiv" style="display: none">
                    <label class="required"
                        for="enroll_master_id">{{ trans('cruds.student.fields.enroll_master') }}</label>

                    <select class="form-control select2  {{ $errors->has('enroll_master') ? 'is-invalid' : '' }}"
                        name="enroll_master_id" id="enroll_master_id">
                        <option value="">Please Select</option>

                        @foreach ($enroll_masters as $id => $entry)
                            <option value="{{ $id }}" {{ old('enroll_master_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('enroll_master'))
                        <span class="text-danger">{{ $errors->first('enroll_master') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.student.fields.enroll_master_helper') }}</span>
                </div>
                <div class="form-group studentDiv" style="display: none">
                    <label class="required" for="rollNumber">{{ 'Roll Number' }}</label>
                    <input class="form-control {{ $errors->has('rollNumber') ? 'is-invalid' : '' }}" type="rollNumber"
                        name="rollNumber" id="rollNumber" value="{{ old('rollNumber') }}">
                    @if ($errors->has('rollNumber'))
                        <span class="text-danger">{{ $errors->first('rollNumber') }}</span>
                    @endif
                    {{-- <span class="help-block">{{ 'Roll Number' }}</span> --}}
                </div>
                <div class="form-group phone" style="display: none">
                    <label class="required" for="phone">{{ 'Phone Number' }}</label>
                    <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="number"
                        name="phone" id="phone" value="{{ old('phone') }}">
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                    {{-- <span class="help-block">{{ 'Roll Number' }}</span> --}}
                </div>

                <div class="form-group">
                    <button class="btn btn-danger" id="btnsave">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        const depart = [];
        window.onload = function() {
            $("select").select2()

            let dept = $("#Dept");
            for (let i = 0; i < dept.find('option').length; i++) {
                let option = {
                    text: $.trim($(dept.find('option')[i]).text()),
                    value: $(dept.find('option')[i]).val()
                };
                depart.push(option);
            }
        }

        $('#myDropdown').change(function() {
            let roleType = $('#role_type').val()
            let role = $(this).find('option:selected').text()
            let roleValue = $(this).val()
            // console.log(roleType, role, roleValue);
            if (roleType == 1 || roleType == 3) {

                $("#Designation").val(role);
                $(".staff").show();
                $(".shift").show();
                $(".dept").show();
                $(".doj").show();
                let dept = $('#Dept')
                dept.empty()
                $(depart).each(function(index, data) {
                    if (data.text != 'CIVIL' && data.text != 'ADMIN') {
                        dept.append(`<option value="${data.value}">${data.text}</option>`)

                    }
                })
                $('.main').hide();
                $('.phone').show();
                $('#namefull').hide();
                $('.studentDiv').hide();


            } else if (roleType == 2 || roleType == 4 || roleType == 5) {
                if (roleType == 5) {
                    $("#Designation").val(role);
                    $(".staff").show();
                    $(".dept").show();
                    $(".shift").hide();
                    $(".doj").show();
                    let dept1 = $('#Dept')
                    dept1.empty()
                    $(depart).each(function(index, data) {
                        if (data.text == 'CIVIL') {
                            dept1.prepend(`<option value="">Please Select</option>`)
                            dept1.append(`<option value="${data.value}">${data.text}</option>`)


                        }
                    })
                    $('.main').hide();
                    $('.phone').show();
                    $('#namefull').hide();
                    $('.studentDiv').hide();
                } else if (roleType == 4) {
                    $("#Designation").val(role);
                    $(".staff").show();
                    $(".dept").show();
                    $(".doj").show();
                    $(".shift").hide();
                    let dept2 = $('#Dept')
                    dept2.empty()
                    $(depart).each(function(index, data) {
                        if (data.text == 'ADMIN') {
                            dept2.prepend(`<option value="">Please Select</option>`)
                            dept2.append(`<option value="${data.value}">${data.text}</option>`)


                        }
                    })
                    $('.main').hide();
                    $('.phone').show();
                    $('#namefull').hide();
                    $('.studentDiv').hide();
                } else {
                    if (roleValue == 60) {
                        $("#Designation").val(role);
                        $(".staff").show();
                        $(".shift").hide();
                        $(".dept").hide();
                        $(".doj").show();
                        // let dept3 = $('#Dept')
                        // dept3.empty()
                        // $(depart).each(function(index, data) {
                        //     if (data.text != 'CIVIL' && data.text != 'ADMIN') {
                        //         dept3.append(`<option value="${data.value}">${data.text}</option>`)

                        //     }
                        // })
                        $('.main').hide();
                        $('.phone').show();
                        $('#namefull').hide();
                        $('.studentDiv').hide();
                    } else {
                        $("#Designation").val(role);
                        $(".staff").show();
                        $(".shift").hide();
                        $(".dept").show();
                        $(".doj").show();
                        let dept3 = $('#Dept')
                        dept3.empty()
                        $(depart).each(function(index, data) {
                            if (data.text != 'CIVIL' && data.text != 'ADMIN') {
                                dept3.append(`<option value="${data.value}">${data.text}</option>`)

                            }
                        })
                        $('.main').hide();
                        $('.phone').show();
                        $('#namefull').hide();
                        $('.studentDiv').hide();
                    }
                }
            } else {
                if (roleValue == 11) {
                    $(".staff").hide();
                    $('.main').hide();
                    $(".dept").hide();
                    $(".doj").hide();
                    $(".shift").show();
                    $('.phone').show();
                    $('#namefull').hide();
                    $('.studentDiv').show();
                } else {
                    $('#namefull').show();
                    $(".shift").hide();
                    $('.main').show();
                    $(".staff").hide();
                    $('.phone').hide();
                    $(".dept").hide();
                    $(".doj").hide();
                    $('.studentDiv').hide();
                }

            }
        })

        $(document).ready(function() {

            @if ($errors->count() > 0)
                $(window).on('load', function() {
                    $("#myDropdown").trigger("change");

                });
            @endif

        });

        $('#role_type').change(function() {
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

        $('#btnsave').click(function() {
            let role_type = $('#role_type').val()
            let role = $('#myDropdown').val()
            let name = $('#name').val().toUpperCase()
            let fname = $('#fname').val().toUpperCase()
            let firstname = $('#firstname').val().toUpperCase()
            let last_name = $('#last_name').val().toUpperCase()
            let Dept = $('#Dept option:selected').text()
            let Designation = $('#Designation').val()
            let StaffCode = $('#StaffCode').val()
            let email = $('#email').val()
            let doj = $('#doj').val()
            console.log(doj);
            let password = $('#password').val()
            let register_no = $('#register_no').val()
            let enroll_master_id = $('#enroll_master_id').val()
            let rollNumber = $('#rollNumber').val()
            let phone = $('#phone').val()
            let shift = $('#shift').val()

            var data = ''
            if (role_type != '' && role != '') {
                if ((role_type == 1 || role_type == 3 || role_type == 2 || role_type == 4 || role_type == 5) &&
                    firstname != '' && doj != '' && last_name != '' && Dept != '' && Designation != '' &&
                    StaffCode != '' && email != '' && phone != '' && role_type != '') {
                    data = {
                        'firstname': firstname,
                        'last_name': last_name,
                        'Dept': Dept,
                        'shift': shift,
                        'doj': doj,
                        'Designation': Designation,
                        'StaffCode': StaffCode,
                        'email': email,
                        'phone': phone,
                        'role_type': role_type,
                        'role': role

                    }
                } else if (role_type == 6) {
                    if (role == 11 && name != '' && email != '' && register_no != '' && enroll_master_id != '' &&
                        rollNumber != '' && phone != '' && role_type != '') {
                        data = {
                            'name': name,
                            'email': email,
                            'shift': shift,
                            'register_no': register_no,
                            'enroll_master_id': enroll_master_id,
                            'rollNumber': rollNumber,
                            'phone': phone,
                            'role_type': role_type,
                            'role': role

                        }
                    } else {
                        if (fname != '' && email != '' && password != '' && role_type != '') {
                            data = {
                                'fname': fname,
                                'email': email,
                                'password': password,
                                'role_type': role_type,
                                'role': role

                            }
                        }

                    }

                }

                if (data != '') {
                    $.ajax({
                        url: "{{ route('admin.users.store') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        success: function(response) {

                            let status = response.status;
                            let data = response.data;
                            if (status == true) {
                                Swal.fire('', data, 'success');
                                location.reload();
                            } else {
                                Swal.fire('', data, 'error');
                            }
                        }
                    })
                } else {
                    Swal.fire('', "Enter all Details", 'error');
                }

            } else {
                Swal.fire('', "Select all fields", 'error');
            }




        })
    </script>
@endsection
