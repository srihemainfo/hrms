<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySmsTemplateRequest;
use App\Http\Requests\StoreSmsTemplateRequest;
use App\Http\Requests\UpdateSmsTemplateRequest;
use App\Models\SmsTemplate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SmsTemplatesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('sms_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smsTemplates = SmsTemplate::all();

        return view('frontend.smsTemplates.index', compact('smsTemplates'));
    }

    public function create()
    {
        abort_if(Gate::denies('sms_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.smsTemplates.create');
    }

    public function store(StoreSmsTemplateRequest $request)
    {
        $smsTemplate = SmsTemplate::create($request->all());

        return redirect()->route('frontend.sms-templates.index');
    }

    public function edit(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.smsTemplates.edit', compact('smsTemplate'));
    }

    public function update(UpdateSmsTemplateRequest $request, SmsTemplate $smsTemplate)
    {
        $smsTemplate->update($request->all());

        return redirect()->route('frontend.sms-templates.index');
    }

    public function show(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.smsTemplates.show', compact('smsTemplate'));
    }

    public function destroy(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smsTemplate->delete();

        return back();
    }

    public function massDestroy(MassDestroySmsTemplateRequest $request)
    {
        $smsTemplates = SmsTemplate::find(request('ids'));

        foreach ($smsTemplates as $smsTemplate) {
            $smsTemplate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
