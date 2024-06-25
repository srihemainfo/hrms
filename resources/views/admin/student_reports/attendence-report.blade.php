@extends('layouts.admin')
@section('content')
    <style>
        .num-r {
            background: #eee;
            width: 60px;
            text-align: center;
            height: 60px;
            padding: 13px 0;
            border-radius: 50px;
            font-size: 22px;
            font-weight: 800;
            color: #989393;
        }
    </style>
    <div class="container"
        style="
        background-color: #fefefe;
        box-shadow: -2px 3px 12px 4px #c6c0c0a8;
        border-radius: 5px;">


        <div class="pt-4 pb-4">

            <div>
                <div class="d-flex">
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <label class="required" for="AcademicYear">Academic year</label>
                        <select class="form-control select2 " name="AcademicYear" id="AcademicYear" required>
                            <option value="">Please Select</option>
                            @foreach ($AcademicYear as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <label class="required" for="course">{{ trans('cruds.lesson.fields.course') }}</label>
                        <select class="form-control select2 " name="course" id="course" required>
                            <option value="">Please Select</option>
                            @foreach ($courses as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <label for="semester" class="required">Semester</label>
                        <select class="form-control select2" name="semester" id="semester" required>
                            <option value="">Please Select</option>
                            @foreach ($semester as $id => $entry)
                                <option value="{{ $entry }}">
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <label for="student_name" class="required">Student Name</label>
                        <select class="form-control select2 " name="student_name" id="student_name">
                            <option value="">Select Student</option>

                        </select>

                        <span class="help-block"> </span>
                    </div>
                    <input type="hidden" name="" value="" id="admitted_course">
                    <input type="hidden" name="" value="" id="user_name_id">
                    <input type="hidden" name="" value="" id="current_semester">
                    <input type="hidden" name="" value="" id="enroll_master_id">
                    <input type="hidden" name="" value="" id="dynamicId">
                </div>

            </div>
        </div>
    </div>
    <style>
        .background-u {
            box-shadow: -2px 3px 12px 4px #a09f9fa8;
            border-radius: 5px;
            background: #007bffe0;
            color: #fff;
            transition: background-color 0.9s
        }

        .background-s:hover {
            cursor: pointer;
            background: #007bffe0;
            /* transition: background-color 0.5s */

        }
    </style>
    <div class="div mx-1" style="display: none;" id="cards">
        <div class="row pt-5">
            <div class="col-md-3">
                <div class="card background-s" id="sem-1">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 1</p>
                                <h5 class="card-title sem-1">-</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">01</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card background-s" id="sem-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 2</p>
                                <h5 class="card-title sem-2">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">02</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card background-s"id="sem-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 3</p>
                                <h5 class="card-title sem-3">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">03</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card background-s" id="sem-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 4</p>
                                <h5 class="card-title sem-4">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">04</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row ">
            <div class="col-md-3">
                <div class="card background-s" id="sem-5">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 5</p>
                                <h5 class="card-title sem-5">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">05</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card background-s"id="sem-6">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 6</p>
                                <h5 class="card-title sem-6">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">06</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card background-s" id="sem-7">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 7</p>
                                <h5 class="card-title sem-7">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">07</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card background-s" id="sem-8">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <p class="my-1">Semester 8</p>
                                <h5 class="card-title sem-8">Card title</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="num-r">08</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card" id="tableShow"
        style="display:none;box-shadow: -2px 3px 12px 4px #c6c0c0a8;
        border-radius: 5px; display: none;">
        <div class="card-header">
            <div>Attendence Report</div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-examtimetable"
                id="dataTable">
                <thead>
                    <tr>
                        <th class="text-center">Sl/No</th>
                        <th class="text-center">Subject ID</th>
                        <th class="text-center">Subject Name</th>
                        <th class="text-center">Total Hours</th>
                        <th class="text-center">Attended Hours</th>
                        <th class="text-center">Percentage</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <style>
        #loaders {
            position: absolute;
            z-index: 99;
            top: 50%;
            left: 45%;
        }

        body {
            min-height: 100vh;
            /* font-family: Roboto, Arial; */
            /* color: #ADAFB6; */
            /* display: flex; */
            justify-content: center;
            align-items: center;
            background: rgba(134, 134, 134, 0.6);

        }

        .boxes {
            height: 32px;
            width: 32px;
            position: relative;
            -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
            -webkit-transform-origin: 50% 50%;
            transform-origin: 50% 50%;
            margin-top: 32px;
            -webkit-transform: rotateX(60deg) rotateZ(45deg) rotateY(0deg) translateZ(0px);
            transform: rotateX(60deg) rotateZ(45deg) rotateY(0deg) translateZ(0px);
        }

        .boxes .box {
            width: 32px;
            height: 32px;
            top: 0px;
            left: 0;
            position: absolute;
            -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
        }



        .boxes .box:nth-child(1) {
            -webkit-transform: translate(100%, 0);
            transform: translate(100%, 0);
            -webkit-animation: box1 1s linear infinite;
            animation: box1 1s linear infinite;
        }

        .boxes .box:nth-child(2) {
            -webkit-transform: translate(0, 100%);
            transform: translate(0, 100%);
            -webkit-animation: box2 1s linear infinite;
            animation: box2 1s linear infinite;
        }

        .boxes .box:nth-child(3) {
            -webkit-transform: translate(100%, 100%);
            transform: translate(100%, 100%);
            -webkit-animation: box3 1s linear infinite;
            animation: box3 1s linear infinite;
        }

        .boxes .box:nth-child(4) {
            -webkit-transform: translate(200%, 0);
            transform: translate(200%, 0);
            -webkit-animation: box4 1s linear infinite;
            animation: box4 1s linear infinite;
        }



        .boxes .box>div {
            background: #5C8DF6;
            --translateZ: 15.5px;
            --rotateY: 0deg;
            --rotateX: 0deg;
            position: absolute;
            width: 100%;
            height: 100%;
            background: #5C8DF6;
            top: auto;
            right: auto;
            bottom: auto;
            left: auto;
            -webkit-transform: rotateY(var(--rotateY)) rotateX(var(--rotateX)) translateZ(var(--translateZ));
            transform: rotateY(var(--rotateY)) rotateX(var(--rotateX)) translateZ(var(--translateZ));
        }

        .boxes .box>div:nth-child(1) {
            top: 0;
            left: 0;
            background: #5C8DF6;
        }

        .boxes .box>div:nth-child(2) {
            background: #145af2;
            right: 0;
            --rotateY: 90deg;
        }

        .boxes .box>div:nth-child(3) {
            background: #447cf5;
            --rotateX: -90deg;
        }

        .boxes .box>div:nth-child(4) {
            background: #DBE3F4;
            top: 0;
            left: 0;
            --translateZ: -90px;
        }





        @keyframes box1 {

            0%,
            50% {
                transform: translate(100%, 0);
            }

            100% {
                transform: translate(200%, 0);
            }
        }

        @keyframes box2 {
            0% {
                transform: translate(0, 100%);
            }

            50% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(100%, 0);
            }
        }

        @keyframes box3 {

            0%,
            50% {
                transform: translate(100%, 100%);
            }

            100% {
                transform: translate(0, 100%);
            }
        }

        @keyframes box4 {
            0% {
                transform: translate(200%, 0);
            }

            50% {
                transform: translate(200%, 100%);
            }

            100% {
                transform: translate(100%, 100%);
            }
        }
    </style>
    <div class="overall">

        <div class="boxes " style="display: none;" id="loaders">
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        window.onload = function() {
            var $selects = $('#course, #semester,#AcademicYear');

            $selects.change(function() {
                var allFilled = true;
                $selects.each(function() {
                    if ($(this).val() === '') {
                        allFilled = false;
                        return false;
                    }
                });

                var token = $('meta[name="csrf-token"]').attr('content');

                var data = {
                    AcademicYear: $('#AcademicYear').val(),
                    course: $('#course').val(),
                    semester: $('#semester').val(),
                    _token: token
                };
                if (allFilled) {
                    $('#tableShow').hide();
                    $("#loaders").show();
                    $('#cards').hide();


                    $('#student_name').empty();
                    $.ajax({
                        url: "{{ route('admin.Attendence-Details.search') }}",
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            let select = $('#student_name');
                            select.append('<option value="">Please select</option>');
                            $.each(response.data, function(index, user) {
                                // console.log(user.user_name_id)
                                var option = $('<option>').text(user.name).val(user
                                    .user_name_id);
                                select.append(option);
                            });
                            $("#loaders").hide();
                        },
                        error: function(xhr, status, error) {
                            $("#loaders").hide();
                            console.log('An error occurred: ' + error);
                        }
                    });
                }
            });
            var $student = $('#course, #semester,#student_name');
            $('#student_name').change(function() {
                $('#tableShow').hide();
                $("#loaders").show();
                $('#cards').hide();
                var selectedValue = $(this).val();
                var token = $('meta[name="csrf-token"]').attr('content');

                var data = {
                    student_name: $('#student_name').val(),
                    course: $('#course').val(),
                    semester: $('#semester').val(),
                    _token: token
                };

                if (selectedValue === '') {
                    $("#loaders").hide();
                    alert('Please Select Student Name');
                } else {
                    $.ajax({
                        url: "{{ route('admin.Attendence-Details.studentGet') }}",
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            $('#cards').show();
                            $('#admitted_course').val(response.data.admitted_course);
                            $('#user_name_id').val(response.data.user_name_id);
                            $('#current_semester').val(response.data.current_semester);
                            $('#enroll_master_id').val(response.data.enroll_master_id);
                            if (response.attendence !== '') {
                                $.each(response.attendence, function(index, user) {
                                    console.log(user);
                                    $('.sem-' + user.semester).text(user.totalAttended);
                                });
                            }
                            $("#loaders").hide();
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            $("#loaders").hide();
                            console.log('An error occurred: ' + error);
                        }
                    });
                }
            });

        }

        function reset() {
            var number = '';
        }


        $('.card.background-s').click(function() {
            $("#loaders").show();
            var divId = $(this).attr('id');
            number = divId.match(/-(\d+)/)[1];
            let lastid = $('#dynamicId').val();
            $(lastid).removeClass('background-u');
            var token = $('meta[name="csrf-token"]').attr('content');
            var data = {
                course: $('#admitted_course').val(),
                semester: number,
                user_id: $('#user_name_id').val(),
                enroll_master_id: $('#enroll_master_id').val(),
                divId: divId,
                _token: token
            };

            $.ajax({
                url: "{{ route('admin.Attendence-Details.tableShow') }}",
                method: 'POST',
                data: data,
                success: function(response) {
                    var dynamicId = '#' + response.div_id;
                    $(dynamicId).addClass('background-u');
                    $('#tableShow').show();
                    $('#dynamicId').val(dynamicId);
                    $('#datatable-examtimetable').empty();

                    reset();
                    $("#loaders").hide();
                    let dtOverrideGlobals = {
                        deferRender: true,
                        retrieve: true,
                        aaSorting: [],
                        data: response.subjects,
                        columns: [{
                                data: null,
                                name: 'empty',
                                render: function(data, type, full, meta) {
                                    return ' ';
                                }
                            },
                            {
                                data: 'subject_code',
                                name: 'subject_code'
                            },
                            {
                                data: 'subjectName',
                                name: 'subjectName'
                            },
                            {
                                data: 'count',
                                name: 'count'
                            },
                            {
                                data: 'attended',
                                name: 'attended'
                            },
                            {
                                data: 'percentageAttended',
                                name: 'percentageAttended'
                            },


                        ],
                        orderCellsTop: true,
                        order: [
                            [1, 'desc']
                        ],
                        pageLength: 10,
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            var total1 = api.column(3).data().reduce(function(a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);
                            var total4 = api.column(4).data().reduce(function(a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);
                            // var totalPercentage = api.column(5).data().reduce(function(
                            //     a, b) {
                            //     var value = parseFloat(b);
                            //     if (!isNaN(value)) {
                            //         return a + value;
                            //     } else {
                            //         return a;
                            //     }
                            // }, 0);
                            var pageTotal = api.column(3, {
                                page: 'current'
                            }).data().reduce(function(a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);
                            var percentageTotal = (total4 / total1) * 100;
                            // var percentageTotal = Math.min(totalPercentage, 100);
                            $(api.table().footer()).html(
                                '<tr>' +
                                '<td colspan="3" class="text-center"><strong>Total:</strong></td>' +
                                '<td> ' + total1 + '  </td>' +
                                '<td>' + total4 + '  </td>' +
                                '<td> ' + (percentageTotal ? percentageTotal.toFixed(2) +
                                    '%' : '') + ' </td>' +
                                '</tr>'
                            );
                        }

                    };

                    let table = $('#dataTable').DataTable(dtOverrideGlobals);
                    table.destroy();
                    table = $('#dataTable').DataTable(dtOverrideGlobals);
                    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable()
                            .columns.adjust();
                    });




                },
                error: function(xhr, status, error) {
                    // Handle errors
                    $("#loaders").hide();
                    $('#datatable-examtimetable').empty();
                    console.log('An error occurred: ' + error);
                }
            });
        });
    </script>
@endsection
