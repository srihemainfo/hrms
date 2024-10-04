<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFundingdetaliRequest;
use App\Http\Requests\StoreFundingdetaliRequest;
use App\Http\Requests\UpdateFundingdetaliRequest;
use App\Models\Fundingdetali;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FundingdetalisController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('fundingdetali_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Fundingdetali::with(['user_name'])->select(sprintf('%s.*', (new Fundingdetali)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'fundingdetali_show';
                $editGate      = 'fundingdetali_edit';
                $deleteGate    = 'fundingdetali_delete';
                $crudRoutePart = 'fundingdetalis';

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
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : '';
            });

            $table->editColumn('topic', function ($row) {
                return $row->topic ? $row->topic : '';
            });
            $table->editColumn('remark', function ($row) {
                return $row->remark ? $row->remark : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name']);

            return $table->make(true);
        }

        return view('admin.fundingdetalis.index');
    }

    public function create()
    {
        abort_if(Gate::denies('fundingdetali_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.fundingdetalis.create', compact('user_names'));
    }

    public function store(StoreFundingdetaliRequest $request)
    {
        $fundingdetali = Fundingdetali::create($request->all());

        return redirect()->route('admin.fundingdetalis.index');
    }

    public function edit(Fundingdetali $fundingdetali)
    {
        abort_if(Gate::denies('fundingdetali_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fundingdetali->load('user_name');

        return view('admin.fundingdetalis.edit', compact('fundingdetali', 'user_names'));
    }

    public function update(UpdateFundingdetaliRequest $request, Fundingdetali $fundingdetali)
    {
        $fundingdetali->update($request->all());

        return redirect()->route('admin.fundingdetalis.index');
    }

    public function show(Fundingdetali $fundingdetali)
    {
        abort_if(Gate::denies('fundingdetali_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fundingdetali->load('user_name');

        return view('admin.fundingdetalis.show', compact('fundingdetali'));
    }

    public function destroy(Fundingdetali $fundingdetali)
    {
        abort_if(Gate::denies('fundingdetali_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fundingdetali->delete();

        return back();
    }

    public function massDestroy(MassDestroyFundingdetaliRequest $request)
    {
        $fundingdetalis = Fundingdetali::find(request('ids'));

        foreach ($fundingdetalis as $fundingdetali) {
            $fundingdetali->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
