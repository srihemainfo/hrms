<style>
    input[type="file"] {
        /* background-color: #f2f2f2; */
        border: none;
        /* color: #555; */
        cursor: pointer;
        font-size: 16px;
        /* padding: 10px; */
    }

    input[type="file"]:focus {
        outline: none;
    }

    .fullscreen {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: all 0.5s ease;
        z-index: 9999;
        background-color: rgba(0,0,0,0.9);
    }

    body.fullscreen-enabled {
        overflow: hidden;
    }
</style>

<div class="container">
    <div class="row gutters">
        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.documents.stu_update', ['user_name_id' => $student->user_name_id, 'name' => $student->name]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Upload Documents</h5>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="fileName">File Name</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('fileName') ? 'is-invalid' : '' }}"
                                        name="fileName" id="fileName" required>
                                        <option value="">Please Select</option>
                                        <option value="SSLC">SSLC</option>
                                        <option value="HSC">HSC</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="Aadhar Card">Aadhar Card</option>
                                        <option value="Community">Community</option>
                                        <option value="Income">Income</option>
                                        <option value="TC">TC</option>
                                        <option value="Extra Curricular">Extra Curricular</option>
                                    </select>
                                    @if ($errors->has('fileName'))
                                        <span class="text-danger">{{ $errors->first('fileName') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="document">Document</label>
                                    <input type="file" class="form-control" name="filePath" value="">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="0">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary">{{ $student->add }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (count($document) > 0)
        <div class="row gutters mt-3 mb-3">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    <div class="card-body table-responsive">
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
                        @endphp
                        <h5 class="mb-3 text-primary">Document List </h5>
                        <table class="list_table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($document as $row)
                                    <tr>
                                        @if (($row->fileName != '' || $row->fileName != null) && $row->fileName != 'Profile')
                                            <td>{{ $row->fileName }}</td>
                                            <td>
                                                <img class="uploaded_img" onclick="toggleFullScreen(this)"
                                                    alt="Click To Enlarge" src="{{ asset($row->filePath) }}"
                                                    alt="image">
                                            </td>
                                            <td>
                                                @if ($userId == 1)
                                                    @if ($row->status == '0')
                                                        <form method="POST"
                                                            action="{{ route('admin.documents.newapprove', ['user_name_id' => $student->user_name_id, 'name' => $student->name, 'id' => $row->id]) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <button type="submit" name="accept" value="accept"
                                                                class="btn btn-success">Accept</button>
                                                        </form>

                                                        <form action="{{ route('admin.documents.destroy', $row->id) }}"
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

                                                    @if ($row->status == '1')
                                                        <div class="p-2 Approved">
                                                            Approved </div>
                                                    @endif
                                                @endif

                                                @if ($userId == $student->user_name_id)
                                                    @if ($row->status == '0')
                                                        <div class="p-2 Pending">
                                                            Pending </div>
                                                    @elseif ($row->status == '1')
                                                        <div class="p-2 Approved">
                                                            Approved</div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.documents.destroy', $row->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn mt-2 btn-danger"
                                                        value="{{ trans('global.delete') }}">
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


@section('scripts')
    @parent
    <script>
        function toggleFullScreen(img) {
            if (document.fullscreenElement) {
                document.exitFullscreen();
                img.classList.remove("fullscreen");
            } else {
                img.requestFullscreen();
                img.classList.add("fullscreen");
            }
        }

        document.addEventListener("fullscreenchange", function() {
            if (!document.fullscreenElement) {
                var img = document.querySelector(".fullscreen");
                if (img) {
                    img.classList.remove("fullscreen");
                }
            }
        });
    </script>
@endsection
