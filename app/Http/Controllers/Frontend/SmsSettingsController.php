<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySmsSettingRequest;
use App\Http\Requests\StoreSmsSettingRequest;
use App\Http\Requests\UpdateSmsSettingRequest;
use App\Models\SmsSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SmsSettingsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('sms_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smsSettings = SmsSetting::all();

        return view('frontend.smsSettings.index', compact('smsSettings'));
    }

    public function create()
    {
        abort_if(Gate::denies('sms_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.smsSettings.create');
    }

    public function store(StoreSmsSettingRequest $request)
    {
        $smsSetting = SmsSetting::create($request->all());

        return redirect()->route('frontend.sms-settings.index');
    }

    public function edit(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.smsSettings.edit', compact('smsSetting'));
    }

    public function update(UpdateSmsSettingRequest $request, SmsSetting $smsSetting)
    {
        $smsSetting->update($request->all());

        return redirect()->route('frontend.sms-settings.index');
    }

    public function show(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.smsSettings.show', compact('smsSetting'));
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
