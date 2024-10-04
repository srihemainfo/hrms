@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header text-center" style="font-weight: bold;">
            Fee Cycle Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <p>Yearly Wise Fees Creation</p>
                    <label class="switch">
                        <input type="checkbox" class="toggle" id="YearlyWise"
                            {{ $feeCycles->isNotEmpty() && $feeCycles->first()->cycle_name === 'YearlyWise' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-md-4 text-center">
                    <p>Semester Wise Fees Creation</p>
                    <label class="switch">
                        <input type="checkbox" class="toggle" id="SemesterWise"
                            {{ $feeCycles->isNotEmpty() && $feeCycles->first()->cycle_name === 'SemesterWise' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-md-4 text-center">
                    <p>Customs Fees Creation</p>
                    <label class="switch">
                        <input type="checkbox" class="toggle" id="CustomsWise"
                            {{ $feeCycles->isNotEmpty() && $feeCycles->first()->cycle_name === 'CustomsWise' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="secondLoader"></div>
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;

        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #5f1f61;
        }

        input:checked+.slider:before {
            transform: translateX(22px);
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script>
        $('.toggle').change(function() {
            if ($(this).is(':checked')) {
                $('.secondLoader').show();
                $('.toggle').not(this).prop('checked', false);
                var selecetedCycle = $(this).attr('id')
                // console.log(selecetedCycle);

                $.ajax({
                    url: '{{ route('admin.fee-cycle.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'selecetedCycle': selecetedCycle,
                    },
                    success: function(response) {
                        let status = response.status;
                        if (status == true) {
                            Swal.fire('', response.data, 'success');

                        } else {
                            Swal.fire('', response.data, 'error');

                        }
                        $('.secondLoader').hide();
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

                        $('.secondLoader').hide();
                    }
                })


            }
        });
    </script>
@endsection
