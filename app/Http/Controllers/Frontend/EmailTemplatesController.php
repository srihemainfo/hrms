<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmailTemplateRequest;
use App\Http\Requests\StoreEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Models\EmailTemplate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailTemplatesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('email_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emailTemplates = EmailTemplate::all();

        return view('frontend.emailTemplates.index', compact('emailTemplates'));
    }

    public function create()
    {
        abort_if(Gate::denies('email_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emailTemplates.create');
    }

    public function store(StoreEmailTemplateRequest $request)
    {
        $emailTemplate = EmailTemplate::create($request->all());

        return redirect()->route('frontend.email-templates.index');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        abort_if(Gate::denies('email_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emailTemplates.edit', compact('emailTemplate'));
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->update($request->all());

        return redirect()->route('frontend.email-templates.index');
    }

    public function show(EmailTemplate $emailTemplate)
    {
        abort_if(Gate::denies('email_template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emailTemplates.show', compact('emailTemplate'));
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        abort_if(Gate::denies('email_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emailTemplate->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmailTemplateRequest $request)
    {
        $emailTemplates = EmailTemplate::find(request('ids'));

        foreach ($emailTemplates as $emailTemplate) {
            $emailTemplate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
