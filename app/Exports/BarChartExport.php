<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarChartExport implements FromArray, WithCharts
{
    protected $labels;
    protected $data;
    protected $question;
    public $feed_name;
    public $rating;
    public $staff_name;

    public function __construct(array $labels, array $data, array $question, $feed)
    {
        $this->labels = $labels;
        $this->data = $data;
        $this->question = $question;
        $this->feed_name = $feed[0]->feedback->name;
        $this->staff_name = $feed[0]->teaching ? $feed[0]->teaching?->name : null;
        $this->rating = $feed[0]->overall_rating;
    }

    public function array(): array
    {
        // Convert data to a format suitable for Excel
        $result = [['Questions', $this->rating . ' Scale (%)']];
        foreach ($this->question as $index => $label) {
            $result[] = [$label, $this->data[$index]];
        }
        return $result;
    }

    public function charts(): array
    {

        return [
            $this->createBarChart()
        ];
    }

    private function createBarChart()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        // Add data to worksheet
        $worksheet->fromArray($this->array(), NULL, 'A1');

        // Define data series
        $dataSeriesLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', NULL, 1)];
        $dataSeriesValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$' . (count($this->data) + 1), NULL, count($this->data))];


        $chartData = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
            \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,
            \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_CLUSTERED,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . (count($this->data) + 1), NULL, count($this->data))],
            $dataSeriesValues
        );

        // Create the Plot Area
        $plotArea = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, [$chartData]);

        // Create the Chart
        if ($this->staff_name != null) {
            $chart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart1',
                new Title($this->feed_name . '-' . $this->staff_name),
                new Legend(Legend::POSITION_RIGHT, NULL, false),
                $plotArea
            );
        } else {
            $chart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart1',
                new Title($this->feed_name),
                new Legend(Legend::POSITION_RIGHT, NULL, false),
                $plotArea
            );
        }

        $chart->setTopLeftPosition('D5');
        $chart->setBottomRightPosition('N20');

        $worksheet->addChart($chart);

        return $chart;
    }
}
