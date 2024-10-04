<div class="container">

    <div class="row gutters">
        {{-- {{ dd($list); }} --}}
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.bank-account-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Bank Account Details</h5>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="account_type">Account Type</label>
                                    <select class="form-control select2" name="account_type">
                                        <option value="Salary Account"
                                            {{ old('account_type', $staff_edit->account_type) == 'Salary Account' ? 'selected' : '' }}>
                                            Salary Account</option>
                                        <option value="Savings Account"
                                            {{ old('account_type', $staff_edit->account_type) == 'Savings Account' ? 'selected' : '' }}>
                                            Savings Account</option>
                                        <option value="Current Account"
                                            {{ old('account_type', $staff_edit->account_type) == 'Current Account' ? 'selected' : '' }}>
                                            Current Account</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="account_no">Account No</label>
                                    <input type="text" class="form-control" name="account_no"
                                        placeholder="Enter Account No" value="{{ $staff_edit->account_no }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="ifsc_code">IFSC Code</label>
                                    <input type="text" class="form-control" name="ifsc_code"
                                        placeholder="Enter IFSC Code" value="{{ $staff_edit->ifsc_code }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name"
                                        placeholder="Enter Bank Name" value="{{ $staff_edit->bank_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="branch_name">Branch Name</label>
                                    <input type="text" class="form-control" name="branch_name"
                                        placeholder="Enter Branch Name" value="{{ $staff_edit->branch_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="bank_location">Bank Location</label>
                                    <input type="text" class="form-control" name="bank_location"
                                        placeholder="Enter Bank Location" value="{{ $staff_edit->bank_location }}">
                                </div>
                            </div>
                            <input type="hidden" name="status" value="0">
                        </div>

                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                    <button type="submit" id="submit" name="submit" value="submit"
                                        class="btn btn-primary Edit">{{ $staff_edit->add }}</button>
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
                        <h5 class="mb-3 text-primary">Bank Account List</h5>
                        <table class="list_table">
                            <thead>
                                @php
                                    $user = auth()->user();

                                    if ($user) {
                                        // Get the user's ID
                                        $userId = $user->id;
                                        // dd($userId);
                                        // You can also use the following equivalent syntax:
                                        // $userId = auth()->id();

                                        // $userId now contains the ID of the authenticated user
                                    } else {
                                        // User is not authenticated
                                        // Handle accordingly
                                    }
                                @endphp <tr>
                                    <th>
                                        Account Type
                                    </th>
                                    <th>
                                        Account No
                                    </th>
                                    <th>
                                        IFSC Code
                                    </th>
                                    <th>
                                        Bank Name
                                    </th>
                                    <th>
                                        Branch Name
                                    </th>
                                    <th>
                                        Bank Location
                                    </th>
                                        <th>
                                            Status
                                        </th>
                                    <th>
                                        Action
                                    </th>
                                    </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($list); $i++)
                                    <tr>
                                        <td>{{ $list[$i]->account_type }}</td>
                                        <td>{{ $list[$i]->account_no }}</td>
                                        <td>{{ $list[$i]->ifsc_code }}</td>
                                        <td>{{ $list[$i]->bank_name }}</td>
                                        <td>{{ $list[$i]->branch_name }}</td>
                                        <td>{{ $list[$i]->bank_location }}</td>
                                        <td>
                                        @if ($userId == 1)
                                            @if ($list[$i]->status == '0')

                                                    <form method="POST"
                                                        action="{{ route('admin.bank-account-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <button type="submit" name="accept" value="accept"
                                                            class="btn btn-success  ">Accept</button>
                                                    </form>

                                                    <form
                                                        action="{{ route('admin.bank-account-details.destroy', $list[$i]->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token"
                                                            value="{{ csrf_token() }}">
                                                        <input type="submit" class="btn  btn-danger mt-2"
                                                            value="{{ 'Reject' }}">
                                                    </form>

                                            @endif

                                            @if ($list[$i]->status == '1')

                                                    <div class="p-2 Approved"
                                                        >
                                                        Approved </div>

                                            @endif
                                        @endif

                                        @if ($userId == $staff->user_name_id)
                                            @if ($list[$i]->status == '0')

                                                    <div class="p-2 Pending"
                                                        >
                                                        Pending </div>

                                            @elseif ($list[$i]->status == '1')

                                                <div class="p-2 Approved"
                                                    >
                                                    Approved</div>

                                            @endif
                                        @endif
                                    </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.bank-account-details.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                @csrf
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn  btn-info Edit">Edit</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.bank-account-details.destroy', $list[$i]->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn  btn-danger mt-2"
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
