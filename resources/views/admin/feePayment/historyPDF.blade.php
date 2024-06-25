<!DOCTYPE html>
<html>

<head></head>

<body style="font-family: Arial, Helvetica, sans-serif;">
    @if ($history != null)
        <div>
            <div>
                <div style="float:left;width:20%;">
                    <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" style="width:100%;" alt="RIT LOGO">
                </div>
                <div style="float:right;width:80%;">
                    <div style="width:100%;color:#007bff;font-weight:bold;padding-left:5%;padding-top:15px;">Demo
                        Institute Of Technology</div>
                </div>
            </div>
            <table style="width:100%;padding-top:70px;">
                {{-- <tr>
                <td style="width:2%;">
                    <img src="{{ public_path('adminlogo/school_menu_logo.png') }}" style="width:50%;" alt="RIT LOGO">
                </td>
                <td style="width:98%;margin-left:-10px;">
                    <div style="width:100%;color:#007bff;font-weight:bold;">Demo Collage Of Engineering & Technology</div>
                </td>
            </tr> --}}
                <tr>
                    <td colspan="2" style="text-align:center;"><b>Receipt</b></td>
                </tr>
                <tr>
                    <td style="width:50%;padding-top:15px;"><b>Receipt No : </b> {{ $history->id }}</td>
                    <td style="width:50%;text-align:right;padding-top:15px;"><b>Receipt Date : </b> {{ $history->date }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:15px;"><b>Student Name :</b>  {{ $history->name }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:15px;"><b>Course Name :</b> {{ $history->course }}</td>
                </tr>
                <tr>
                    <td style="width:35%;padding-top:15px;"><b>Student Year : </b>  {{ $history->year }}</td>
                    <td style="width:65%;padding-top:15px;text-align:right;"><b>Student Roll No : </b> {{ $history->register_no }}</td>
                </tr>
            </table>
            <table
                style="margin-top:20px;border-top:1px solid black;border-bottom:1px solid black;border-collapse:collapse;width:100%;">
                <tr>
                    <td
                        style="border-left:1px solid black;border-bottom:1px solid black;border-right:1px solid black;padding-left:10px;text-align:center;">
                        <b>Particulars</b> </td>
                    <td
                        style="border-right:1px solid black;border-bottom:1px solid black;padding-left:10px;text-align:center;">
                        <b>Amount</b></td>
                </tr>
                <tr>
                    <td
                        style="border-left:1px solid black;border-right:1px solid black;padding-left:10px;text-align:center;font-size:0.9rem;">
                        <b>TUITION FEE</b> </td>
                    <td style="border-right:1px solid black;padding-left:10px;text-align:center;"><b>{{ $history->tuition_paid }}</b></td>
                </tr>
                <tr>
                    <td
                        style="border-left:1px solid black;border-right:1px solid black;padding-left:10px;text-align:center;font-size:0.9rem;">
                        <b>OTHER FEE</b> </td>
                    <td style="border-right:1px solid black;padding-left:10px;text-align:center;"><b>{{ $history->other_paid }}</b></td>
                </tr>
                <tr>
                    <td
                        style="border-left:1px solid black;border-right:1px solid black;padding-left:10px;text-align:center;font-size:0.9rem;">
                        <b>HOSTEL FEE</b> </td>
                    <td style="border-right:1px solid black;padding-left:10px;text-align:center;"><b>{{ $history->hostel_paid }}</b></td>
                </tr>
                <tr>
                    <td
                        style="border-left:1px solid black;border-top:1px solid black;border-right:1px solid black;padding-left:10px;text-align:center;">
                        <b>Total</b> </td>
                    <td
                        style="border-right:1px solid black;border-top:1px solid black;padding-left:10px;text-align:center;">
                        <b>{{ $history->total_paid }}</b></td>
                </tr>
            </table>
            <table style="width:100%;">
                <tr>
                    <td colspan="2" style="font-size:0.8rem;padding-top:5px;">NEFT REF NO : 9080765839</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:15px"><b>Rupees :</b> <span style="font-size:0.8rem;"><b> {{ $history->amtWord }}  ONLY </b></span></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:15px">Note : Cheque Subject to realisation</td>
                </tr>
                <tr>
                    <td style="width:50%;padding-top:70px;"><b>Cashier</b> </td>
                    <td style="width:50%;text-align:right;padding-top:70px;"><b>Administrative Officer</b></td>
                </tr>
                <tr>
                    <td colspan="2" style="width:100%;text-align:center;padding-top:70px;">Computer generated
                            receipt, no signature required</td>
                </tr>
            </table>

        </div>
    @endif
</body>

</html>
