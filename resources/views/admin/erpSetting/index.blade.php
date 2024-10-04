@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row gutters">

                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="col_type" class="required">College Type</label>
                    <select name="col_type" id="col_type" class="form-control select2">
                        <option value="">Select College</option>
                        <option value="ARTS">ARTS</option>
                        <option value="ENGINEERING">ENGINEERING</option>
                    </select>
                    <span id="col_type_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <label for="degree" class="required">Degree Holds</label>
                    <select name="degree" id="degree" class="form-control select2" multiple>
                        <option value="">Select Degree Type</option>
                        <option value="UG">UG</option>
                        <option value="PG">PG</option>
                        {{-- <option value="UG,PG">UG,PG</option> --}}
                    </select>
                    <span id="degree_span" class="text-danger text-center" style="display:none;font-size:0.9rem;"></span>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 form-group">
                    <div id="save_div" style="margin-top: 30px;">
                        <button type="button" id="save_btn" class="btn btn-outline-success"
                            onclick="saveErp()">Save</button>
                    </div>
                    <div id="loading_div" style="display: none;">
                        <span class="theLoader"></span>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        function saveErp() {
            $("#loading_div").hide();
            if ($("#col_type").val() == '') {
                $("#col_type_span").html(`Collage Type Is Required.`);
                $("#col_type_span").show();
                $("#degree_span").hide();
            } else if ($("#degree").val() == '') {
                $("#degree_span").html(`Degree Type Is Required.`);
                $("#degree_span").show();
                $("#col_type_span").hide();
            } else {
                $("#save_div").hide();
                $("#col_type_span").hide();
                $("#degree_span").hide();
                $("#loading_div").show();
                let degree = $("#degree").val();
                let college = $("#col_type").val();
                $.ajax({
                    url: "{{ route('admin.erp-setting.store') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'degree': degree,
                        'college': college
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#toolsCourseModel").modal('hide');
                        callAjax();
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
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })
            }
        }
    </script>
@endsection
