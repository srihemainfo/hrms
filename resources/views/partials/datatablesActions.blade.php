@if (isset($viewGate))
    @can($viewGate)
        <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}" target="_blank">
            {{ trans('global.view') }}
        </a>
    @endcan
@endif

@if (isset($editGate))
    @can($editGate)
        @if ($editGate == 'certificate_provision')
            <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.print', $row->id) }}"
                target="_blank">Print
            </a>
            <button class="btn btn-sm btn-success mt-2" onclick="certificateReady({{ $row->id }})">Ready</button>
        @elseif ($editGate == '')
        @else
            <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}" target="_blank">
                {{ trans('global.edit') }}
            </a>
        @endif
    @endcan
@endif

@if (isset($deleteGate))
    @can($deleteGate)
        @if ($deleteGate == 'student_delete')
            <button type="submit" class="btn btn-xs btn-danger" data-id="{{ $row->id }}"
                id="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}"
                onclick="deleteStudent(this)">Delete</button>
        @else
            <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST"
                onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endif
    @endcan
@endif
