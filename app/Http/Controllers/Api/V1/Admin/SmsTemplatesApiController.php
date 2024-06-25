<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSmsTemplateRequest;
use App\Http\Requests\UpdateSmsTemplateRequest;
use App\Http\Resources\Admin\SmsTemplateResource;
use App\Models\SmsTemplate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SmsTemplatesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sms_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmsTemplateResource(SmsTemplate::all());
    }

    public function store(StoreSmsTemplateRequest $request)
    {
        $smsTemplate = SmsTemplate::create($request->all());

        return (new SmsTemplateResource($smsTemplate))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmsTemplateResource($smsTemplate);
    }

    public function update(UpdateSmsTemplateRequest $request, SmsTemplate $smsTemplate)
    {
        $smsTemplate->update($request->all());

        return (new SmsTemplateResource($smsTemplate))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smsTemplate->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
