@extends('layouts.admin')
@section('content')
@can('personal_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.personal-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.personalDetail.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'PersonalDetail', 'route' => 'admin.personal-details.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.personalDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-PersonalDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.user_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.age') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.dob') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.mobile_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.aadhar_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.blood_group') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.mother_tongue') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.religion') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.community') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.state') }}
                    </th>
                    <th>
                        {{ trans('cruds.personalDetail.fields.country') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('personal_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.personal-details.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.personal-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name_name', name: 'user_name.name' },
{ data: 'age', name: 'age' },
{ data: 'dob', name: 'dob' },
{ data: 'email', name: 'email' },
{ data: 'mobile_number', name: 'mobile_number' },
{ data: 'aadhar_number', name: 'aadhar_number' },
{ data: 'blood_group_name', name: 'blood_group.name' },
{ data: 'mother_tongue_mother_tongue', name: 'mother_tongue.mother_tongue' },
{ data: 'religion_name', name: 'religion.name' },
{ data: 'community_name', name: 'community.name' },
{ data: 'state', name: 'state' },
{ data: 'country', name: 'country' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-PersonalDetail').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
