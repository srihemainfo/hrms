@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('experience_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.experience-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.experienceDetail.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'ExperienceDetail', 'route' => 'admin.experience-details.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.experienceDetail.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ExperienceDetail">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.designation') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.years_of_experience') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.worked_place') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.taken_subjects') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.from_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.to_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.experienceDetail.fields.name') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($experienceDetails as $key => $experienceDetail)
                                    <tr data-entry-id="{{ $experienceDetail->id }}">
                                        <td>
                                            {{ $experienceDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->designation ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->years_of_experience ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->worked_place ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->taken_subjects ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->from_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->to_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $experienceDetail->name->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('experience_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.experience-details.show', $experienceDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('experience_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.experience-details.edit', $experienceDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('experience_detail_delete')
                                                <form action="{{ route('frontend.experience-details.destroy', $experienceDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('experience_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.experience-details.massDestroy') }}",
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
  let table = $('.datatable-ExperienceDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection