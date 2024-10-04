<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailSettingRequest;
use App\Http\Requests\UpdateEmailSettingRequest;
use App\Http\Resources\Admin\EmailSettingResource;
use App\Models\EmailSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailSettingsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('email_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmailSettingResource(EmailSetting::all());
    }

    public function store(StoreEmailSettingRequest $request)
    {
        $emailSetting = EmailSetting::create($request->all());

        return (new EmailSettingResource($emailSetting))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EmailSettingResource($emailSetting);
    }

    public function update(UpdateEmailSettingRequest $request, EmailSetting $emailSetting)
    {
        $emailSetting->update($request->all());

        return (new EmailSettingResource($emailSetting))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emailSetting->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
