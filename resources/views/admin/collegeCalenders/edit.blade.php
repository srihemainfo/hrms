@extends('layouts.admin')
@section('content')

    <style>
        .year {
            padding: 30px;
            border: 1px solid #E0E0E0;
            border-radius: 4px;
            display: flex;
            background: #FFF;
        }

        li.editable span {
            margin-right: auto;
        }


        .year ul {
            display: inline-block;
            padding: 9px;
            margin: 0 1px;
            list-style: none;
            flex: 1;

            position: relative;

            &:before {
                position: absolute;
                content: attr(data-month);
                /* color: transparent; */
                text-shadow: 0 0 0 rgba(0, 0, 0, 0.25);
                font-family: sans-serif;
                font-weight: 700;
                font-size: 110%;
                top: -19px;
                left: 6px;
                right: 0px;

            }

            &:first-child {
                margin-left: 0;
            }

            &:last-child {
                margin-right: 0;
            }
        }

        .year ul li {
            padding: 0;
            margin: 0;
            /* height: 22px; */
            box-shadow: inset 0 0 0 1px #f0f0f0;
            background: #F8F8F8;

            &:before {
                /* content: attr(data-date); */
                color: transparent;
                text-shadow: 0 0 0 rgba(0, 0, 0, 0.25);
                text-align: right;
                /* top: -19px; */
                /* position: absolute; */
                line-height: 24px;
                font-size: 20px;
            }

            &[data-day="0"],
            &[data-day="0"] {
                background: #af5d5d;
                box-shadow: inset 0 0 0 1px #E0E0E0;
                z-index: -19999;
            }

        }

        #edit-day {
            max-width: 300px;
            /* Optional: Set max-width to limit the width of the select box */
        }

        .select3 {
            width: 30%;
            background-color: #f4f4f4;
            border-color: #d2d6de;
            border-radius: 4px;
            color: #212529;
            font-size: 14px;
            padding-bottom: 2px;
        }
    </style>
    <?php
    
    foreach ($startDates as $record) {
        // dd($record);
        // ;03,batch
        $id = $record->id;
        $start_date = $record->start_date;
        $end_date = $record->end_date;
        $date = $record->date;
        $dayorder = $record->dayorder;
        $bookmark = $record->bookmark;
        // $semester_type = $record->semester_type;
        $academic_year = $record->academic_year;
    }
    // $batch = $startDates[0]->batch;
    
    $startYear = date('Y', strtotime($startDates[0]->start_date));
    $endYear = date('Y', strtotime($startDates[0]->end_date));
    $startMonth = date('m', strtotime($startDates[0]->start_date));
    $endMonth = date('m', strtotime($startDates[0]->end_date));
    ?>
    <div class="container">
        <div class="card">

            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ $academic_year ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row year" style="position:relative; ">
        <?php
$startDatesArray = $startDates->toArray();

for ($year = $startYear; $year <= $endYear; $year++) {

    // Determine the start and end month for the current year
    $currentStartMonth = ($year == $startYear) ? $startMonth : 1;
    $currentEndMonth = ($year == $endYear) ? $endMonth : 12;

    for ($month = $currentStartMonth; $month <= $currentEndMonth; $month++) {
        $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
        $monthName = date('F', mktime(0, 0, 0, $month, 10));
        ?>


        <?php
        echo '<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-4 year_label" data-year="' . $year . '" style="padding-bottom:2.5rem;"><div style="font-size:2rem;padding-bottom:10px;">' . $year . '</div><ul class="' . $formattedMonth . ' month" data-month="' . $monthName . '">';
        
        ?>

        <?php
        $i =0;
        foreach ($startDatesArray as $object) {
            $i += 1;
            $date = $object->date;
            // $batch = $object->batch;
            $currentYear = date('Y', strtotime($date));
            $currentMonth = date('m', strtotime($date));
            $day = date('d', strtotime($date));
            $dayName = date('l', strtotime($date));
            $dayNameShort = '(' . substr($dayName, 0, 3) . ')';
            $style = '';
            
            if ($currentYear != $year || $currentMonth != $formattedMonth) {
                continue;
            }
            
            $dayorder = $object->dayorder;


                // Add additional styles based on the dayorder
                if ($dayorder == 1 || $dayorder == 2) {
                    $style .= 'background: #FFD5D6; box-shadow: inset 0 0 0 1px #E0E0E0;width: 150px; display: flex; justify-content: space-between; ';
                } elseif ($dayorder == 3) {
                    $style = 'background: #FFD5D6; box-shadow: inset 0 0 0 1px #E0E0E0;width: 150px; display: flex; justify-content: space-between; ';
                } elseif ($dayorder == 0) {
                    $style = 'width: 150px; display: flex; justify-content: space-between; ';
                } elseif ($dayorder == 4) {
                    $style = 'background: #FFD5D6; box-shadow: inset 0 0 0 1px #E0E0E0;color:#010101;width: 150px; display: flex; justify-content: space-between; ';
                    $dayNameShort = 'Holiday';
                } elseif ($dayorder == 5) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = 'NoOrderDay';
                } elseif ($dayorder == 6) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '';
                } elseif ($dayorder == 7) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '(Tue)';
                } elseif ($dayorder == 8) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '(Wed)';
                } elseif ($dayorder == 9) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '(Thu)';
                } elseif ($dayorder == 10) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '(Fri)';
                } elseif ($dayorder == 11) {
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '(Sat)';
                } elseif($dayorder == 20){
                    $style = 'width: 150px; display: flex; justify-content: space-between;';
                    $dayNameShort = '(Mon)';
                }

                if ($dayorder == 1 || $dayorder == 2) {
                    $dayNameShort = '';
                }

                echo '<li data-date="' . $day . '" data-day="' . $dayNameShort . '" data-month="' . $formattedMonth . '" style="' . $style . '" class="editable">';
                echo $day. '&nbsp;&nbsp;'  . $dayName. '<span> </span>';
                echo '<select id="edit-day" class="select3 " style="float:right; width: 65px;" name="">';
                echo '<option value="00">Select</option>';
                echo '<option value="Holiday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "Holiday" ? ' selected' : '') . '>Holiday</option>';
                echo '<option value="No_order_day" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "NoOrderDay" ? ' selected' : '') . '>No order day</option>';
                echo '<option value="Monday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "(Mon)" ? ' selected' : '') . '>Monday</option>';
                echo '<option value="Tuesday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "(Tue)" ? ' selected' : '') . '>Tuesday</option>';
                echo '<option value="Wednesday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "(Wed)" ? ' selected' : '') . '>Wednesday</option>';
                echo '<option value="Thursday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "(Thu)" ? ' selected' : '') . '>Thursday</option>';
                echo '<option value="Friday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "(Fri)" ? ' selected' : '') . '>Friday</option>';
                echo '<option value="Saturday" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '"' . ($dayNameShort == "(Sat)" ? ' selected' : '') . '>Saturday</option>';
                echo '<option value="Reset" data-month="' . $formattedMonth . '" data-datee="' . $day . '" data-year="' . $currentYear . '">Reset</option>';
                echo '</select>';
                echo '</li>';

            }

            echo '</ul></div>';
        }


    }


    ?>
    </div>
    <?php
    echo '<div style="padding-top:30px;text-align: center;">';
    echo '<button id="save" class="enroll_generate_bn">Save</button>';
    echo '</div>'; ?>


    {{-- {{ dd($dayorder) }} --}}
