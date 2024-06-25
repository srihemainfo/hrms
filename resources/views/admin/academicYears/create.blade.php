@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.academicYear.title_singular') }}
        </div>

        <div class="card-body">
            <div class="loader" id="loader" style="display:none;top:15%;">
                <div class="spinner-border text-primary"></div>
            </div>
            <form method="POST" action='{{ route('admin.academic-years.store') }}' enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div id="error-message" class="error-message bg-danger">

                    </div>
                </div>
                <div class="form-group">
                    <div id="error-message2" class="error-message bg-danger">

                    </div>
                </div>

                <div class="form-group">

                    <label class="required" for="from">{{ trans('cruds.academicYear.fields.from') }}</label>
                    <select class="form-control select2 {{ $errors->has('from') ? 'is-invalid' : '' }}" name="from"
                        id="from" required>
                        <option value="">Select From Year</option>
                        @foreach ($year as $id => $entry)
                            <option value="{{ $entry }}" {{ old('from') == $entry ? 'selected' : '' }}>
                                {{ $entry }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('course'))
                        <span class="text-danger">{{ $errors->first('from') }}</span>
                    @endif

                </div>

                <div class="form-group">
                    <label class="required" for="to">{{ trans('cruds.academicYear.fields.to') }}</label>
                    <input class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}" type="number" name="to"
                        id="to" value="{{ old('to', '') }}" step="1" required readonly>
                    @if ($errors->has('to'))
                        <span class="text-danger">{{ $errors->first('to') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.academicYear.fields.to_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.academicYear.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                        name="name" id="name" value="{{ old('name', '') }}" required readonly>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.academicYear.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-danger" id="myForm" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#myForm").on("submit", function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.academic-years.store') }}",
                    data: formData,
                    dataType: "json",
                    success: function(data) {
                        if (data.redirect) {
                            window.history.pushState(null, null, data
                                .redirect); // Update URL without refresh
                            // You can also update the content of the page dynamically here if needed
                        } else {
                            // Handle other scenarios if needed
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error',
                                    'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                });
            });
        });

        $(document).ready(function() {

            const $from = $("#from");
            const $to = $("#to");
            const $name = $("#name");
            const $error = $("#error-message");
            const $error2 = $("#error-message");

            $from.on("change", function() {
                if ($from.val() != '') {

                    $("#loader").show();
                    $from.prop("disabled", true);
                    $to.val('');
                    $name.val('');
                    $error.val('');
                    $error2.val('');
                    const year = this.value;
                    let additionalValue = 1;
                    let intValue = parseInt(year, 10);
                    intValue += additionalValue;
                    let resultString = intValue.toString();
                    let name = year + '-' + resultString;

                    $.ajax({
                        url: "{{ route('admin.AcademicYear.check') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            to: resultString,
                            name: name,
                        },
                        success: function(response) {
                            if (response.status == 'year_not') {
                                $name.val("");
                                $to.val("");
                                $error2.text("Create Year First").show();
                                $("#loader").hide();
                                $from.prop("disabled", false);
                                setTimeout(function() {
                                    $error2.hide();
                                }, 5000);


                            } else if (response.status == true) {
                                $to.val(resultString);
                                let toValue = $to.val();
                                let nameValue = year + '-' + toValue;
                                $name.val(nameValue);
                                $("#loader").hide();
                                $from.prop("disabled", false);
                                $error.hide();
                            } else {
                                $name.val("");
                                $to.val("");
                                $error.text("Combination already exists").show();
                                $("#loader").hide();
                                $from.prop("disabled", false);
                                setTimeout(function() {
                                    $error.hide();
                                }, 5000);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error',
                                        'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                    "error");
                            }
                            $from.val('');
                            $to.val('');
                            $name.val('');
                            $error.val('');
                            $error2.val('');
                            $("#loader").hide();
                            $from.prop("disabled", false);
                        }
                    });

                } else {
                    $to.val('');
                    $name.val('');
                    $error.val('');
                    $error2.val('');
                    $("#loader").hide();

                }
            });
        });
    </script>
@endsection
