<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicationDetailController extends Controller
{

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('staff_publication_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (isset($request->accept)) {
            // dd($request);
            PublicationDetail::where('id', $request->id)->update(['status' => 1]);
        }
        if (!$request->updater) {

            $query = PublicationDetail::where(['user_name_id' => $request->user_name_id])->get();
// dd($query);
            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->publication_type = '';
                $query->paper_title = '';
                // $query->project_name = '';
                $query->journal_name = '';
                $query->book_series_title = '';
                $query->publisher = '';
                $query->organized_by = '';
                $query->issn_no = '';
                $query->doi = '';
                $query->proceeding_name = '';
                $query->volume_no = '';
                $query->issue = '';
                $query->pages = '';
                $query->scopus = '';
                $query->scie = '';
                $query->esci = '';
                $query->ahci = '';
                $query->ugc = '';
                $query->others = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new PublicationDetail;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->publication_type = '';
                $staff_edit->paper_title = '';
                // $staff_edit->project_name = '';
                $staff_edit->journal_name = '';
                $staff_edit->book_series_title = '';
                $staff_edit->publisher = '';
                $staff_edit->organized_by = '';
                $staff_edit->issn_no = '';
                $staff_edit->doi = '';
                $staff_edit->proceeding_name = '';
                $staff_edit->volume_no = '';
                $staff_edit->issue = '';
                $staff_edit->pages = '';
                $staff_edit->scopus = '';
                $staff_edit->scie = '';
                $staff_edit->esci = '';
                $staff_edit->ahci = '';
                $staff_edit->ugc = '';
                $staff_edit->others = '';

            }

        } else {

            // dd($request);

            $query_one = PublicationDetail::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = PublicationDetail::where(['id' => $request->id])->get();

            if (!($query_two->count() <= 0)) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;
                // dd($list);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'publication_details';

        return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
    }

    public function staff_update(Request $request)
    {
        // dd($request);
        abort_if(Gate::denies('staff_publication_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        if (!$request->id == 0 || $request->id != '') {


                $publication_check = PublicationDetail::where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update([

                    'publication_type' => $request->publication_type,
                    'paper_title' => $request->paper_title,
                    'journal_name' => isset($request->journal_name)?$request->journal_name:NULL,
                    'book_series_title' => isset($request->book_series_title)?$request->book_series_title:NULL,
                    'publisher' => $request->publisher,
                    'organized_by' => isset($request->organized_by)?$request->organized_by:NULL,
                    'proceeding_name' => isset($request->proceeding_name)?$request->proceeding_name:NULL,
                    'issn_no' => $request->issn_no,
                    'doi' => $request->doi,
                    'volume_no' => $request->volume_no,
                    'issue' => $request->issue,
                    'status'=>$request->status,
                    'indexed'=>isset($request->indexed)?$request->indexed:NULL,
                    'pages' => $request->pages,
                    'scopus' => isset($request->scopus)?$request->scopus:NULL,
                    'scie' => isset($request->scie)?$request->scie:NULL,
                    'esci' => isset($request->esci)?$request->esci:NULL,
                    'ahci' => isset($request->ahci)?$request->ahci:NULL,
                    'ugc' => isset($request->ugc)?$request->ugc:NULL,
                    'others' => isset($request->others)?$request->others:NULL
                ]);


        } else {
            $publication_check = false;
        }

        if ($publication_check) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $publication = new PublicationDetail;
           $publication->user_name_id=$request->user_name_id;
            $publication->publication_type = $request->publication_type;
            $publication->paper_title = $request->paper_title;
            $publication->journal_name = isset($request->journal_name)?$request->journal_name:NULL;
            $publication->book_series_title = isset($request->book_series_title)?$request->book_series_title:NULL;
            $publication->publisher = $request->publisher;
            $publication->organized_by = isset($request->organized_by)?$request->organized_by:NULL;
            $publication->issn_no = $request->issn_no;
            $publication->doi = $request->doi;
            $publication->proceeding_name = isset($request->proceeding_name)?$request->proceeding_name:NULL;
            $publication->volume_no = $request->volume_no;
            $publication->issue = $request->issue;
            $publication->pages = $request->pages;
            $publication->indexed=isset($request->indexed)?$request->indexed:NULL;
            $publication->scopus = isset($request->scopus)?$request->scopus:NULL;
            $publication->scie =  isset($request->scie)?$request->scie:NULL;
            $publication->esci = isset($request->esci)?$request->esci:NULL;
            $publication->ahci = isset($request->ahci)?$request->ahci:NULL;
            $publication->ugc = isset($request->ugc)?$request->ugc:NULL;
            $publication->others = isset($request->others)?$request->others:NULL;
            $publication->status='0';
            $publication->save();

            if ($publication) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.staff-publications.staff_index', $staff);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PublicationDetail  $publicationDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PublicationDetail $publicationDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PublicationDetail  $publicationDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicationDetail $publicationDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PublicationDetail  $publicationDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicationDetail $publicationDetail)
    {
        //
    }


    public function destroy($request)
    {
        // dd($request);
        $publicationDetail=PublicationDetail::find($request);
        $publicationDetail->delete();

        return back();
    }
}
