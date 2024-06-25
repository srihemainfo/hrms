<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\PackUpDB;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ays = AcademicYear::pluck('name', 'id');
        if ($request->ajax()) {
            $query = PackUpDB::with('getAy:id,name')->query()->select(sprintf('%s.*', (new PackUpDB)->table));

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('ay', function ($row) {
                return $row->getAy ? $row->getAy->name : '';
            });

            $table->rawColumns(['placeholder']);

            return $table->make(true);
        }

        return view('admin.settings.index', compact('ays'));
    }

    public function packUpDb(Request $request)
    {
        if (isset($request->ay)) {
            $check = PackUpDB::where(['ay' => $request->ay])->count();
            if ($check > 0) {
                return response()->json(['status' => false, 'data' => 'Back Up Found For This AY']);
            } else {
                $tableArray = ['assignment_table','assignment_data','assignment_attendances','attendance_record','attendence_tables','examattendances','examattendance_data','exam_registration','exam_result_publish','fee_collection','grade_book','lab_examattendance_data','lab_exam_attendances','lesson_plans','marks_data','practical_marks','salarystatements','staff_biometrics','students','subject_registration'];
                foreach ($tableArray as $table) {
                    $packUpTable = $table . '_' . $request->ay;
                    Schema::dropIfExists($packUpTable);
                    $columns = DB::select(DB::raw("SHOW FULL COLUMNS FROM {$table}"));
                    $createTableSql = "CREATE TABLE {$packUpTable} (";
                    // DB::statement('CREATE TABLE ' . $packUpTable . ' AS SELECT * FROM ' . $table);
                    foreach ($columns as $column) {
                        $createTableSql .= "{$column->Field} {$column->Type}";

                        if ($column->Null === "NO") {
                            $createTableSql .= " NOT NULL";
                        } else {
                            $createTableSql .= " NULL";
                        }

                        if (!is_null($column->Default)) {
                            $createTableSql .= " DEFAULT '{$column->Default}'";
                        }

                        if (!empty($column->Extra)) {
                            $createTableSql .= " {$column->Extra}";
                        }

                        $createTableSql .= ", ";
                    }
                    $primaryKeys = DB::select(DB::raw("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'"));
                    if (!empty($primaryKeys)) {
                        $primaryKeyColumns = implode(',', array_map(function ($key) {
                            return $key->Column_name;
                        }, $primaryKeys));
                        $createTableSql .= "PRIMARY KEY ({$primaryKeyColumns}), ";
                    }

                    // Remove the trailing comma and space
                    $createTableSql = rtrim($createTableSql, ', ') . ')';

                    // Create the backup table
                    DB::statement($createTableSql);

                    $indexes = DB::select(DB::raw("SHOW KEYS FROM {$table} WHERE Key_name != 'PRIMARY'"));
                    $uniqueIndexes = [];
                    $nonUniqueIndexes = [];

                    // Group indexes by name
                    foreach ($indexes as $index) {
                        if ($index->Non_unique == 0) {
                            $uniqueIndexes[$index->Key_name][] = $index->Column_name;
                        } else {
                            $nonUniqueIndexes[$index->Key_name][] = $index->Column_name;
                        }
                    }
                    foreach ($uniqueIndexes as $indexName => $columns) {
                        $indexColumns = implode(',', $columns);
                        DB::statement("ALTER TABLE {$packUpTable} ADD UNIQUE {$indexName} ({$indexColumns})");
                    }

                    // Add non-unique indexes
                    foreach ($nonUniqueIndexes as $indexName => $columns) {
                        $indexColumns = implode(',', $columns);
                        DB::statement("ALTER TABLE {$packUpTable} ADD INDEX {$indexName} ({$indexColumns})");
                    }

                    // Copy the data from the original table to the backup table
                    DB::statement("INSERT INTO {$packUpTable} SELECT * FROM {$table}");
                }
                PackUpDB::create(['ay' => $request->ay]);
                return response()->json(['status' => true, 'data' => 'DB Packed Up Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
