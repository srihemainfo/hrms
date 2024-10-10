@extends('layouts.admin')
@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital@1&display=swap" rel="stylesheet">
    <style>
        .font {
            font-family: 'Merriweather', serif;
        }
    </style>
    {{-- {{ dd($results) }} --}}
    <div class="container mt-5 mb-5 font">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 border">
                <div class="text-center lh-1 mb-2 border-bottom font pb-2">
                    {{-- <img src="{{ asset('adminlogo/images.jpg') }}" alt="Admin Logo"> --}}
                    <h1 class="fw-bold pb-2">SRI HEMA INFOTECH</h1>
                    <span class="fw-normal ">No: 1A, 2nd Floor, Paper Mills Road, Perambur, Chennai, Tamil Nadu 600082</span><br> <span
                        class="fw-normal ">Payment slip for the month of
                        <b>{{ ' ' . $results->month . ' ' . $results->year }}</b></span>
                </div>
                {{-- <div class="d-flex justify-content-end"> <span>Working Branch:ROHINI</span> </div> --}}
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="row">
                            <div class="col-md-6">
                                <div> <span class="width font pl-4">Employee ID :</span> <small
                                        class="ms-3 font">{{ !isset($results->employee_id) ? '' : $results->employee_id }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div> <span class="fw-bolder font pl-4 font">Employee Name :</span> <small
                                        class="ms-3">{{ !isset($results->name) ? '' : $results->name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div><span class="width font pl-4 font">Designation :</span> <small
                                        class="ms-3">{{ !isset($results->designation) ? '' : $results->designation }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div> <span class="fw-bolder font pl-4 font">Bank Name :</span> <small
                                        class="ms-3">{{ !isset($results->bankname) ? '' : $results->bankname }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div><span class="width font pl-4">P.F.No :</span> <small
                                        class="ms-3">{{ !isset($results->PFno) ? '' : $results->PFno }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div> <span class="width font pl-4">Cheque No :</span><small
                                        class="ms-3">{{ !isset($results->chequeno) ? '' : $results->chequeno }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="mt-4 table table-bordered">
                        <thead class=" font">
                            <tr>
                                <th scope="col">Earnings</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Deductions</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        {{-- <form method="POST"
                            action="{{ route('admin.PaySlip.update', [!isset($results->id) ? '' : $results->id]) }}">
                            @csrf
                            @method('PUT') --}}
                        <tbody class="font">
                            <tr>

                                <th scope="row" class="font">Basic</th>
                                <td>{{ !isset($results->basicpay) ? '' : $results->basicpay }}</td>
                                {{-- <td class="font-weight-bold">LIC</td>
                            <td contenteditable="true"><input type="text" class="form-control" name="lic" value=" {{ !isset($results->lic) ? "" : $results->lic;   }}"></td> --}}
                                <td class="font-weight-bold">EPF</td>
                                <td>{{ !isset($results->epf) ? '' : $results->epf }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="font">AGP</th>
                                <td>
                                    {{ !isset($results->agp) ? '' : $results->agp }}</td>
                                <td class="font-weight-bold">ESI</td>
                                <td>{{ !isset($results->esi) ? '' : $results->esi }}</td>

                            </tr>
                            <tr>
                                <th scope="row" class="font">DA</th>
                                <td>{{ !isset($results->da) ? '' : $results->da }}</td>
                                <td class="font-weight-bold">IT</td>
                                <td>{{ !isset($results->it) ? '' : $results->it }}</td>

                            </tr>
                            <tr>
                                <th scope="row" class="font">Conveyance</th>
                                <td></td>
                                <td class="font-weight-bold">PT</td>
                                <td>{{ !isset($results->pt) ? '' : $results->pt }}</td>

                            </tr>
                            <tr>
                                <th scope="row" class="font">Special Pay</th>
                                <td>{{ !isset($results->specialpay) ? '' : $results->specialpay }}</td>
                                <td class="font-weight-bold">Salary Adv</td>
                                <td>{{ !isset($results->salaryadvance) ? '' : $results->salaryadvance }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="font">Arrears</th>
                                <td>{{ !isset($results->arrears) ? '' : $results->arrears }}</td>
                                <td class="font-weight-bold">Other Ded.</td>
                                <td>{{ !isset($results->otherdeduction) ? '' : $results->otherdeduction }}</td>

                            </tr>
                            <tr>
                                <th scope="row" class="font">Other All</th>
                                <td>{{ !isset($results->otherall) ? '' : $results->otherall }}</td>
                                <td class="font-weight-bold font">Total Deductions</td>
                                <td>{{ !isset($results->totaldeductions) ? '' : $results->totaldeductions }}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="font-weight-bold font">ABI</th>
                                <td>{{ !isset($results->abi) ? '' : $results->abi }}</td>

                            </tr>
                            <tr>
                                <th scope="row" class="font">Ph.D.Allow.</th>
                                <td>{{ !isset($results->phdallowance) ? '' : $results->phdallowance }}</td>
                                <td colspan="3">

                                </td>
                            </tr>

                            <tr class="border-top">
                                <th scope="row" class="font-weight-bold font">Total Earning</th>
                                <td>{{ !isset($results->earnings) ? '' : $results->earnings }}</td>
                                <td class="font-weight-bold">LOP</td>
                                <td>{{ !isset($results->lop) ? '' : $results->lop }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    Date :
                                </th>
                                <td>
                                    {{ !isset($results->date) ? '' : $results->date }}
                                </td>
                                <th>
                                    <span class="font-weight-bold font">Net Pay : </span>
                                </th>
                                <td>{{ !isset($results->netpay) ? '' : $results->netpay }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row">

                    <div class="col-md-6 pl-5 font pb-4 pt-4">
                        <span>Prepared By</span>
                    </div>
                    <div class="col-md-6 pl-5 font pb-4 pt-4">
                        <span>Authorized Signatory</span>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11 text-right pt-2" style="padding-right:0;">

                <a href="{{ URL::to('admin/payslips-pdf/' . $results->id) }}"><button
                        class="btn btn-primary save-btn">Download PDF Payslip</button></a>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
