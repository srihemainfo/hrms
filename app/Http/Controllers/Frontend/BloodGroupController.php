<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBloodGroupRequest;
use App\Http\Requests\StoreBloodGroupRequest;
use App\Http\Requests\UpdateBloodGroupRequest;
use App\Models\BloodGroup;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BloodGroupController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('blood_group_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bloodGroups = BloodGroup::all();

        return view('frontend.bloodGroups.index', compact('bloodGroups'));
    }

    public function create()
    {
        abort_if(Gate::denies('blood_group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bloodGroups.create');
    }

    public function store(StoreBloodGroupRequest $request)
    {
        $bloodGroup = BloodGroup::create($request->all());

        return redirect()->route('frontend.blood-groups.index');
    }

    public function edit(BloodGroup $bloodGroup)
    {
        abort_if(Gate::denies('blood_group_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bloodGroups.edit', compact('bloodGroup'));
    }

    public function update(UpdateBloodGroupRequest $request, BloodGroup $bloodGroup)
    {
        $bloodGroup->update($request->all());

        return redirect()->route('frontend.blood-groups.index');
    }

    public function show(BloodGroup $bloodGroup)
    {
        abort_if(Gate::denies('blood_group_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.bloodGroups.show', compact('bloodGroup'));
    }

    public function destroy(BloodGroup $bloodGroup)
    {
        abort_if(Gate::denies('blood_group_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bloodGroup->delete();

        return back();
    }

    public function massDestroy(MassDestroyBloodGroupRequest $request)
    {
        $bloodGroups = BloodGroup::find(request('ids'));

        foreach ($bloodGroups as $bloodGroup) {
            $bloodGroup->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
