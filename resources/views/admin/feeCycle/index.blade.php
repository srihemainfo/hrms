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
        <div id="customs-options" class="text-center mt-4" style="display: none;">
            <p>Select Number of Fee Cycle to be Created</p>
            <div class="radio-group">
                <label><input type="radio" name="numInputs" value="2"> 2</label>
                <label><input type="radio" name="numInputs" value="3"> 3</label>
                <label><input type="radio" name="numInputs" value="4"> 4</label>
                <label><input type="radio" name="numInputs" value="5"> 5</label>
                <label><input type="radio" name="numInputs" value="6"> 6</label>
            </div>
            <div id="input-container" class="row justify-content-center mt-4"></div>
            <button id="submit-customs" class="btn btn-outline-success mt-3 mb-3" style="display: none;">Submit</button>
        </div>
        <div id="output" class="col-md-6 text-center mx-auto"></div>
        <div class="secondLoader"></div>
    </div>
    <style>
        #output {
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .radio-group {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            gap: 20px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            font-size: 1.2em;
        }

        .radio-group input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #5f1f61;
        }

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
        function checkCheckboxes() {
            var anyChecked = $('.toggle:checked').length > 0;
            if (anyChecked) {
                $('.toggle').prop('disabled', true);
            } else {
                $('.toggle').prop('disabled', false);
            }
        }

        function checkInitialState() {
            if ($('#CustomsWise').is(':checked')) {
                $('.secondLoader').show();
                $.ajax({

                    url: '{{ route('admin.fee-cycle.customsnames') }}',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('.secondLoader').hide();

                        if (response.status === true) {
                            var outputDiv = $('#output');
                            outputDiv.empty();

                            // Create the table structure
                            var table = $(
                                '<table class="table table-bordered table-striped text-center"></table>');
                            var thead = $('<thead><tr><th>Fee Name</th></tr></thead>');
                            var tbody = $('<tbody></tbody>');

                            response.data.forEach(function(item) {
                                var row = $('<tr></tr>');
                                var cell = $('<td></td>').text(item);
                                row.append(cell);
                                tbody.append(row);
                            });

                            table.append(thead);
                            table.append(tbody);
                            outputDiv.append(table);
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },


                    error: function(jqXHR, textStatus, errorThrown) {
                        $('.secondLoader').hide(); // Hide loader on error
                        if (jqXHR.status) {
                            if (jqXHR.status === 500) {
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
            }
        }

        checkCheckboxes();
        checkInitialState();

        $('.toggle').change(function() {
            if ($(this).is(':checked')) {
                $('.toggle').prop('disabled', true);

                $('.secondLoader').show();
                $('.toggle').not(this).prop('checked', false);
                var selecetedCycle = $(this).attr('id')

                if (selecetedCycle == 'SemesterWise' || selecetedCycle == 'YearlyWise') {

                    $("#customs-options").hide();
                    $('#input-container').empty();
                    $('#submit-customs').hide();

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

                } else if (selecetedCycle == 'CustomsWise') {

                    $('.secondLoader').hide();
                    $("#customs-options").show()
                    $('input[name="numInputs"]').prop('checked', false);

                    $('input[name="numInputs"]').change(function() {
                        $('#submit-customs').show();

                        var numInputs = $(this).val();
                        var inputContainer = $('#input-container');
                        inputContainer.empty();
                        for (var i = 0; i < numInputs; i++) {
                            inputContainer.append(
                                '<div class="col-md-10 mb-2">' +
                                '<input type="text" class="form-control" placeholder="Example : Term or Installment' +
                                (i + 1) + '">' +
                                '</div>'
                            );
                        }
                        inputContainer.addClass('row');
                    });

                    $('#submit-customs').click(function() {
                        $('.secondLoader').show();

                        var inputValues = [];
                        $('#input-container input').each(function() {
                            inputValues.push($(this).val());
                        });

                        $.ajax({
                            url: '{{ route('admin.fee-cycle.customs') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'selecetedCycle': selecetedCycle,
                                'inputValues': inputValues
                            },
                            success: function(response) {
                                $('.secondLoader').hide();
                                location.reload();

                                let status = response.status;
                                if (status == true) {
                                    Swal.fire('', response.data, 'success');

                                } else {
                                    Swal.fire('', response.data, 'error');

                                }
                                $('.secondLoader').hide();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $('.secondLoader').hide();

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
                                    Swal.fire('', 'Request Failed With Status: ' + jqXHR
                                        .statusText,
                                        "error");
                                }

                                $('.secondLoader').hide();
                            }
                        })

                    });



                } else {
                    $("#customs-options").hide();
                    $('#submit-customs').hide();
                }

            }
        });
    </script>
@endsection

