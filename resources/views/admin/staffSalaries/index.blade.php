@extends('layouts.admin')
@section('content')
@can('staff_salary_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.staff-salaries.create') }}">
                {{ trans('global.add') }} Staff Salary
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'StaffSalary', 'route' => 'admin.staff-salaries.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
       Staff Salary {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-StaffSalary">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Staff Code
                        </th>
                        <th>
                            Biometric ID
                        </th>
                        <th>
                            Staff Name
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Designation
                        </th>
                        <th>
                            Date of Joining
                        </th>
                        <th>
                            Total Working Days
                        </th>
                        <th>
                            Loss of Pay
                        </th>
                        <th>
                            No of Day (Payable)
                        </th>
                        <th>
                            Actual Gross Salary
                        </th>
                        <th>
                            Basic Pay
                        </th>
                        <th>
                            AGP
                        </th>
                        <th>
                            HRA(%)
                        </th>
                        <th>
                            HRA
                        </th>
                        <th>
                            Arrears
                        </th>
                        <th>
                            Special Pay
                        </th>
                        <th>
                            Other Allowances
                        </th>
                        <th>
                            Appraisal Based Increment
                        </th>
                        <th>
                            Phd Allowance
                        </th>
                        <th>
                            Gross Salary
                        </th>
                        <th>
                            IT
                        </th>
                        <th>
                           PT
                        </th>
                        <th>
                            Salary Advance
                        </th>
                        <th>
                            Other Deduction
                        </th>
                        <th>
                           ESI
                        </th>
                        <th>
                            EPF
                        </th>
                        <th>
                            Total Deductions
                        </th>
                        <th>
                            Net Salary
                        </th>
                        <th>
                            Remarks
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffSalaries as $key => $staffSalary)
                        <tr data-entry-id="{{ $staffSalary->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $staffSalary->id ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->staff_code ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->biometric ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->staff_name ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->department ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->designation ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->date_of_joining ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->total_working_days ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->loss_of_pay ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->no_of_day_payable ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->actual_gross_salary ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->basic_pay ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->agp ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->hra ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->hra_amount ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->arrears ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->special_pay ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->other_allowances ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->appraisal_based_increment ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->phd_allowance ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->gross_salary ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->it ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->pt ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->salary_advance ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->other_deduction ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->esi ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->epf ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->total_deductions ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->net_salary ?? '' }}
                            </td>
                            <td>
                                {{ $staffSalary->remarks ?? '' }}
                            </td>
                            <td>
                                @can('staff_salary_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.staff-salaries.show', $staffSalary->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('staff_salary_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.staff-salaries.edit', $staffSalary->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('staff_salary_delete')
                                    <form action="{{ route('admin.staff-salaries.destroy', $staffSalary->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('staff_salary_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.staff-salaries.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-StaffSalary:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
