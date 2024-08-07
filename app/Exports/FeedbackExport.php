<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\PlotSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;

class FeedbackExport implements FromArray, WithCharts
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     //
    // }

    protected $data;
    protected $labels;

    public function __construct($data, $labels)
    {
        $this->data = $data;
        $this->labels = $labels;
    }

    public function array(): array
    {
        return [
            ['Labels', 'Data'],
            array_map(null, $this->labels, $this->data)
        ];
    }

    public function charts(): array
    {
        return [
            $this->createBarChart()
        ];
    }

    protected function createBarChart()
    {
        return function (Worksheet $sheet) {
            $labels = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet1!$A$2:$A$' . (count($this->labels) + 1), null, count($this->labels));
            $data = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Sheet1!$B$2:$B$' . (count($this->data) + 1), null, count($this->data));

            $series = new DataSeries(
                DataSeries::TYPE_BARCHART,
                DataSeries::GROUPING_CLUSTERED,
                range(0, count($this->data) - 1),
                [$labels],
                [$data]
            );

            $plotArea = new PlotArea(null, [$series]);
            $chart = new Chart(
                'bar_chart',
                new Title('Feedback Data'),
                new Legend(Legend::POSITION_BOTTOM, null, false),
                $plotArea
            );

            $chart->setTopLeftPosition('D2');
            $chart->setBottomRightPosition('N15');

            $sheet->addChart($chart);
        };
    }
}
