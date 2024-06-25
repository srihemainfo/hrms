@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Batch-Wise student Count
        </div>

        <div class="card-body">
            <div class="">
                <table class="table   table-bordered table-striped table-hover ajaxTable datatable">
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center">SL NO</th>
                            @php
                                $i = 1;
                            @endphp
                            <th class="text-center">Batch</th>
                            @foreach ($allCourses as $courseId => $courseName)
                            @endforeach
                            @foreach ($shortForm as $key => $short)
                                <th class="text-center">{{ $short }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allBatches as $batchId => $batchName)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>{{ $batchName }}</td>
                                @foreach ($allCourses as $courseId => $courseName)
                                    @php
                                        $count = isset($batchCourseCounts[$batchName][$courseName]) ? $batchCourseCounts[$batchName][$courseName] : 0;
                                    @endphp
                                    <td class="text-center">{{ $count }}</td>
                                @endforeach

                            </tr>
                        @endforeach

                    </tbody>
                </table>
                @php
                    // print_r($allCourses);
                @endphp
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            let table = $('.datatable-Task:not(.ajaxTable)').DataTable();
        });
    </script>
@endsection
