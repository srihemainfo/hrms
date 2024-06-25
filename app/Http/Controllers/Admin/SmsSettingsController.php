<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySmsSettingRequest;
use App\Http\Requests\StoreSmsSettingRequest;
use App\Http\Requests\UpdateSmsSettingRequest;
use App\Models\SmsSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SmsSettingsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sms_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SmsSetting::query()->select(sprintf('%s.*', (new SmsSetting)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'sms_setting_show';
                $editGate      = 'sms_setting_edit';
                $deleteGate    = 'sms_setting_delete';
                $crudRoutePart = 'sms-settings';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('key', function ($row) {
                return $row->key ? $row->key : '';
            });
            $table->editColumn('url', function ($row) {
                return $row->url ? $row->url : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.smsSettings.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sms_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.smsSettings.create');
    }

    public function store(StoreSmsSettingRequest $request)
    {
        $smsSetting = SmsSetting::create($request->all());

        return redirect()->route('admin.sms-settings.index');
    }

    public function edit(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.smsSettings.edit', compact('smsSetting'));
    }

    public function update(UpdateSmsSettingRequest $request, SmsSetting $smsSetting)
    {
        $smsSetting->update($request->all());

        return redirect()->route('admin.sms-settings.index');
    }

    public function show(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.smsSettings.show', compact('smsSetting'));
    }

    public function destroy(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smsSetting->delete();

        return back();
    }

    public function massDestroy(MassDestroySmsSettingRequest $request)
    {
        $smsSettings = SmsSetting::find(request('ids'));

        foreach ($smsSettings as $smsSetting) {
            $smsSetting->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
