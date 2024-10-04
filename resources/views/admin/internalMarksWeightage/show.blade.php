@extends('layouts.admin')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            margin: auto;
        }
    </style>
    <a class="btn btn-default mb-2" href="{{ route('admin.internal-weightage.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    <div class="card">
        <div class="card-header">
            Internal Weightage
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="required" for="regulation">Regulation</label>
                    <select class="form-control select2" name="regulation" id="regulation">
                        @php
                            $reg = $getData->getRegulation ? $getData->getRegulation->id : '';
                        @endphp

                        @foreach ($regulations as $id => $entry)
                            @if ($reg == $id)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endif 
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                    <label class="required" for="ay">Academic Year</label>
                    <select class="form-control select2" name="ay" id="ay">
                        @php
                            $ay = $getData->getAy ? $getData->getAy->id : '';
                        @endphp
                        @foreach ($ays as $id => $entry)
                            @if ($ay == $id)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="subject_type" class="required">Subject Type</label>
                    <select class="form-control select2" name="subject_type" id="subject_type">
                        <option value="{{ $getData->subject_type }}">{{ $getData->subject_type }}</option>
                    </select>
                </div>
                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label for="category" class="required">Category</label>
                    <select name="category" id="category" class="form-control select2">
                        <option value="{{ $getData->category }}">{{ $getData->category }}</option>
                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row ">
                        <div class="col">
                            <table class="table table-bordered table-striped table-hover text-center ">
                                <thead>
                                    <tr>
                                        <th>Internal Component</th>
                                        <th>Weightage</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @if (count($weightage) > 0)
                                        @foreach ($weightage as $data)
                                            <tr>
                                                <td>{{ $data->exam_title }}</td>
                                                <td>{{ $data->internal_weightage }}</td>
                                            </tr>
                                            @php
                                                $total += (int) $data->internal_weightage;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot id="tfoot">
                                    <tr>
                                        <th>Total Weightage</th>
                                        <th>{{ $total }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