@endsection

@section('scripts')
    @parent

    <script>
        $(function() {
            console.clear();
            $(".year").each(function() {
                fillCalendar($(this).attr('data-year'));
            });
        });

        function fillCalendar(year) {
            var i;
            for (i = 0; i < 12; i++) {
                renderMonth(i + 1, year);
            }
        }

        function renderMonth(month, year) {
            var first_day = new Date(year + "-" + month),
                last_day = new Date();
            last_day.setYear(year);
            last_day.setMonth(month);
            last_day.setDate(0);

            // var i, l = last_day.getDate() + 1,
            //     d;
            // for (i = 1; i < l; i++) {
            //     d = new Date(year + "-" + month + "-" + i);
            //     $(".year[data-year='" + year + "'] ." + month).append("<li data-day=\"" + d.getDay() + "\"  data-date=\"" +
            //         i + "\" ></li>");
            // }
        }
        $(document).ready(function() {
            var daysToBeUpdated = [];
            $('.editable').on('change', function() {
                var dayElement = $(this);
                var dayName = dayElement.val();
                var dayId = dayElement.data('date');
                var batch = dayElement.closest('.year_label').data(
                    'batch'); // Retrieve the batch value from the parent '.year' element
                var dayMonth = dayElement.data('month');
                var dayYear = $(".year_label").data('year');
                var dayOrder = dayElement.index() + 1;

                if (dayName !== "Please select") {
                    daysToBeUpdated.push({
                        day_id: dayId,
                        day_name: dayName,
                        day_month: dayMonth,
                        day_year: dayYear,
                        day_order: dayOrder,
                        batch: batch
                    });
                } else {
                    for (var i = 0; i < daysToBeUpdated.length; i++) {
                        if (daysToBeUpdated[i].day_id === dayId) {
                            daysToBeUpdated.splice(i, 1);
                            break;
                        }
                    }
                }
            });

            $('#save').on('click', function() {
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var month = {};

                $('.select3').each(function(index, val) {
                    if ($(val).val() != "00") {
                        var monthNumber = $(this).find(':selected').data('month');
                        var batch = $(this).closest('.year_label').data('batch');
                        var monthString = monthNumber < 10 ? monthNumber : monthNumber;
                        var key = $(this).find(':selected').data('year') + '-' + monthString + '-' +
                            $(this).find(':selected').data('datee');
                        month[key] = {
                            value: $(this).val(),
                            batch: batch
                        };
                    }
                });

                $.ajax({
                    url: '/admin/update-day',
                    type: 'POST',
                    data: {
                        month: month,
                        accYear: '{{ $academic_year ?? '' }}',
                        // semType: '{{ $semester_type ?? '' }}',

                        _token: csrf_token
                    },
                    success: function(response) {
                        if (response.message === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                            }).then(function() {
                                location.reload();
                            });
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error',
                                    'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }

                });
            });
        });
    </script>
@stop
