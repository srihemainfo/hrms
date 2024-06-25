@extends('layouts.admin')
@section('content')
    <style>
        input[type="file"] {
            border: none;
            cursor: pointer;
            font-size: 16px;
        }


        input[type="file"]:focus {
            outline: none;
        }
    </style>
    @php
        ini_set('max_execution_time', 240);
    @endphp
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-6">
                    <input type="file" name="images[]" id="images" multiple><br>
                    <span class="text-primary" id="text_shower" style="font-size:0.8rem;">Each File Should Be In 6 MB. PNG,
                        JPG ,JPEG Formats
                        Only</span>
                    <span id="process_shower" class="text-success" style="display:none;">Processing...</span>
                </div>
                <div class="col-md-8 col-6">
                    <button class="enroll_generate_bn" onclick="go()">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function go() {
            console.log($("#images")[0].files)
            let images = $("#images")[0].files;
            if (images.length > 0) {
                $("#process_shower").show();
                $("#text_shower").hide();
                let formData = new FormData();
                for (let i = 0; i < images.length; i++) {
                    formData.append('images[]', images[i]);
                }
                $.ajax({
                    url: '{{ route('admin.student-image.upload') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if (response.status == true) {
                            Swal.fire('', response.data, 'success');
                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                        $("#process_shower").hide();
                        $("#text_shower").show();
                        $("#images").val('');
                    }
                })
            } else {
                $("#process_shower").hide();
                $("#text_shower").show();
                Swal.fire('', 'Please Select Images', 'info');
                return false;
            }
        }
    </script>
@endsection
