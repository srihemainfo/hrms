@extends('layouts.admin')
@section('content')

    <style>
        .select2-results__option {
            color: rgb(0, 6, 43) !important;
        }

        .select2-results__option:first-child {
            color: black !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            color: rgb(0, 0, 0) !important;
        }

        .staff_label {
            width: 98%;
            margin: auto;
            margin-bottom: 3px;
            border-radius: 5px;
            box-sizing: border-box;
            box-shadow: 0px 1px 3px grey;
            position: relative;
        }


        .inter {
            position: absolute;
            right: 4%;
            top: 6%;
            background-color: transparent;
            color: red;
            font-size: 0.8rem;
            cursor: pointer;
        }
    </style>
    <div style="margin-bottom:10px;">
        <a class="btn btn-default" href="{{ route('admin.class-time-table.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row cutters">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="course" class="required"> Course</b>
                        <select class="form-control select2" name="course" id="course" onchange="get_section(this)">
                            @if (count($course) > 0)
                                @foreach ($course as $id => $entry)
                                    <option value="{{ $id }}">{{ $entry }}</option>
                                @endforeach
                            @else
                                <option value=""></option>
                            @endif
                        </select>

                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <b for="academic_year" class="required"> Academic Year</b>
                        <select class="form-control select2" name="academic_year" id="academic_year">
                            @foreach ($academic_years as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group">
                        <b for="semester" class="required"> Semester</b>
                        <select class="form-control select2" name="semester" id="semester">
                            <option value="">Select Semester</option>
                            @foreach ($semester as $entry)
                                <option value="{{ $entry }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                        {{-- <span id="class_checker" class="text-danger"></span> --}}
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                    <div class="form-group">
                        <b for="section" class="required"> Section</b>
                        <select class="form-control select2" name="section" id="section">
                            <option value="">Select Section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                        {{-- <span id="class_checker" class="text-danger"></span> --}}
                        <input type="hidden" name="class_name" id="class_name" value="">
                    </div>
                </div>
                {{-- <div class="col-1"></div> --}}
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12">
                    <div class="form-group" style="padding-top: 1.5rem;">
                        <button type="submit" id="submit" name="submit" class="enroll_generate_bn"
                            onclick="go()">Go</button>
                            <span id="loadig_spin" style="display:none;font-weight:bold;" class="text-success">Processing...</span>
                    </div>
                </div>
            </div>
            <b> <span style="color:red;"> Note : </span> Make Sure You Have Alloted the Subjects Which is Related to the
                Class</b>
        </div>
    </div>
    {{-- <div id="loadig_spin" style="display:none;">
        <div class="loader">
            <div class="spinner-border text-primary"></div>
        </div>
    </div> --}}
    <div class="card" id="table-card" style="display:none;max-width:100%;overflow-x:auto;">
        <div class="card-body" style="font-size:0.75rem;min-width:1100px;">
            <div class="table table-bordered text-center">
                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>DAY</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>MON DAY</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>TUES DAY</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>WEDNES DAY</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>THURS DAY</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b>FRI DAY</b>
                        </div>
                    </div>
                </div>


                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">1</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_one">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_one')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_one">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_one')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_one">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_one')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_one">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_one')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_one">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_one')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">2</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_two">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_two')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_two">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_two')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_two">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_two')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_two">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_two')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_two">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_two')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">3</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_three">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_three')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_three">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_three')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_three">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_three')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_three">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_three')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_three">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_three')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">4</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_four">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_four')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_four">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_four')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_four">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_four')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_four">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_four')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_four">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_four')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">5</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_five">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_five')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_five">

                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_five')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_five">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_five')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_five">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_five')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_five">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_five')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">6</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_six">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_six')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_six">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_six')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_six">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_six')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_six">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_six')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_six">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_six')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">7</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_seven">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_seven')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_seven">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_seven')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_seven">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_seven')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_seven">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_seven')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_seven">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_seven')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex;width:100%;">
                    <div style="padding-top:1rem;width:5%;" class="table-bordered">
                        <div class="form-group text-center">
                            <b style="display:block;padding-top:10%;">8</b>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="monday_eight">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('monday_eight')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="tuesday_eight">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('tuesday_eight')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="wednesday_eight">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('wednesday_eight')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="thursday_eight">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('thursday_eight')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                    <div style="padding-top:1rem;width:19%;" class="table-bordered">
                        <div class="form-group" id="friday_eight">
                        </div>
                        <div class="form-group">
                            <span class="btn btn-primary btn-xs adder" onclick="add('friday_eight')">ADD <i
                                    style="margin-left:1px;background-color:transparent;font-size:0.6rem;cursor: pointer;"
                                    class="fa fa-plus-circle">
                                </i></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row gutters">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="text-right" style="padding-top:1rem;">
                        <button type="submit" id="submit" name="submit" class="btn btn-primary"
                            onclick="submit()">Submit</button>
                        <span id="submit_span" style="display:none;font-weight:bold;" class="text-success">Processing;...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="periodModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary">Select Subject & Staff</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row" id="selector_div">
                                <div class='col-md-12'>
                                    <select class="form-control select2" style="width:100%;" name="selector"
                                        id="selector" onchange="open_divs(this)">
                                        <option value="">Select Program</option>
                                        <option value="lecture">Lecture</option>
                                        <option value="library">Library</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row' id="lecture">
                        <div class='col-md-12'>
                            <div class="form-group">
                                <input type="hidden" value="" name="period" id="period">
                                <input type="hidden" value="" name="column" id="column">
                                <input type="hidden" value="" name="day" id="day">
                                <input type="hidden" value="" name="selected_subject" id="selected_subject">
                                <input type="hidden" value="" name="selected_staff" id="selected_staff">
                                <b for="" style="display:block;">Select Subject</b>
                                <select style="width:100%;"class="form-control select2 subject" name="subject_selecter"
                                    id="subject_selecter">
                                    <option value="">Select Subject</option>
                                </select>
                                <div class="text-center">
                                    <span id="subject_span" style="font-weight:bold;display:block;color:rgb(255, 0, 13);">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <b for="" style="display:block;">Select Staff</b>
                                <select style="width:100%;" class="form-control select2" name="staff_selecter"
                                    id="staff_selecter" onchange="check_staff(this)">
                                    <option value="">Select Staff</option>
                                    @foreach ($teaching_staffs as $staff)
                                        <option value="{{ $staff->user_name_id }}">{{ $staff->name }}
                                            ({{ $staff->StaffCode }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-center">
                                    <span id="staff_span" style="display:block:">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="library">
                        <div class='col-md-12'>
                            <div class="form-group">
                                <label for="library_staff">Select Staff</label>
                                <select class="form-control select2" name="library_staff" id="library_staff">
                                    <option value="">Select Staff</option>
                                    @foreach ($teaching_staffs as $staff)
                                        <option value="{{ $staff->user_name_id }}">{{ $staff->name }}
                                            ({{ $staff->StaffCode }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-center">
                                    <span id="library_span" style="font-weight:bold;display:block;color:rgb(255, 0, 13);">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="program_saver" class="btn btn-success" onclick="save()">Add</button>
                </div>
            </div>

        </div>
    </div>

    {{-- <div class="modal fade" id="studentModal" role="dialog">
        <div class="modal-dialog  modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Student List</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg-primary" id="list_header">

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-primary">
                            <div class="row text-center">
                                <div class="col-1">S No</div>
                                <div class="col-5">Name</div>
                                <div class="col-4">Register No</div>
                                <div class="col-2">Allocate</div>
                            </div>
                        </div>
                        <div class="card-body" id="stu_list">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="student_allot()">Save Allocation</button>
                </div>
            </div>

        </div>
    </div> --}}

@endsection
@section('scripts')
    @parent
    <script>
        let subject_div = document.getElementsByClassName("subject");
        let allot_div;

        window.onload = function() {
            $("#staff_selecter").select2();
            $("#subject_selecter").select2();
        }


        function get_section(element) {
            let course_id = element.value;
            // console.log(course_id)
            if (course_id != '') {
                $.ajax({
                    url: '{{ route('admin.get_all_section') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'course_id': course_id,

                    },
                    success: function(response) {
                        // console.log(response)

                        let data = response.data;
                        let data_len = data.length;
                        let got_sections = `<option>Select Section</option>`;
                        if (data_len > 0) {
                            for (let a = 0; a < data_len; a++) {
                                got_sections +=
                                    `<option value="${data[a].section}">${data[a].section}</option>`;
                            }
                        }
                        $("#section").html(got_sections);
                        $("select").select2();
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
        }

        function remove(element) {

            let id = $(element).attr('id');

            let parent = $(element).parent();

            $(parent).remove();

        }


        function go() {

            $("#course").select2();
            $("#academic_year").select2();
            $("#semester").select2();
            $("#section").select2();

            let course = $("#course").val();
            let ay = $("#academic_year").val();
            let semester = $("#semester").val();
            let section = $("#section").val();

            if (course != '' && ay != '' && semester != '' && section != '') {


                $("#loadig_spin").show();
                $("#submit").hide();
                $("#table-card").hide();

                let data = {
                    'course': course,
                    'ay': ay,
                    'semester': semester,
                    'section': section
                };

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.get-subjects.index') }}',
                    data: data,
                    success: function(response) {
                        // console.log(response);
                        $("#loadig_spin").hide();
                        $("#submit").show();

                        if (response.subjects == 'Calendar Fail') {

                            Swal.fire('','The Calendar Not Created Yet...','error');

                        } else if (response.subjects == 'Class Fail') {

                            Swal.fire('','The Class Not Allocated Yet...','error');

                        } else if (response.subjects == 'Fail') {

                            Swal.fire('','The Class Time Table Already Created','error');

                        } else {

                            $("#table-card").show();

                            $("#course").select2();
                            $("#academic_year").select2();
                            $("#semester").select2();
                            $("#section").select2();
                            let sub = ' <option value="">Select Subject</option>';
                            let got_subjects = response.subjects;
                            for (let i = 0; i < got_subjects.length; i++) {
                                sub +=
                                    `<option style="color:blue;" value="${got_subjects[i].id}"> ${got_subjects[i].name}  (${got_subjects[i].subject_code})</option>`;
                            }

                            $(".subject").html(sub);

                        }


                        if (response.class_name != null) {
                            $("#class_name").val(response.class_name);
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loadig_spin").hide();
                        $("#submit").show();
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
                // console.log(semester, course)

            } else {
                Swal.fire('','Please Provide the Required Datas..','error');
            }
        }

        function add(element) {
            $("#staff_selecter").val('');
            $("#subject_selecter").val('');
            $("#staff_selecter").select2();
            $("#subject_selecter").select2();
            $("#subject_span").html("");
            $("#staff_span").html("");

            $("#column").val(element);

            let split = element.split("_");
            // console.log(split)

            $("#day").val(split[0]);

            let period;

            if (split[1] == 'one') {
                period = 1;
            } else if (split[1] == 'two') {
                period = 2;
            } else if (split[1] == 'three') {
                period = 3;
            } else if (split[1] == 'four') {
                period = 4;
            } else if (split[1] == 'five') {
                period = 5;
            } else if (split[1] == 'six') {
                period = 6;
            } else if (split[1] == 'seven') {
                period = 7;
            } else if (split[1] == 'eight') {
                period = 8;
            }

            $("#period").val(period);

            $("#lecture").hide();
            $("#library").hide();
            $("#program_saver").hide();
            $("#selecter_div").show();
            $("#selector").val('');
            $("select").select2();
            $("#periodModal").modal();
        }


        function open_divs(element) {
            let div_id = element.value;
            $("#program_saver").hide();
            if (div_id == 'lecture') {
                $("#lecture").show();
                $("#library").hide();
                $("select").select2();
                $("#program_saver").show();
            }
            if (div_id == 'library') {
                $("#library").show();
                $("#lecture").hide();
                $("select").select2();
                $("#program_saver").show();
            }
        }

        function check_staff(element) {

            let selected_staff = element.value;
            let day = $("#day").val();
            let period = $("#period").val();
            let class_name = $("#class_name").val();
            let selecter = $("#selector").val();
            let get_id = $(element).attr('id');
            let span = `<span class="text-primary">Checking Availability...</span>`;
            console.log(get_id)
            if (get_id == 'library_staff') {
                $("#library_span").html(span)
            } else {
                $("#staff_span").html(span);
            }
            let subject = null;
            if (selecter == 'lecture') {
                subject = $("#subject_selecter").val();
            } else {
                if (selecter == 'library') {
                    subject = 'library';
                }
            }
            let data = {
                'selected_staff': selected_staff,
                'column': period,
                'day': day,
                'class_name': class_name,
                'subject': subject
            };

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.check-staff-period.check') }}',
                data: {
                    data: data
                },
                success: function(response) {

                    if (response.status == false) {
                        span =
                            `<span style="font-weight:bold;color:rgb(255, 0, 13);">Choosen Staff Already Assigned</span>`;
                        if (get_id == 'library_staff') {
                            $("#library_span").html(span)
                        } else {
                            $("#staff_span").html(span);
                        }

                        element.innerHTML = `  <option value="">Select Staff</option>
                    @foreach ($teaching_staffs as $staff)
                        <option value="{{ $staff->user_name_id }}">{{ $staff->name }} <span style="font-size:0.5rem;">({{ $staff->StaffCode }})</span></option>
                    @endforeach`;
                    } else {
                        if (get_id == 'library_staff') {
                            $("#library_span").html('')
                        } else {
                            $("#staff_span").html('');
                        }
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (get_id == 'library_staff') {
                        $("#library_span").html('')
                    } else {
                        $("#staff_span").html('');
                    }
                    $("#loadig_spin").hide();
                    $("#submit").show();
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

            // console.log(data)
        }

        function save() {

            let column = $("#column").val();
            let day_name = $("#day").val();
            let day = day_name.toUpperCase();
            let period = $("#period").val();
            let subject_selecter;
            let staff_selecter;

            let program_type = $("#selector").val();
            if (program_type == 'lecture') {
                subject_selecter = $("#subject_selecter").val();
                staff_selecter = $("#staff_selecter").val();
                $("#staff_span").html('');
                $("#subject_span").html('');

                if (subject_selecter == '') {
                    $("#subject_span").html("Please Choose the Subject");

                } else if (staff_selecter == '') {
                    $("#staff_span").html("Please Choose the Staff");

                }
            } else if (program_type == 'library') {
                subject_selecter = 'Library';
                staff_selecter = $("#library_staff").val();

                if (staff_selecter == '') {
                    $("#library_span").html("Please Choose the Library Staff");

                } else {
                    $("#library_span").html('');
                }

            }

            if (staff_selecter != '' && subject_selecter != '' && staff_selecter != null && subject_selecter !=
                null) {
                $("#staff_span").html('');
                $("#subject_span").html('');
                // console.log(staff_selecter, subject_selecter)
                $.ajax({
                    url: '{{ route('admin.class-time-table.get_staff_and_subject') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'staff': staff_selecter,
                        'subject': subject_selecter
                    },
                    success: function(response) {

                        if (response.staff != '' && response.subject != '') {
                            let staff = response.staff;
                            let subject = response.subject;

                            let staff_name = staff['name'];
                            let staff_code = staff['StaffCode'];
                            let user_name_id = staff['user_name_id'];

                            let subject_name;
                            let subject_code;
                            let subject_id;
                            let sub_label;

                            if (program_type == 'lecture') {
                                subject_name = subject['subjects']['name'];
                                subject_code = '(' + subject['subjects']['subject_code'] + ')';
                                subject_id = subject['subjects']['id'];
                                sub_label = 'Subject';
                            }

                            if (program_type == 'library') {
                                subject_name = '';
                                subject_code = '';
                                subject_id = subject;
                                sub_label = 'Library';
                            }

                            let div = `
                                  <div class="staff_label"><i class="fa fa-times inter" onclick="remove(this)" id="added"></i>
                                      <form class='period_form'>
                                          <div><b>${sub_label}</b></div>
                                          <input type="hidden" name="day" value="${day}">
                                          <input type="hidden" name="id" value="">
                                          <input type="hidden" name="period" value="${period}">
                                          <input type="hidden" name="subject_id" value="${subject_id}">
                                          <input type="hidden" name="user_name_id" value="${user_name_id}">
                                          <b class="text-primary">${subject_name}  ${subject_code}</b>
                                          <div><b>Staff</b></div>
                                          <b class="text-primary">${staff_name} (${staff_code})</b>
                                      </form>
                                  </div>`;


                            $("#" + column).append(div);

                            $("#periodModal").modal('hide');

                        } else {
                            Swal.fire('','Technical Error','error');
                        }
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

        }

        function submit() {
            let class_name = $("#class_name").val();
            let form_len = $('.period_form').length;
            let forms = $(".period_form");

            let form_data = [];

            for (let a = 0; a < form_len; a++) {
                let form = forms[a];
                let serialize = $(form).serializeArray();
                form_data.push(serialize);
            }
            $("#submit_span").show();
            $("#submit").hide();
            // console.log(form_data);
            if (form_len > 0) {
                $.ajax({
                    url: '{{ route('admin.class-time-table.store') }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'class': class_name,
                        'data': form_data
                    },
                    success: function(response) {
                        // console.log(response)
                        if (response.status) {
                            Swal.fire('','Class Time Table Submitted For Approval','success');

                            $("#loadig_spin").hide();
                            $("#submit").show();
                            $("#submit_span").hide();
                            $("#table-card").hide();

                            let day_array = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                            let period_array = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight'];

                            for (let i = 0; i < day_array.length; i++) {
                                for (let j = 0; j < period_array.length; j++) {
                                    $("#" + day_array[i] + "_" + period_array[j]).html('');
                                }
                            }
                        }
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

        }
    </script>
@endsection
