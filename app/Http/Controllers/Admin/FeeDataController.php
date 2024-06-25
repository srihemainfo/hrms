<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\AcademicYear;
use App\Models\AdmissionMode;
use App\Models\Batch;
use App\Models\Scholarship;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FeeDataController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('fee_structure')
                ->whereNull('fee_structure.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
                ->leftJoin('batches', 'batches.id', '=', 'fee_structure.batch_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_structure.course_id')
                ->leftJoin('academic_years', 'academic_years.id', '=', 'fee_structure.academic_year_id')
                ->leftJoin('admission_mode', 'admission_mode.id', '=', 'fee_structure.admission_id')
                ->select('fee_structure.id', 'users.name as user', 'academic_years.name as ay', 'tools_courses.short_form as course', 'batches.name as batch', 'admission_mode.name as admission', 'fee_structure.generation_status')->get();

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'fee_structure_show';
                $editGate = 'fee_structure_edit';
                $deleteGate = 'fee_structure_delete';
                $viewFunct = 'viewfeeStructure';
                $editFunct = 'editfeeStructure';
                $deleteFunct = 'deletefeeStructure';
                $crudRoutePart = 'fee-structure';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->batch ? $row->batch : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->course ? $row->course : '';
            });
            $table->editColumn('ay', function ($row) {
                return $row->ay ? $row->ay : '';
            });
            $table->editColumn('admission', function ($row) {
                return $row->admission ? $row->admission : '';
            });

            $table->editColumn('user', function ($row) {
                return $row->user ? $row->user : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $degreeType = ToolsDegreeType::pluck('name', 'id');
        $course = ToolsCourse::pluck('short_form', 'id');
        $admission = AdmissionMode::pluck('name', 'id');
        $scholarship = Scholarship::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        return view('admin.feeData.index', compact('scholarship', 'admission', 'course', 'degreeType', 'ay', 'batch'));
    }

}
