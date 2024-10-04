@extends('layouts.teachingStaffHome')
@section('content')
    {{-- {{ dd($class_name) }} --}}
    <a class="btn btn-default"
        href="{{ route('admin.staff-subjects.lesson-plan', ['user_name_id' => $user_name_id, 'name' => $name, 'status' => 0]) }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card" style="margin-top:1rem;">
        <div class="card-body">
            <div class="row gutters">
                <div class="col-md-2 col-12">
                    <label for="class_name ">Class Name</label><br>
                    <span class="manual_bn">{{ $short_form }}</span>
                </div>
                <div class="col-md-6 col-12">
                    <label for="subject">Subject</label><br>
                    <span class="manual_bn">{{ $get_subject->name }} ({{ $get_subject->subject_code }})</span>
                </div>
            </div>

        </div>
    </div>
    <div class="card" id="lesson_plan">
        <div class="card-header">
            <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary"> Lesson Plans</h5>
        </div>
        <div class="card-body">
            @if (count($lessons) > 0)
                @php
                    $no = 1;
                @endphp
                <div id="units">
                    @foreach ($lessons as $data)
                        <div class="card add_color" id="unit_{{ $no }}">
                            <div class="card-body">
                                <input type="hidden" name="class_name" id="class_name" value="{{ $data[0]->class }}">
                                <input type="hidden" name="subject" id="subject" value="{{ $data[0]->subject }}">
                                <form id="form_{{ $no }}">
                                    <div class="row">
                                        <div class="col-md-6 col-9">
                                            <div class="form-group">
                                                <label class="unit_label">Unit - {{ $no }}</label>
                                                <input class="form-control" name="form_1_unit" type="text"
                                                    value="{{ $data[0]->unit }}" placeholder="Unit - {{ $no }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-1"></div>
                                        <div class="col-md-2 col-2">
                                            <div class="form-group text-center">
                                                <label>Remove Unit</label>
                                                <div>
                                                    <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                        class="fa fa-times-circle"
                                                        onclick="remove_unit('unit_{{ $no }}')">
                                                    </i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($data as $id => $datas)
                                        <div class="row gutters">
                                            <div class="col-md-2 col-12">
                                                <div class="form-group">
                                                    <label>Proposed Date</label>
                                                    <input class="form-control" name="proposed_date" type="date"
                                                        value="{{ $datas->proposed_date }}" placeholder="Proposed Date">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label class="topic_label">Topic-{{ $id + 1 }}</label>
                                                    <input class="form-control" name="form_1_topic_1" type="text"
                                                        value="{{ $datas->topic }}"
                                                        placeholder="Topic-{{ $id + 1 }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label>Text Books / Reference</label>
                                                    <input class="form-control" name="text_book" type="text"
                                                        value="{{ $datas->text_book }}"
                                                        placeholder="Text Books / Reference">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-10">
                                                <div class="form-group">
                                                    <label>Delivery Methods</label>
                                                    <input class="form-control" name="delivery_method" type="text"
                                                        value="{{ $datas->delivery_method }}"
                                                        placeholder="Delivery Methods">
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-2">
                                                <div class="form-group text-center">
                                                    <label>Remove</label>
                                                    <div>
                                                        <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                            class="fa fa-times-circle" onclick="remove_element(this)">
                                                        </i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </form>
                                <button class="enroll_generate_bn" value="form_{{ $no }}"
                                    onclick="add_topic(this)"><i class="right fa fa-fw fa-angle-down"></i>Add Topic</button>

                            </div>
                        </div>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </div>
            @endif
            <div style="display:flex;justify-content:space-between;">
                <div>
                    <button class="enroll_generate_bn" onclick="add_unit()"><i class="right fa fa-fw fa-angle-down"></i>Add
                        Unit</button>
                </div>
                <div>
                    <button class="enroll_generate_bn" style="margin-right:5px;" onclick="save()">Save & Exit</button>
                    <button class="enroll_generate_bn bg-success" onclick="submit()">Submit</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function add_topic(element) {
            let parent = element.value;
            let len = $("#" + parent).children().length;

            $("#" + parent).append(`<div class="row gutters">
                                      <div class="col-md-2 col-12">
                                    <div class="form-group">
                                         <label>Proposed Date</label>
                                        <input class="form-control" name="proposed_date" type="date" value=""
                                            placeholder="Proposed Date" onchange="date_check(this)" required>
                                    </div>
                                </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label>Topic-${len}</label>
                                    <input class="form-control" name="${parent}_topic_${len}" type="text" value="" placeholder="Topic-${len}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label>Text Books / Reference</label>
                                        <input class="form-control" name="text_book" type="text" value="" placeholder="Text Books / Reference">
                                    </div>
                            </div>
                                <div class="col-md-2 col-10">
                                    <div class="form-group">
                                        <label>Delivery Methods</label>
                                        <input class="form-control" name="delivery_method" type="text" value="" placeholder="Delivery Methods">
                                    </div>
                            </div>
                            <div class="col-md-1 col-2">
                                <div class="form-group text-center">
                                    <label>Remove</label>
                                    <div>
                                        <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                class="fa fa-times-circle" onclick="remove_element(this)">
                                            </i>
                                    </div>
                                </div>
                            </div>
                        </div>`);
        }

        function add_unit() {
            let len = $("#units").children().length;
            $("#units").append(`
            <div class="card add_color" id="unit_${len + 1}">
            <div class="card-body">
                <form id="form_${len + 1}">
                    <div class="row">
                        <div class="col-md-6 col-9">
                            <div class="form-group">
                                <label class="unit_label">Unit-${len + 1}</label>
                                <input class="form-control" name="form_${len + 1}_unit" type="text" value=""
                                    placeholder="Unit - ${len + 1}[Title]">
                            </div>
                        </div>
                        <div class="col-md-3 col-1"></div>
                        <div class="col-md-2 col-2">
                           <div class="form-group text-center">
                                <label>Remove Unit</label>
                                 <div>
                                    <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                class="fa fa-times-circle" onclick="remove_unit('unit_${len + 1}')">
                                    </i>
                                 </div>
                           </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div class="col-md-2 col-12">
                                    <div class="form-group">
                                         <label>Proposed Date</label>
                                        <input class="form-control" name="proposed_date" type="date" value=""
                                            placeholder="Proposed Date" onchange="date_check(this)" required>
                                    </div>
                                </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="topic_label">Topic-1</label>
                                <input class="form-control" name="form_1_topic_1" type="text" value="" placeholder="Topic-1">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Text Books / Reference</label>
                                <input class="form-control" name="text_book" type="text" value="" placeholder="Text Books / Reference">
                            </div>
                        </div>
                        <div class="col-md-2 col-10">
                             <div class="form-group">
                                 <label>Delivery Methods</label>
                                 <input class="form-control" name="delivery_method" type="text" value="" placeholder="Delivery Methods">
                             </div>
                        </div>
                        <div class="col-md-1 col-2">
                            <div class="form-group text-center">
                                <label>Remove</label>
                                <div>
                                    <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                class="fa fa-times-circle" onclick="remove_element(this)">
                                            </i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div class="col-md-2 col-12">
                                    <div class="form-group">
                                         <label>Proposed Date</label>
                                        <input class="form-control" name="proposed_date" type="date" value=""
                                            placeholder="Proposed Date" onchange="date_check(this)" required>
                                    </div>
                                </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="topic_label">Topic-2</label>
                                <input class="form-control" name="form_2_topic_2" type="text" value="" placeholder="Topic-2">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label>Text Books / Reference</label>
                                        <input class="form-control" name="text_book" type="text" value="" placeholder="Text Books / Reference">
                                    </div>
                        </div>
                        <div class="col-md-2 col-10">
                            <div class="form-group">
                                <label>Delivery Methods</label>
                                <input class="form-control" name="delivery_method" type="text" value="" placeholder="Delivery Methods">
                            </div>
                        </div>
                        <div class="col-md-1 col-2">
                            <div class="form-group text-center">
                                <label>Remove</label>
                                <div>
                                    <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                class="fa fa-times-circle" onclick="remove_element(this)">
                                            </i>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row gutters">
                        <div class="col-md-2 col-12">
                                    <div class="form-group">
                                         <label>Proposed Date</label>
                                        <input class="form-control" name="proposed_date" type="date" value=""
                                            placeholder="Proposed Date" onchange="date_check(this)" required>
                                    </div>
                                </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="topic_label">Topic-3</label>
                                <input class="form-control" name="form_3_topic_3" type="text" value="" placeholder="Topic-3">
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label>Text Books / Reference</label>
                                        <input class="form-control" name="text_book" type="text" value="" placeholder="Text Books / Reference">
                                    </div>
                        </div>
                        <div class="col-md-2 col-10">
                            <div class="form-group">
                                <label>Delivery Methods</label>
                                <input class="form-control" name="delivery_method" type="text" value="" placeholder="Delivery Methods">
                            </div>
                        </div>
                        <div class="col-md-1 col-2">
                            <div class="form-group text-center">
                                <label>Remove</label>
                                <div>
                                    <i style="color:red;font-size:1.5rem;cursor: pointer;"
                                                class="fa fa-times-circle" onclick="remove_element(this)">
                                            </i>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <button class="enroll_generate_bn" value="form_${len + 1}" onclick="add_topic(this)"><i
                        class="right fa fa-fw fa-angle-down"></i>Add Topic</button>
            </div>
         </div>`);
        }

        function remove_unit(element) {
            var choose_unit, num, choose_label, next_element;

            let selected_element = $("#" + element);


            Swal.fire({
                title: "Are You Sure?",
                text: "Do you want to remove the unit ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Remove it!",
                cancelButtonText: "No, Cancel!",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {

                    $(selected_element).remove();

                    let units = $(".add_color");
                    let unit_count = units.length;
                    // console.log(unit_count)
                    if (unit_count > 0) {
                        for (let a = 0; a < unit_count; a++) {
                            choose_unit = $(units).eq(a);
                            num = a + 1;
                            choose_label = $(choose_unit).find('.unit_label').eq(0);
                            next_element = $(choose_label).next();

                            $(choose_label).html('Unit-' + num);
                            $(next_element).attr('placeholder', 'Unit-' + num + '[Title]');
                        }
                    }

                    Swal.fire(
                        "Removed!",
                        "Unit has been removed.",
                        "success"
                    )
                    // result.dismiss can be "cancel", "overlay",
                    // "close", and "timer"
                } else if (result.dismiss === "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Unit not removed",
                        "error"
                    )
                }
            });

        }

        function remove_element(element) {

            let selected_element = $(element).parents('div.gutters');



            var choose_unit, num, choose_label, next_element;

            Swal.fire({
                title: "Are You Sure?",
                text: "Do you want to remove the topic ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Remove it!",
                cancelButtonText: "No, Cancel!",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    let selected_unit = $(element).parents('div.add_color');
                    selected_element.remove();
                    let select_topics = $(selected_unit).find('.gutters');

                    let topics_len = $(select_topics).length;
                    // console.log(topics_len)

                    if (topics_len > 0) {
                        for (let a = 0; a < topics_len; a++) {
                            choose_topic = $(select_topics).eq(a);
                            num = a + 1;
                            choose_label = $(choose_topic).find('.topic_label').eq(0);
                            next_element = $(choose_label).next();

                            $(choose_label).html('Topic-' + num);
                            $(next_element).attr('placeholder', 'Topic-' + num);
                        }
                    }

                    Swal.fire(
                        "Removed!",
                        "Topic has been removed.",
                        "success"
                    )
                    // result.dismiss can be "cancel", "overlay",
                    // "close", and "timer"
                } else if (result.dismiss === "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Topic not removed",
                        "error"
                    )
                }
            });

        }

        function save() {
            // console.log($("form").length - 1);
            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want To Save The Lesson Plan ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    let len = $("form").length - 1;

                    let class_name = $("#class_name").val()
                    let subject = $("#subject").val()
                    let form_data = [];
                    let data = {
                        'class': class_name,
                        'subject': subject,
                        'form': form_data
                    };

                    for (let i = 0; i < len; i++) {
                        form_data.push($($("form")[i]).serializeArray());
                    }
                    $.ajax({
                        url: '{{ route('admin.staff-subjects.lesson-plan.save') }}',
                        type: 'POST',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // console.log(response)
                            if (response.status == true) {

                                Swal.fire(
                                    "Success",
                                    "Lesson Plan Saved..",
                                    "success"
                                );
                                window.location.href =
                                    '{{ route('admin.staff-subjects.lesson-plan') }}';
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            if (jqXHR) {
                                var errors = jqXHR.responseJSON.errors;
                                var errorMessage = errors[Object.keys(errors)[0]][0];
                                Swal.fire('', errorMessage, 'error');

                            }
                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Lesson Plan Save Process Cancelled",
                        "error"
                    );
                }
            });
        }

        function submit() {

            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want To Submit The Lesson Plan ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    let len = $("form").length - 1;

                    let class_name = $("#class_name").val()
                    let subject = $("#subject").val()

                    let form_data = [];

                    let data = {
                        'class': class_name,
                        'subject': subject,
                        'form': form_data
                    };

                    for (let i = 0; i < len; i++) {
                        form_data.push($($("form")[i]).serializeArray());
                    }
                    // console.log(data)

                    $.ajax({
                        url: '{{ route('admin.staff-subjects.lesson-plan.submit') }}',
                        type: 'POST',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // console.log(response)
                            if (response.status == true) {

                                Swal.fire(
                                    "Success",
                                    "Lesson Plan Submitted..",
                                    "success"
                                );
                                window.location.href =
                                    '{{ route('admin.staff-subjects.lesson-plan') }}';
                            }
                        }
                    })
                } else if (result.dismiss == "cancel") {
                    Swal.fire(
                        "Cancelled",
                        "Lesson Plan Submission Cancelled",
                        "error"
                    );
                }
            });
        }
    </script>
@endsection
