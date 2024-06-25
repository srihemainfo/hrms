 @if (auth()->user()->roles[0]->id == 11)
     @php
         $key = 'layouts.studentHome';
     @endphp
 @else
     @php
         $key = 'layouts.admin';
     @endphp
 @endif
 @extends($key)
 @section('content')
     <style>
         .table.datatable tbody td.select-checkbox:before {
             content: none !important;
         }

         .sorting_desc {
             display: none !important;
         }
     </style>
     {{-- <a class="btn btn-default mb-3" href="{{ route('admin.fee-payment.collectIndex') }}">
        {{ trans('global.back_to_list') }}
    </a> --}}
     <div class="card" id="fee_structure">
         <input type="hidden" id="id" value="{{ $id }}">
         <div class="card-header">
             <div class="row">
                 <div class="col-sm-6 col-12 text-primary">Fee Details</div>
                 <div class="col-sm-6 col-12 text-right" id="download_div">

                 </div>
             </div>
         </div>
         <div class="card-body">
             <table class="table table-bordered" id="historyTable">
                 <thead id="thead">
                     <tr>
                         <th colspan="2">Payment History</th>
                     </tr>
                 </thead>
                 <tbody id="tbody">
                 </tbody>
             </table>
         </div>
     </div>
 @endsection
 @section('scripts')
     <script>
         window.onload = function() {

             let id = $("#id").val();
             $("#download_div").html('');
             $("#tbody").html(`<tr class="text-center"><td colspan="2">Loading...</td></tr>`);
             $.ajax({
                 url: '{{ route('admin.fee-payment.collectShow') }}',
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 data: {
                     'id': id,
                 },
                 success: function(response) {
                     // console.log(response);
                     let history = response.history;
                     let data;
                     var year;

                     if (history != null) {

                         if (history.year == '4') {
                             year = 'Final Year';
                         } else if (history.year == '3') {
                             year = 'Third Year';
                         } else if (history.year == '2') {
                             year = 'Second Year';
                         } else if (history.year == '1') {
                             year = 'First Year';
                         }
                         var given_date = history.date;
                         var parts = given_date.split('-');
                         var formattedDate = given_date;
                         if (parts.length == 3) {
                             formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                         }
                         $("#download_div").html(
                             `<a class="enroll_generate_bn bg-success" href="{{ url('admin/fee-payment/pdf/${history.id}') }}" target="_blank">Download PDF</a>`
                         );
                         data = `
                        <tr>
                               <td><b>Student Name </b></td>
                               <td><b>${history.student.name}  (${history.student.register_no})</b></td>
                           </tr>
                           <tr>
                               <td><b>Year</b></td>
                               <td><b>${year}</b></td>
                           </tr>
                           <tr>
                               <td><b>Date</b></td>
                               <td>${history.date}</td>
                           </tr>
                           <tr>
                               <td><b>Payment Mode</b></td>
                               <td>${history.payment_mode}</td>
                           </tr>
                           <tr>
                               <td><b>Status</b></td>
                               <td>${history.status}</td>
                           </tr>
                           <tr>
                               <td colspan="2"></td>
                           </tr>
                           <tr>
                               <td colspan="2"><b>Tuition Fee Details </b></td>
                           </tr>
                           <tr>
                               <td>Tuition Fee</td>
                               <td>${history.tuition_fee}</td>
                           </tr>
                           <tr>
                               <td>Tuition Paid Fee</td>
                               <td>${history.tuition_paid}</td>
                           </tr>
                           <tr>
                               <td>Tuition Paying Fee</td>
                               <td>${history.tuition_paying}</td>
                           </tr>
                           <tr>
                               <td>FG Discount</td>
                               <td>${history.fg_deduction}</td>
                           </tr>
                           <tr>
                               <td>Tuition Balance Fee</td>
                               <td>${history.tuition_balance}</td>
                           </tr>
                           <tr>
                               <td colspan="2"></td>
                           </tr>
                           <tr>
                               <td colspan="2"><b>Hostel Fee Details</b></td>

                           </tr>
                           <tr>
                               <td>Hostel Fee</td>
                               <td>${history.hostel_fee}</td>
                           </tr>
                           <tr>
                               <td>Hostel Paid Fee</td>
                               <td>${history.hostel_paid}</td>
                           </tr>
                           <tr>
                               <td>Hostel Paying Fee</td>
                               <td>${history.hostel_paying}</td>
                           </tr>
                           <tr>
                               <td>Hostel Balance Fee</td>
                               <td>${history.hostel_balance}</td>
                           </tr>
                           <tr>
                               <td colspan="2"></td>
                           </tr>
                           <tr>
                               <td colspan="2"><b>Other Fee Details</b></td>

                           </tr>
                           <tr>
                               <td>Other Fee</td>
                               <td>${history.others}</td>
                           </tr>
                           <tr>
                               <td>Other Paid Fee</td>
                               <td>${history.other_paid}</td>
                           </tr>
                           <tr>
                               <td>Other Paying Fee</td>
                               <td>${history.other_paying}</td>
                           </tr>
                           <tr>
                               <td>Sponserhip Amount</td>
                               <td>${history.sponser_amt}</td>
                           </tr>
                           <tr>
                               <td>Other Balance Fee</td>
                               <td>${history.other_balance}</td>
                           </tr>
                           <tr>
                               <td colspan="2"></td>
                           </tr>
                           <tr>
                               <td colspan="2"><b> Fee Summary </b></td>
                           </tr>
                           <tr>
                               <td>Total Fee</td>
                               <td>${history.total_fee}</td>
                           </tr>
                           <tr>
                               <td>Total Paid Fee</td>
                               <td>${history.total_paid}</td>
                           </tr>
                           <tr>
                               <td>Total Paying Fee</td>
                               <td>${history.total_paying}</td>
                           </tr>
                           <tr>
                               <td>FG Discount</td>
                               <td>${history.fg_deduction}</td>
                           </tr>
                           <tr>
                               <td>Sponserhip Amount</td>
                               <td>${history.sponser_amt}</td>
                           </tr>
                           <tr>
                               <td>Total Balance Fee</td>
                               <td>${history.total_balance}</td>
                           </tr>
                            `;
                     } else {
                         data = `<tr><td class="text-center"> No Data Available...</td></tr>`;
                     }

                     $("#tbody").html(data);

                 }
             });
         }
     </script>
 @endsection
