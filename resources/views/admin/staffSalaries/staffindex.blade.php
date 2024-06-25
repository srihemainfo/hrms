<div class="container">
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <div class="row gutters">
        {{-- {{ dd($staff_edit); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.staff-salaries.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Salary Details</h6><br>

                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="basic_pay">Basic Pay</label>
                                    <input type="number" class="form-control" id="myNumberInput1" name="basic_pay"
                                        placeholder="Enter Basic Pay" value="{{ $staff_edit->basic_pay }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                {{-- <div class="form-group">
                                    <label for="TotalSalary">Total Salary</label>
                                    <input type="number" class="form-control" id="basic_pay" name="TotalSalary"
                                        placeholder="Enter Total Salary"  value="{{ $staff_edit-> }}">
                                </div> --}}
                                <div class="form-group">
                                    <label for="phd_allowance">Ph.D. Allowance</label>
                                    <input type="number" class="form-control" id="myNumberInput2" name="phd_allowance"
                                        placeholder="Enter Ph.D. Allowance" value="{{ $staff_edit->phd_allowance }}">
                                </div>

                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="agp">AGP</label>
                                    <input type="number" class="form-control" id="myNumberInput3" name="agp"
                                        placeholder="Enter AGP" value="{{ $staff_edit->agp }}">
                                </div>
                                <div class="form-group">
                                    <label for="special_pay">Special Pay</label>
                                    <input type="number" class="form-control" name="special_pay" id="myNumberInput4"
                                        placeholder="Enter Special Pay" value="{{ $staff_edit->special_pay }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="hra">HRA(%)</label>
                                    <input type="number" class="form-control" name="hra"
                                        placeholder="Enter HRA In Percentage" value="{{ $staff_edit->hra }}"
                                        onkeyup="check_number(this)">

                                </div>
                                {{-- <div class="form-group">
                                    <label for="da">DA</label>
                                    <input type="number" class="form-control" name="da" placeholder="Enter DA"
                                        value="{{ $staff_edit->da }}">

                                </div> --}}
                                <div class="form-group">
                                    <label for="other_allowances">Other Allowance</label>
                                    <input type="number" class="form-control" name="other_allowances"
                                        id="myNumberInput5" placeholder="Enter Other Allowances"
                                        value="{{ $staff_edit->other_allowances }}">
                                </div>

                            </div>

                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">

                            </div> --}}


                        </div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                    <button type="submit" id="submit" name="submit" value="{{ $staff_edit->add }}"
                                        class="btn btn-primary">{{ $staff_edit->add }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (count($list) > 0)
        <div class="row gutters mt-3 mb-3">
            <div class="col" style="padding:0;">
                <div class="card h-100">

                    <div class="card-body table-responsive">
                        <h6 class="mb-3 text-primary">Salary Details List</h6>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th>
                                        Salary Type
                                    </th>
                                    <th>
                                        Basic Pay
                                    </th>
                                    <th>
                                        Ph.D Allowance
                                    </th>
                                    <th>
                                        AGP
                                    </th>
                                    <th>
                                        Special Pay
                                    </th>
                                    <th>
                                        HRA(%)
                                    </th>
                                    <th>
                                        HRA
                                    </th>
                                    <th>
                                        DA
                                    </th>
                                    <th>
                                        Other Allowence
                                    </th>
                                    <th>Gross Salary</th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        @if ($list[$i] == $list[0])
                                            <td>Default Salary</td>
                                        @else
                                            <td>Increment</td>
                                        @endif
                                        <td>{{ $list[$i]->basic_pay }}</td>
                                        <td>{{ $list[$i]->phd_allowance }}</td>
                                        <td>{{ $list[$i]->agp }}</td>
                                        <td>{{ $list[$i]->special_pay }}</td>
                                        <td>{{ $list[$i]->hra }}</td>
                                        <td>{{ $list[$i]->hra_amount }}</td>
                                        <td>{{ $list[$i]->da }}</td>
                                        <td>{{ $list[$i]->other_allowances }}</td>
                                        <td>{{ $list[$i]->gross_salary }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.staff-salaries.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn btn-xs btn-info">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.staff-salaries.destroy', $list[$i]->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    function check_number(element) {

        if (element.value < 0) {
            element.value = 0;
        } else if (element.value > 100) {
            element.value = 100;
        }

    }
</script>
