<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSmsSettingRequest;
use App\Http\Requests\UpdateSmsSettingRequest;
use App\Http\Resources\Admin\SmsSettingResource;
use App\Models\SmsSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SmsSettingsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sms_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmsSettingResource(SmsSetting::all());
    }

    public function store(StoreSmsSettingRequest $request)
    {
        $smsSetting = SmsSetting::create($request->all());

        return (new SmsSettingResource($smsSetting))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmsSettingResource($smsSetting);
    }

    public function update(UpdateSmsSettingRequest $request, SmsSetting $smsSetting)
    {
        $smsSetting->update($request->all());

        return (new SmsSettingResource($smsSetting))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SmsSetting $smsSetting)
    {
        abort_if(Gate::denies('sms_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smsSetting->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
