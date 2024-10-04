@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('college_calender_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.college-calenders.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.collegeCalender.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'CollegeCalender', 'route' => 'admin.college-calenders.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.collegeCalender.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-CollegeCalender">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.academic_year') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.shift') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.semester_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.to_date') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collegeCalenders as $key => $collegeCalender)
                                    <tr data-entry-id="{{ $collegeCalender->id }}">
                                        <td>
                                            {{ $collegeCalender->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $collegeCalender->type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $collegeCalender->academic_year ?? '' }}
                                        </td>
                                        <td>
                                            {{ $collegeCalender->shift ?? '' }}
                                        </td>
                                        <td>
                                            {{ $collegeCalender->semester_type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $collegeCalender->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $collegeCalender->to_date ?? '' }}
                                        </td>
                                        <td>
                                            @can('college_calender_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.college-calenders.show', $collegeCalender->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('college_calender_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.college-calenders.edit', $collegeCalender->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('college_calender_delete')
                                                <form action="{{ route('frontend.college-calenders.destroy', $collegeCalender->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('college_calender_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.college-calenders.massDestroy') }}",
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
    pageLength: 25,
  });
  let table = $('.datatable-CollegeCalender:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection