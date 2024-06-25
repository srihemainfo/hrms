@if(isset($accessGate))
@can($accessGate)
<div class="toggle text-center" >
    <input type="checkbox" id='Leave_value' data-class="{{ $row->id }}" class="toggleData" data-id="{{ $row->past_attendance_control }}" {{ $row->past_attendance_control == 0 ? '' : 'checked'}} onchange="attControl(this)" />
    <label></label>
</div>
@endcan
@endif



@if(isset($accessGate2))
@can($accessGate2)
<div class="toggle text-center" >
    <input type="checkbox" data-class="{{ $row->user_name_id }}" class="toggleData" data-id="{{ $row->past_leave_access }}" {{ $row->past_leave_access == 0 ? '' : 'checked'}} onchange="attControl(this)" />
    <label></label>
</div>
@endcan
@endif

