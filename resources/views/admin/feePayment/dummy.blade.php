<!DOCTYPE html>
<html>

<head></head>

<body>
    @if ($history != null)
        <table border="1" style="width:100%;" id="historyTable">
            <tr>
                <th colspan="2">Fee Payment History</th>
            </tr>
            <tr>
                <td><b>Student Name </b></td>
                <td><b>{{ $history->name }}</b></td>
            </tr>
            <tr>
                <td><b>Year</b></td>
                <td><b>{{ $history->year }}</b></td>
            </tr>
            <tr>
                <td><b>Date</b></td>
                <td>{{ $history->date }}</td>
            </tr>
            <tr>
                <td><b>Payment Mode</b></td>
                <td>{{ $history->payment_mode }}</td>
            </tr>
            <tr>
                <td><b>Status</b></td>
                <td>{{ $history->status }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Tuition Fee Details </b></td>
            </tr>
            <tr>
                <td>Tuition Fee</td>
                <td>{{ $history->tuition_fee }}</td>
            </tr>
            <tr>
                <td>Tuition Paid Fee</td>
                <td>{{ $history->tuition_paid }}</td>
            </tr>
            <tr>
                <td>Tuition Paying Fee</td>
                <td>{{ $history->tuition_paying }}</td>
            </tr>
            <tr>
                <td>FG Discount</td>
                <td>{{ $history->fg_deduction }}</td>
            </tr>
            <tr>
                <td>Tuition Balance Fee</td>
                <td>{{ $history->tuition_balance }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Hostel Fee Details</b></td>

            </tr>
            <tr>
                <td>Hostel Fee</td>
                <td>{{ $history->hostel_fee }}</td>
            </tr>
            <tr>
                <td>Hostel Paid Fee</td>
                <td>{{ $history->hostel_paid }}</td>
            </tr>
            <tr>
                <td>Hostel Paying Fee</td>
                <td>{{ $history->hostel_paying }}</td>
            </tr>
            <tr>
                <td>Hostel Balance Fee</td>
                <td>{{ $history->hostel_balance }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Other Fee Details</b></td>

            </tr>
            <tr>
                <td>Other Fee</td>
                <td>{{ $history->other_fee }}</td>
            </tr>
            <tr>
                <td>Other Paid Fee</td>
                <td>{{ $history->other_paid }}</td>
            </tr>
            <tr>
                <td>Other Paying Fee</td>
                <td>{{ $history->other_paying }}</td>
            </tr>
            <tr>
                <td>Sponsership Amount</td>
                <td>{{ $history->sponser_amt }}</td>
            </tr>
            <tr>
                <td>Other Balance Fee</td>
                <td>{{ $history->other_balance }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"><b> Fee Summary </b></td>
            </tr>
            <tr>
                <td>Total Fee</td>
                <td>{{ $history->total_fee }}</td>
            </tr>
            <tr>
                <td>Total Paid Fee</td>
                <td>{{ $history->total_paid }}</td>
            </tr>
            <tr>
                <td>Total Paying Fee</td>
                <td>{{ $history->total_paying }}</td>
            </tr>
            <tr>
                <td>FG Discount</td>
                <td>{{ $history->fg_deduction }}</td>
            </tr>
            <tr>
                <td>Sponserhip Amount</td>
                <td>{{ $history->sponser_amt }}</td>
            </tr>
            <tr>
                <td>Total Balance Fee</td>
                <td>{{ $history->total_balance }}</td>
            </tr>
        </table>
    @endif
</body>

</html>
