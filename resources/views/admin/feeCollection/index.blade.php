@extends('layouts.admin')
@section('content')
    <div class="loading" id='loading' style='display:none'>Loading&#8230;</div>
    <div class="row">
        <div class="form-group col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
            <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
            <div class="card">
                <div class="row">
                    <div class="col-11">
                        <div class="input-field" style="padding-left: 0.50rem;">
                            <input type="text" name="name" id="autocomplete-input"
                                style="margin:0;padding-left:0.50rem;" placeholder="Enter Student Rollnumber"
                                class="autocomplete" autocomplete="off" onchange="run(this)">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div id="info"></div>
            <div class="row">
                <div class="col-md-6 col-12 form-group">
                    <div><b>Name</b> : <span id="student_name"></span></div>
                </div>
                <div class="col-md-6 col-12 form-group">
                    <div><b>Roll Number</b> : <span id="roll_number"></span></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
        integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const student = [];

        window.onload = function() {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.student-rollnumber.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                    let details = data.student;
                    let student = {};
                    for (let i = 0; i < details.length; i++) {
                        student[details[i]] = null;
                    }
                    $('input.autocomplete').autocomplete({
                        data: student,
                    });
                    $('#loading').hide();

                },
                error: function(jqXHR, textStatus, errorThrown) {
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

            })

        }


        function run(element) {
            $("#info").html(`Loading......`);
            let roll_no = $(element).val();
            let roll_no_length =  roll_no.length;

            $.ajax({
                url: '{{ route('admin.student-details.alldetails') }}',
                type: 'POST',
                data: {
                    'roll_no': roll_no
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if(data.status)
                    {
                        $('#student_name').text(data.name);
                        $('#roll_number').text(data.roll_no);
                        $("#info").hide();
                    }
                    else{
                        $('#student_name').text('');
                        $('#roll_number').text('');
                        $("#info").show();
                        $("#info").html(`No data found`);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 404) {
                        Swal.fire('', 'Student not found', 'error');
                    } else {
                        Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, 'error');
                    }
                    $("#info").show();
                    $("#info").html(`No data found`);
                }


            });
        }
    </script>
@endsection
