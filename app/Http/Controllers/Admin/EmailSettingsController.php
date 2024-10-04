<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmailSettingRequest;
use App\Http\Requests\StoreEmailSettingRequest;
use App\Http\Requests\UpdateEmailSettingRequest;
use App\Models\EmailSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmailSettingsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('email_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EmailSetting::query()->select(sprintf('%s.*', (new EmailSetting)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'email_setting_show';
                $editGate      = 'email_setting_edit';
                $deleteGate    = 'email_setting_delete';
                $crudRoutePart = 'email-settings';

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
            $table->editColumn('host_name', function ($row) {
                return $row->host_name ? $row->host_name : '';
            });
            $table->editColumn('user_name', function ($row) {
                return $row->user_name ? $row->user_name : '';
            });
            $table->editColumn('password', function ($row) {
                return $row->password ? $row->password : '';
            });
            $table->editColumn('smtp_secure', function ($row) {
                return $row->smtp_secure ? $row->smtp_secure : '';
            });
            $table->editColumn('port_no', function ($row) {
                return $row->port_no ? $row->port_no : '';
            });
            $table->editColumn('from', function ($row) {
                return $row->from ? $row->from : '';
            });
            $table->editColumn('to', function ($row) {
                return $row->to ? $row->to : '';
            });
            $table->editColumn('cc', function ($row) {
                return $row->cc ? $row->cc : '';
            });
            $table->editColumn('bcc', function ($row) {
                return $row->bcc ? $row->bcc : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.emailSettings.index');
    }

    public function create()
    {
        abort_if(Gate::denies('email_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.emailSettings.create');
    }

    public function store(StoreEmailSettingRequest $request)
    {
        $emailSetting = EmailSetting::create($request->all());

        return redirect()->route('admin.email-settings.index');
    }

    public function edit(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.emailSettings.edit', compact('emailSetting'));
    }

    public function update(UpdateEmailSettingRequest $request, EmailSetting $emailSetting)
    {
        $emailSetting->update($request->all());

        return redirect()->route('admin.email-settings.index');
    }

    public function show(EmailSetting $emailSetting)
    {
        abort_if(Gate::denies('email_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.emailSettings.show', compact('emailSetting'));
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
