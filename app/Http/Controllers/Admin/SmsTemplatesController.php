<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySmsTemplateRequest;
use App\Http\Requests\StoreSmsTemplateRequest;
use App\Http\Requests\UpdateSmsTemplateRequest;
use App\Models\SmsTemplate;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SmsTemplatesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sms_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SmsTemplate::query()->select(sprintf('%s.*', (new SmsTemplate)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'sms_template_show';
                $editGate      = 'sms_template_edit';
                $deleteGate    = 'sms_template_delete';
                $crudRoutePart = 'sms-templates';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('sender', function ($row) {
                return $row->sender ? $row->sender : '';
            });
            $table->editColumn('template', function ($row) {
                return $row->template ? $row->template : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? $row->type : '';
            });
            $table->editColumn('content', function ($row) {
                return $row->content ? $row->content : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.smsTemplates.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sms_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.smsTemplates.create');
    }

    public function store(StoreSmsTemplateRequest $request)
    {
        $smsTemplate = SmsTemplate::create($request->all());

        return redirect()->route('admin.sms-templates.index');
    }

    public function edit(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.smsTemplates.edit', compact('smsTemplate'));
    }

    public function update(UpdateSmsTemplateRequest $request, SmsTemplate $smsTemplate)
    {
        $smsTemplate->update($request->all());

        return redirect()->route('admin.sms-templates.index');
    }

    public function show(SmsTemplate $smsTemplate)
    {
        abort_if(Gate::denies('sms_template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.smsTemplates.show', compact('smsTemplate'));
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
