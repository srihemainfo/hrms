@if (isset($viewGate))
    @can($viewGate)
        @if ($viewFunct != 'viewExamfee')
            @if (isset($row->id))
                <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row->id }})" title="View">
                    <i class="fa-fw nav-icon far fa-eye"></i>
                </button>
            @elseif (isset($row['id']))
                <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row['id'] }})" title="View">
                    <i class="fa-fw nav-icon far fa-eye"></i>
                </button>
            @elseif (isset($row->rack['id']))
                <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row->rack['id'] }})" title="View">
                    <i class="fa-fw nav-icon far fa-eye"></i>
                </button>
            @endif
        @else
            <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row->regulation_id }})" title="View">
                <i class="fa-fw nav-icon far fa-eye"></i>
            </button>
        @endif
    @endcan
@endif

@if (isset($editGate))
    @can($editGate)
        @if ($editFunct != 'editExamfee')
            @if (isset($row->id))
                <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row->id }})" title="Edit">
                    <i class="fa-fw nav-icon far fa-edit"></i>
                </button>
            @elseif (isset($row['id']))
                <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row['id'] }})" title="Edit">
                    <i class="fa-fw nav-icon far fa-edit"></i>
                </button>
            @elseif (isset($row->rack['id']))
                <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row->rack['id'] }})" title="Edit">
                    <i class="fa-fw nav-icon far fa-edit"></i>
                </button>
            @endif
        @else
            <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row->regulation_id }})" title="Edit">
                <i class="fa-fw nav-icon far fa-edit"></i>
            </button>
        @endif
    @endcan
@endif

@if (isset($deleteGate))
    @can($deleteGate)
        @if ($deleteFunct != 'deleteExamfee')
            @if (isset($row->id))
                <button class="newDeleteBtn" onclick="{{ $deleteFunct }}({{ $row->id }})" title="Delete">
                    <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                </button>
            @elseif (isset($row->rack['id']))
                <button class="newDeleteBtn" onclick="{{ $deleteFunct }}({{ $row->rack['id'] }})" title="Delete">
                    <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                </button>
            @endif
        @else
            <button class="newDeleteBtn" onclick="{{ $deleteFunct }}({{ $row->regulation_id }})" title="Delete">
                <i class="fa-fw nav-icon far fa-trash-alt"></i>
            </button>
        @endif
    @endcan
@endif
