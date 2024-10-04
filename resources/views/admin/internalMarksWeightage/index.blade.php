@extends('layouts.admin')
@section('content')
<style>
    .select2-container {
        width: 100% !important;
        margin: auto;
    }
</style>
<a class="btn btn-success mb-2" href="{{ route('admin.internal-weightage.create') }}">
    Add Internal Weightage
</a>
<div class="card">
    <div class="card-header">
        Internal Weightage
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <label class="required" for="regulation">Regulation</label>
                <select class="form-control select2" name="regulation" id="regulation">
                    <option value="">Select Regulation</option>
                    @foreach ($regulations as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <label class="required" for="ay">Academic Year</label>
                <select class="form-control select2" name="ay" id="ay">
                    <option value="">Select Academic Year</option>
                    @foreach ($ays as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <label for="subject_type" class="required">Subject Type</label>
                <select class="form-control select2" name="subject_type" id="subject_type">
                    <option value="">Select Subject Type</option>
                    <option value="THEORY">THEORY</option>
                    <option value="LABORATORY">LABORATORY</option>
                    <option value="PROJECT">PROJECT</option>
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <label for="sem" class="required">Semester</label>
                <select class="form-control select2" name="sem" id="sem">
                    <option value="">Select Semester</option>
                    @foreach ($sem as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <button class="enroll_generate_bn" style="margin-top:32px">Search</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Internal Weightage List
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Regulation</th>
                    <th>AY</th>
                    <th>Sub Type</th>
                    <th>Semester</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @if (count($getData) > 0)
              
                @foreach ($getData as $i => $data)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $data->getRegulation ? $data->getRegulation->name : '' }}</td>
                    <td>{{ $data->getAy ? $data->getAy->name : '' }}</td>
                    <td>{{ $data->subject_type }}</td>
                    <td>{{ $data->semester }}</td>
                    <td>{{ $data->total }}</td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="{{ route('admin.internal-weightage.show', $data->id) }}" target="_blank">View</a>
                        @if($data->status == 0)
                        <a class="btn btn-xs btn-info" href="{{ route('admin.internal-weightage.edit', $data->id) }}" target="_blank">Edit</a>
                        <form action="{{ route('admin.internal-weightage.destroy') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                        </form>
                        
                        @endif

                    </td>
                </tr>
                @endforeach

                @else
                <tr>
                    <td colspan="7"> No Data Available...</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // function getSubjectTypes() {
    //     if ($("#regulation").val() != '') {
    //         $("#subject_type").html(`<option value="">Loading...</option>`);
    //         $.ajax({
    //             url: "{{ route('admin.internal-weightage.subject-types') }}",
    //             method: "POST",
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             data: {
    //                 'regulation': $("#regulation").val()
    //             },
    //             success: function(response) {
    //                 console.log(response)
    //                 let status = response.status;
    //                 let data = response.data;
    //                 let subjectTypes = '';
    //                 if (status == true) {
    //                     let data_len = data.length;
    //                     console.log(data_len)
    //                     subjectTypes += `<option value="">Select Subject Type</option>`;
    //                     if (data_len > 0) {
    //                         $.each(data, function(index, value) {
    //                             subjectTypes +=
    //                                 `<option value="${value.id}">${value.name}</option>`;
    //                         });
    //                     }
    //                 } else {
    //                     Swal.fire('', data, 'error');
    //                 }
    //                 $("#subject_type").html(subjectTypes);
    //             }
    //         })
    //     } else {
    //         Swal.fire('', 'Please Select Regulation', 'error');
    //         return false;
    //     }
    // }
</script>
@endsection