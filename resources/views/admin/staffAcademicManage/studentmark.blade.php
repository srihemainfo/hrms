@extends('layouts.teachingStaffHome')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Mark Update For My Students</h5>
        </div>
        <div class="card-body" style="max-width:100%;overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover text-center"  style="min-width:700px;">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Mark Update</th>
                        <th>View Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($got_subjects) > 0)
                        @foreach ($got_subjects as $data)
                            <tr>
                                <td>
                                   {{ $data[1] }}
                                </td>
                                <td>
                                    @foreach ($subjects as $id => $entry)
                                        @if ($id == $data[0])
                                            {{ $entry }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-primary" style="margin-left:3px;"
                                        onclick="get_students(this)"
                                        value="{{ $data[2] }}|{{ $data[0] }}|IAT-1|{{ $data[1] }}">IAT
                                        - 1</button>
                                    <button class="btn btn-xs btn-primary" style="margin-left:3px;"
                                        onclick="get_students(this)"
                                        value="{{ $data[2] }}|{{ $data[0] }}|IAT-2|{{ $data[1] }}">IAT -
                                        2</button>
                                    <button class="btn btn-xs btn-primary" style="margin-left:3px;"
                                        onclick="get_students(this)"
                                        value="{{ $data[2] }}|{{ $data[0] }}|IAT-3|{{ $data[1] }}">IAT -
                                        3</button>
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-success" style="margin-left:3px;"

                                        href="{{ route('admin.student-marks.show',['class' => $data[2],'subject' => $data[0],'exam' => 'IAT-1','short_form' => $data[1]]) }}">IAT
                                        - 1</a>
                                    <a class="btn btn-xs btn-success" style="margin-left:3px;"

                                    href="{{ route('admin.student-marks.show',['class' => $data[2],'subject' => $data[0],'exam' => 'IAT-2','short_form' => $data[1]]) }}">IAT
                                        2</a>
                                    <a class="btn btn-xs btn-success" style="margin-left:3px;"

                                    href="{{ route('admin.student-marks.show',['class' => $data[2],'subject' => $data[0],'exam' => 'IAT-3','short_form' => $data[1]]) }}">IAT
                                        3</a>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Date Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mark Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div style="width:100%;display:flex;justify-content:space-around;">
                        <div id="modal_class_name"></div>
                        <div id="modal_subject"></div>
                        <div id="modal_exam"></div>
                    </div>
                </div>
                <div class="modal-body text-center" id="modal_students">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="save_marks()">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function get_students(element) {
            let given_data = element.value;

            let split = given_data.split('|');

            let data = {
                'class': split[0],
                'subject': split[1],
                'exam': split[2],
                'short_name': split[3]
            };
            $.ajax({
                url: '{{ route('admin.get-students-for-mark.get_students') }}',
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.students != '') {
                        console.log(response)
                        $("#modal_class_name").html('Class Name : ' + response.class);
                        $("#modal_subject").html('Subject : ' + response.subject);
                        $("#modal_exam").html('Exam : ' + response.exam);
                        let got_students = response.students;
                        let students_list =
                            `<div class="row"><div class="col-4 py-2"> <b>Student Name</b></div><div class="col-4 py-2"><b>Roll No</b></div><div class="col-4 py-2"><b>Mark (Out of 100)</b></div></div>`;
                        for (let i = 0; i < got_students.length; i++) {
                            students_list +=
                                `<form id="form_${[i]}">
                                    <input type="hidden" name="exam" value="${response.exam}">
                                    <input type="hidden" name="subject" value="${response.subject_id}">
                                    <input type="hidden" name="enroll" value="${response.enroll}">
                                    <input type="hidden" name="user_name_id" value="${response.students[i]['user_name_id']}">
                                    <div class="row">
                                       <div class="col-4 py-2" style="border-top:1px solid #e9ecef;">${response.students[i]['name']}</div>
                                       <div class="col-4 py-2" style="border-top:1px solid #e9ecef;">${response.students[i]['roll_no']}</div>
                                       <div class="col-4 py-2" style="border-top:1px solid #e9ecef;">
                                           <input type="text" name="student_mark" style="width:50px;display:block;margin:auto;outline:none;padding-left:15px;" value="${response.students[i]['mark']}">
                                       </div>
                                    </div>
                                </form>`;
                        }

                        $("#modal_students").html(students_list);
                        $("#exampleModal").modal('show');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });
            // console.log(split)
        }

        function save_marks() {
            let forms = document.querySelectorAll("form");

            let data_array = [];
            for (i = 0; i < (forms.length - 1); i++) {
                let id = forms[i].getAttribute('id');

                let data = $("#" + id).serializeArray();

                data_array.push(data);

            }
            if (data_array.length > 0) {
                // console.log(data_array)
                $.ajax({
                    url: '{{ route('admin.save-students-mark.store') }}',
                    type: 'POST',
                    data: {
                        'data': data_array
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // console.log(response)
                        $("#exampleModal").modal('hide');
                        if(response.status == true){
                            alert('Mark Submitted For This Students');
                        }else{
                            alert('Error...');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                    }
                })
            }
        }
    </script>
@endsection
