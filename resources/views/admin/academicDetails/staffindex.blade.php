
<div class="container">

    <div class="row gutters">

        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                    action="{{ route('admin.academic-details.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Academic Details</h6>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="text-align: center;">
                                Under Construction..
                                {{-- <div class="text-right">
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary">Add</button>
                                </div> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


