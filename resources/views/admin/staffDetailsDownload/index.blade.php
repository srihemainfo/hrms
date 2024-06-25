@extends('layouts.admin')
@section('content')

<style>
    .table.dataTable tbody td.select-checkbox:before {
        content: none !important;
    }
</style>

<div class="loading" id='loading' style='display:none'>Loading&#8230;</div>

<div class="card">
    <div class="card-header text-uppercase text-center">
        <p class='text-center'> <strong> Staff Details Download</strong></p>
    </div>
    <div class="card">

    </div>


    <div class="card-body">

        <div class="row">

            <div class="col-9 error_message text-center text-danger" style="display: none;">

            </div>
        </div>
        <div class="row">

            <div class="form-group col-3 ">
                <p>
                    <label class="required" for="accademicYear">Staff Details</label>
                </p>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }} " name="name" id="name" required>
                    <option value="">Please Select</option>
                    @foreach ($title as $id => $entry)
                    <option value="{{ $id }}">
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-group col-3 ">
                <div class="row" style='padding-top:45px;'>
                    <button class="btn btn-success " id='submit' type="button">
                       Go
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
<div class="row gutters mt-3 mb-3" id='dataHeader' style='display:none'>
    <div class="col" style="padding:0;">
        <div class="card h-100">
            <div class="card-body ">
                <div class="card " id='Data_get' >
                    <div class="card-header text-right" id="card_header">
                        <button class="manual_bn bg-success" onclick="ExportToExcel('xlsx')"> Download Excel File</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class=" table table-bordered table-striped text-center table-hover ajaxTable datatable  datatable-events" id='tbl_exporttable_to_xls'>
                            <thead class='text-uppercase' id='header'>
                            </thead>
                            <tbody id='body_content'>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>

    <script>


        $(document).ready(function() {

            // var $personal_details = $('#name');
            $('name').select2();

            $(document).on("click", "#submit", function() {

                var $personal_details = $('#name');
                var $value = $personal_details.val();
                var $Data_get = $('#Data_get');
                var $header = $('#header');
                var $body_content = $('#body_content');
                var $dataHeader = $('#dataHeader');

                if ($personal_details.val() === "") {
                    $personal_details.html('<option value=""> select a Staff Details</option>');
                } else {
                    $('.datatable-events').hide();
                    var $class = $('.datatable-events');

                    $('#loading').show();
                    $("select").prop("disabled", true);
                    $.ajax({
                        url: "{{ route('admin.Staff_details_personal') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'personal': $value,
                        },
                        success: function(response) {
                            let status = response.status;
                            if (status == 200) {
                                $body_content.html('');
                                let $thead = response.sortedColumns;
                                let $thead_length = response.sortedColumns.length;
                                let head = '<tr>';
                                for (var i = 0; i < $thead_length; i++) {
                                    head += `<th>${$thead[i].name}</th>`;
                                }
                                head += '</tr>';

                                $header.html(head)
                                let collectionArray = response.data.collection;

                                let sortedColumnsArray = response.sortedColumns
                                let $body_row_data = '';
                                for (let i = 0; i < collectionArray.length; i++) {
                                    // Get the current objects from both arrays
                                    let collectionItem = collectionArray[i];
                                    let columnItem = sortedColumnsArray;
                                    $body_row_data += '<tr>';
                                    for (j = 0; j < sortedColumnsArray.length; j++) {

                                        $body_row_data += `<td> ${collectionItem[columnItem[j].data] != null ? collectionItem[columnItem[j].data] : '' } </td>`;

                                    }

                                    $body_row_data += '</tr>';

                                }
                                $body_content.html($body_row_data);

                                $('#card_header').show();
                                $("select").prop("disabled", false);
                                $('.datatable-events').show();
                                $dataHeader.show();

                                // $Data_get.show();
                                // $('.datatable-events').show();
                                $('#loading').hide();

                            }else{
                                $body_content.html('<tr> <td colspan="12"> No Data Available</td></tr>');
                                $header.html('<tr> <td colspan="12"> Message</td></tr>');
                                $("select").prop("disabled", false);
                                // $Data_get.hide();
                                $('.datatable-events').show();
                                // $('.datatable-events').show();
                                $('#card_header').hide();
                                // $('#Data_get').show();
                                $('#dataHeader').show();
                                $('#loading').hide();
                            }

                        }
                    });

                }



            });

            // $personal_details.on("change", function() {
            //     const year = this.value;
            //     PersonalDetailsGet();
            // });

            // function PersonalDetailsGet() {
            //     var $personal_details = $('#name');
            //     var $value = $personal_details.val();
            //     var $Data_get = $('#Data_get');
            //     var $header = $('#header');
            //     var $body_content = $('#body_content');
            //     var $dataHeader = $('#dataHeader');

            //     if ($personal_details.val() === "") {
            //         $personal_details.html('<option value=""> select a Staff Details</option>');
            //     } else {
            //         $('.datatable-events').hide();
            //         var $class = $('.datatable-events');

            //         $('#loading').show();
            //         $("select").prop("disabled", true);
            //         $.ajax({
            //             url: "{{ route('admin.Staff_details_personal') }}",
            //             type: "POST",
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             data: {
            //                 'personal': $value,
            //             },
            //             success: function(response) {
            //                 let status = response.status;
            //                 if (status == 200) {
            //                     $body_content.html('');
            //                     let $thead = response.sortedColumns;
            //                     let $thead_length = response.sortedColumns.length;
            //                     let head = '<tr>';
            //                     for (var i = 0; i < $thead_length; i++) {
            //                         head += `<th>${$thead[i].name}</th>`;
            //                     }
            //                     head += '</tr>';

            //                     $header.html(head)
            //                     let collectionArray = response.data.collection;
            //                     let sortedColumnsArray = response.sortedColumns
            //                     let $body_row_data = '';
            //                     for (let i = 0; i < collectionArray.length; i++) {
            //                         // Get the current objects from both arrays
            //                         let collectionItem = collectionArray[i];
            //                         let columnItem = sortedColumnsArray;
            //                         $body_row_data += '<tr>';
            //                         for (j = 0; j < sortedColumnsArray.length; j++) {

            //                             $body_row_data += `<td> ${collectionItem[columnItem[j].data] != null ? collectionItem[columnItem[j].data] : '' } </td>`;

            //                         }

            //                         $body_row_data += '</tr>';

            //                     }
            //                     $body_content.html($body_row_data);

            //                     $('#card_header').show();
            //                     $("select").prop("disabled", false);
            //                     $('.datatable-events').show();
            //                     $dataHeader.show();

            //                     // $Data_get.show();
            //                     // $('.datatable-events').show();
            //                     $('#loading').hide();

            //                 }else{
            //                     $body_content.html('<tr> <td colspan="12"> No Data Available</td></tr>');
            //                     $header.html('<tr> <td colspan="12"> Message</td></tr>');
            //                     $("select").prop("disabled", false);
            //                     // $Data_get.hide();
            //                     $('.datatable-events').show();
            //                     // $('.datatable-events').show();
            //                     $('#card_header').hide();
            //                     // $('#Data_get').show();
            //                     $('#dataHeader').show();
            //                     $('#loading').hide();
            //                 }

            //             }
            //         });

            //     }
            // }





        });
    </script>

    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var text = $.trim($("#name option:selected"). text());
            console.log(text)
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || (`${text}.` + (type || 'xlsx')));
        }
    </script>

    @endsection
