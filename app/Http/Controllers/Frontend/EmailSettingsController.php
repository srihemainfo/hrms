<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmailSettingRequest;
use App\Http\Requests\StoreEmailSettingRequest;
use App\Http\Requests\UpdateEmailSettingRequest;
use App\Models\EmailSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailSettingsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('email_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emailSettings = EmailSetting::all();

        return view('frontend.emailSettings.index', compact('emailSettings'));
    }

    public function create()
    {
        abort_if(Gate::denies('email_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emailSettings.create');
    }

    public function store(StoreEmailSettingRequest $request)
    {
        $emailSetting = EmailSetting::create($request->all());

        return redirect()->route('frontend.email-settings.index');
    }

    public function edit(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emailSettings.edit', compact('emailSetting'));
    }

    public function update(UpdateEmailSettingRequest $request, EmailSetting $emailSetting)
    {
        $emailSetting->update($request->all());

        return redirect()->route('frontend.email-settings.index');
    }

    public function show(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.emailSettings.show', compact('emailSetting'));
    }

    public function destroy(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emailSetting->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmailSettingRequest $request)
    {
        $emailSettings = EmailSetting::find(request('ids'));

        foreach ($emailSettings as $emailSetting) {
            $emailSetting->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
