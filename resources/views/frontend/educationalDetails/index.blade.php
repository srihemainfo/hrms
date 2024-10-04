@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('educational_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.educational-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.educationalDetail.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'EducationalDetail', 'route' => 'admin.educational-details.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.educationalDetail.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-EducationalDetail">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.education_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.institute_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.institute_location') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.medium') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.board_or_university') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.marks') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.marks_in_percentage') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_1') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_1') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_2') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_2') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_3') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_3') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_4') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_4') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_5') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_5') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.subject_6') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.educationalDetail.fields.mark_6') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($educationalDetails as $key => $educationalDetail)
                                    <tr data-entry-id="{{ $educationalDetail->id }}">
                                        <td>
                                            {{ $educationalDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->education_type->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->institute_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->institute_location ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->medium->medium ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->board_or_university ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->marks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->marks_in_percentage ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->subject_1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->mark_1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->subject_2 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->mark_2 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->subject_3 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->mark_3 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->subject_4 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->mark_4 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->subject_5 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->mark_5 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->subject_6 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $educationalDetail->mark_6 ?? '' }}
                                        </td>
                                        <td>
                                            @can('educational_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.educational-details.show', $educationalDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('educational_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.educational-details.edit', $educationalDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('educational_detail_delete')
                                                <form action="{{ route('frontend.educational-details.destroy', $educationalDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('educational_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.educational-details.massDestroy') }}",
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
  let table = $('.datatable-EducationalDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection