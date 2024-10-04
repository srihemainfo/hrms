<style>
    .box,
    .table {
        display: none;
    }
</style>
<div class="container">

    <div class="row gutters">
        {{-- {{ dd($staff); }} --}}

        <div class="col" style="padding:0;">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.staff-publications.staff_update', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $staff_edit->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h5 class="mb-2 text-primary">Publication Details</h5>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="publication_type">Publication type</label>
                                    <select name="publication_type" id="publication_type" required
                                        class="form-control form-select-lg mb-3" aria-label=".form-select-lg example"
                                        data-style="btn-outline-secondary">
                                        <option value=""
                                            {{ $staff_edit->publication_type == '' ? 'selected' : '' }}>
                                            Please Select</option>
                                        <option value="Journal"
                                            {{ $staff_edit->publication_type == 'Journal' ? 'selected' : '' }}>Journal
                                        </option>
                                        <option value="Conference"
                                            {{ $staff_edit->publication_type == 'Conference' ? 'selected' : '' }}>
                                            Conference
                                        </option>
                                        <option value="Text_Book"
                                            {{ $staff_edit->publication_type == 'Text Book' ? 'selected' : '' }}>Text
                                            Book
                                        </option>
                                        <option value="Book_Chapter"
                                            {{ $staff_edit->publication_type == 'Book Chapter' ? 'selected' : '' }}>Book
                                            Chapter</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="TitleDiv">
                                <div class="form-group">
                                    <label for="paper_title" class="title">Title</label>
                                    <input type="text" class="form-control" id="paper_title" name="paper_title"
                                        placeholder="Enter Title " value="{{ $staff_edit->paper_title }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Journal">
                                <div class="form-group">
                                    <label for="journal_name" class="name">Journal Name</label>
                                    <input type="text" class="form-control" id="journal_name" name="journal_name"
                                        placeholder="Enter Journal Name" value="{{ $staff_edit->journal_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="titleofbook">
                                <div class="form-group">
                                    <label for="book_series_title">Title of Book Series</label>
                                    <input type="text" class="form-control" name="book_series_title"
                                        placeholder="Enter Title of Book Series"
                                        value="{{ $staff_edit->book_series_title }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Publisher">
                                <div class="form-group">
                                    <label for="publisher">Publisher</label>
                                    <input type="text" class="form-control" name="publisher"
                                        placeholder="Enter Publisher" value="{{ $staff_edit->publisher }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Organized">
                                <div class="form-group">
                                    <label for="organized_by">Organized By</label>
                                    <input type="text" class="form-control" name="organized_by"
                                        placeholder="Enter Organized By" value="{{ $staff_edit->organized_by }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="ISBN">
                                <div class="form-group">
                                    <label for="issn_no">ISBN/ISSN No</label>
                                    <input type="text" class="form-control" name="issn_no"
                                        placeholder="Enter ISBN/ISSN No" value="{{ $staff_edit->issn_no }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="DOI">
                                <div class="form-group">
                                    <label for="doi">DOI</label>
                                    <input type="text" class="form-control" name="doi" placeholder="Enter DOI"
                                        value="{{ $staff_edit->doi }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Proceedings">
                                <div class="form-group">
                                    <label for="proceeding_name">Proceedings Name</label>
                                    <input type="text" class="form-control" name="proceeding_name"
                                        placeholder="Enter Proceedings Name"
                                        value="{{ $staff_edit->proceeding_name }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Volume">
                                <div class="form-group">
                                    <label for="volume_no">Volume No</label>
                                    <input type="text" class="form-control" name="volume_no"
                                        placeholder="Enter Volume No" value="{{ $staff_edit->volume_no }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Issue">
                                <div class="form-group">
                                    <label for="issue">Issue</label>
                                    <input type="text" class="form-control" name="issue"
                                        placeholder="Enter Issue" value="{{ $staff_edit->issue }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Pages">
                                <div class="form-group">
                                    <label for="pages">Pages</label>
                                    <input type="text" class="form-control" name="pages"
                                        placeholder="Enter Pages" value="{{ $staff_edit->pages }}">

                                </div>
                            </div>
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Scopus"> --}}

                            {{-- </div> --}}
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 box" style="display: flex"
                                id="SCIE">
                                <div class="form-check box m-2" id="check">
                                    <label class="form-check-label" for="indexed">
                                        Indexed
                                    </label>
                                    <input type="checkbox" value="1" id="indexed" name="indexed"
                                        {{ isset($staff_edit->indexed) ? ($staff_edit->indexed == 1 ? 'Checked' : '') : '' }}>

                                </div>
                                <div class="form-group m-2" id="Scopus">
                                    <label for="scopus">Scopus</label>
                                    <input type="checkbox" class="form-check-label" name="scopus" value="Yes"
                                        {{ $staff_edit->scopus == 'Yes' ? 'Checked' : '' }}>
                                </div>
                                <div class="form-group m-2">
                                    <label for="scie">SCIE(WOS)</label>
                                    <input type="checkbox" class="form-check-label" name="scie" value="Yes"
                                        {{ $staff_edit->scie == 'Yes' ? 'Checked' : '' }}>
                                </div>
                                <div class="form-group m-2" id="ESCI">
                                    <label for="esci">ESCI(WOS)</label>
                                    <input type="checkbox" class="form-check-label" name="esci" value="Yes"
                                        {{ $staff_edit->esci == 'Yes' ? 'Checked' : '' }}>
                                </div>
                                <div class="form-group m-2" id="AHCI">
                                    <label for="ahci">AHCI(WOS)</label>
                                    <input type="checkbox" class="form-check-label" name="ahci" value="Yes"
                                        {{ $staff_edit->ahci == 'Yes' ? 'Checked' : '' }}>
                                </div>
                                <div class="form-group m-2" id="UGC">
                                    <label for="ugc">UGC</label>
                                    <input type="checkbox" class="form-check-label" name="ugc"value="Yes"
                                        {{ $staff_edit->ugc == 'Yes' ? 'Checked' : '' }}>
                                </div>
                                <input type="hidden" name="status" value="0">

                                <div class="form-group m-2" id="Others">
                                    <label for="others">Others</label>
                                    <input type="checkbox" class="form-check-label" name="others"value="Yes"
                                        {{ $staff_edit->others == 'Yes' ? 'Checked' : '' }}>
                                </div>
                            </div>
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="ESCI">

                            </div> --}}
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="AHCI">

                            </div> --}}
                            {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="UGC">

                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 box" id="Others">

                            </div> --}}



                        </div>

                        <div class="row gutters" id="button" style="display: none">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">
                                    {{-- <button type="button" id="cancel" name="cancel"
                                        class="btn btn-secondary">Cancel</button> --}}
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary Edit">{{ $staff_edit->add }}</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (count($list) > 0)
        <div class="row gutters mt-3 mb-3 table" id="main">
            <div class="col" style="padding:0;">
                <div class="card h-100">
                    @php
                        $user = auth()->user();

                        if ($user) {
                            // Get the user's ID
                            $userId = $user->id;
                        } else {
                        }
                    @endphp
                    <div class="card-body table-responsive">
                        <h5 class="mb-3 text-primary">Publication List </h5>
                        <table class="list_table">
                            <thead>
                                <tr>
                                    <th id="Title_1" class="box Title_1">
                                        Title
                                    </th>
                                    <th id="Journal_Name_1" class="box Journal_Name_1">
                                        Journal Name
                                    </th>
                                    <th id="Title_of_Book_Series_1" class="box">
                                        Title of Book Series
                                    </th>
                                    <th id="Publisher_1" class="box">
                                        Publisher
                                    </th>
                                    <th id="Organized_By_1" class="box">
                                        Organized By
                                    </th>
                                    <th id="ISBN_No_1" class="box">
                                        ISBN/ISSN No
                                    </th>
                                    <th id="DOI_1" class="box">
                                        DOI
                                    </th>
                                    <th id="Proceedings_Name_1" class="box">
                                        Proceedings Name
                                    </th>
                                    <th id="Volume_No_1" class="box">
                                        Volume No
                                    </th>
                                    <th id="Issue_1" class="box">
                                        Issue
                                    </th>
                                    <th id="Pages_1" class="box">
                                        Pages
                                    </th>
                                    <th id="Scopus_1" class="box">
                                        Scopus
                                    </th>
                                    <th id="SCIE_1" class="box">
                                        SCIE(WOS)
                                    </th>
                                    <th id="ESCI_1" class="box">
                                        ESCI(WOS)
                                    </th>
                                    <th id="AHCI_1" class="box">
                                        AHCI(WOS)
                                    </th>
                                    <th id="UGC_1" class="box">
                                        UGC
                                    </th>
                                    <th id="Others_1" class="box">
                                        Others
                                    </th>
                                    <th id="status">
                                        status
                                    </th>
                                    <th id="Action">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($list); $i++)
                                    <tr class="publication-row"
                                        data-publication-type="<?= $list[$i]->publication_type ?>">
                                        <td class="box col1-data" id="publication_type_2" class="box">
                                            {{ $list[$i]->publication_type }}</td>
                                        <td class="box col2-data" id="paper_title_2">{{ $list[$i]->paper_title }}
                                        </td>
                                        <td class="box col3-data" id="journal_name_2">{{ $list[$i]->journal_name }}
                                        </td>
                                        <td class="box col4-data" id="book_series_title_2">
                                            {{ $list[$i]->book_series_title }}</td>
                                        <td class="box col5-data" id="publisher_2">{{ $list[$i]->publisher }}</td>
                                        <td class="box col6-data" id="organized_by_2">{{ $list[$i]->organized_by }}
                                        </td>
                                        <td class="box col7-data" id="issn_no_2">{{ $list[$i]->issn_no }}</td>
                                        <td class="box col8-data" id="doi_2">{{ $list[$i]->doi }}</td>
                                        <td class="box col9-data" id="proceeding_name_2">
                                            {{ $list[$i]->proceeding_name }}
                                        </td>
                                        <td class="box col10-data" id="volume_no_2">{{ $list[$i]->volume_no }}</td>
                                        <td class="box col11-data" id="issue_2">{{ $list[$i]->issue }}</td>
                                        <td class="box col12-data" id="pages_2">{{ $list[$i]->pages }}</td>
                                        <td class="box col13-data" id="scopus_2">{{ $list[$i]->scopus }}</td>
                                        <td class="box col14-data" id="scie_2">{{ $list[$i]->scie }}</td>
                                        <td class="box col15-data" id="esci_2">{{ $list[$i]->esci }}</td>
                                        <td class="box col16-data" id="ahci_2">{{ $list[$i]->ahci }}</td>
                                        <td class="box col17-data" id="ugc_2">{{ $list[$i]->ugc }}</td>
                                        <td class="box col18-data" id="others_2">{{ $list[$i]->others }}</td>
                                        {{-- <td>{{ $list[$i]->contribution_of_conference }}</td> --}}
                                        <td>
                                            @if ($userId == 1)
                                                @if ($list[$i]->status == '0')
                                                    <form method="POST"
                                                        action="{{ route('admin.staff-publications.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                        enctype="multipart/form-data"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                        @csrf
                                                        <button type="submit" name="accept" value="accept"
                                                            class="btn btn-success  ">Accept</button>
                                                    </form>

                                                    <form
                                                        action="{{ route('admin.staff-publications.destroy', $list[$i]->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token"
                                                            value="{{ csrf_token() }}">
                                                        <input type="submit" class="btn  btn-danger mt-2"
                                                            value="{{ 'Reject' }}">
                                                    </form>
                                                @endif

                                                @if ($list[$i]->status == '1')
                                                    <div class="p-2 Approved">
                                                        Approved </div>
                                                @endif
                                            @endif

                                            @if ($userId == $staff->user_name_id)
                                                @if ($list[$i]->status == '0')
                                                    <div class="p-2 Pending">
                                                        Pending </div>
                                                @elseif ($list[$i]->status == '1')
                                                    <div class="p-2 Approved">
                                                        Approved</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.staff-publications.staff_updater', ['user_name_id' => $staff->user_name_id, 'name' => $staff->name, 'id' => $list[$i]->id]) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="selectedValue"
                                                    value="{{ $list[$i]->publication_type }}" />
                                                <!-- Add a hidden input field to store the selected value -->
                                                <button type="submit" id="updater" name="updater" value="updater"
                                                    class="btn  btn-info Edit">Edit</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.staff-publications.destroy', $list[$i]->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn  btn-danger mt-2"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {


            $('.box').hide();
            $('.table').hide();
            // $('#button').hide();
            var selectedValue = $('#publication_type').val();
            $('#publication_type').on("change", function() {
                selectedValue = $(this).val();
                $('.box').hide();
                $('.table').show();
                if (selectedValue === 'Journal') {
                    $('#TitleDiv, #Journal, #Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages, #check,#Scopus,#SCIE,#ESCI,#AHCI,#UGC,#Others')
                        .show();
                    $('#Organized,#Proceedings').hide();

                    $('.Title_1').text('Tittle of Paper');
                    $('.title').text('Tittle of Paper');
                    $('.name').text('Journal Name');
                    $('.Journal_Name_1').text('Journal Name');

                    $('#Title_1, #Journal_Name_1, #Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1,#Scopus_1,#SCIE_1,#ESCI_1,#AHCI_1,#UGC_1,#Others_1')
                        .show();
                    $('#Organized_By_1,#Proceedings_Name_1,#Title_of_Book_Series_1').hide();

                    $('#organized_by_2,#proceeding_name_2,#book_series_title_2').hide();

                    $('#paper_title_2, #journal_name_2, #publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2,#scopus_2,#scie_2,#esci_2,#ahci_2,#ugc_2,#others_2')
                        .show();

                    $('#button').show();

                }

                if (selectedValue === 'Conference') {
                    $('#TitleDiv, #Journal,#Organized,#Proceedings,#Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages, #check,#Scopus,#SCIE,#ESCI,#AHCI,#UGC,#Others')
                        .show();
                    $('.Title_1').text('Tittle of Paper');
                    $('.title').text('Tittle of Paper');
                    $('.name').text('Conference Name');
                    $('.Journal_Name_1').text('Conference Name');

                    $('#Title_1, #Journal_Name_1,#Organized_By_1,#Proceedings_Name_1,#Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1,#Scopus_1,#SCIE_1,#ESCI_1,#AHCI_1,#UGC_1,#Others_1,#Title_of_Book_Series_1')
                        .show();

                    $('#paper_title_2, #journal_name_2,#organized_by_2,#proceeding_name_2,#publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2,#scopus_2,#scie_2,#esci_2,#ahci_2,#ugc_2,#others_2,#book_series_title_2')
                        .show();
                    $('#button').show();

                }
                if (selectedValue === 'Text_Book') {

                    $('#TitleDiv,#Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages').show();

                    $('#Organized,#Proceedings, #Journal,#check,#Scopus,#SCIE,#ESCI,#AHCI,#UGC,#Others')
                        .hide();
                    $('.title').text('Tittle of Book');

                    $('.Title_1').text('Tittle of Book');

                    $('#Title_1,#Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1').show();

                    $('#paper_title_2,#publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2')
                        .show();

                    $('#Organized_By_1,#Proceedings_Name_1, #Journal_Name_1,#Scopus_1,#SCIE_1,#ESCI_1,#AHCI_1,#UGC_1,#Others_1,#Title_of_Book_Series_1')
                        .hide();

                    $('#organized_by_2,#proceeding_name_2, #journal_name_2,#scopus_2,#scie_2,#esci_2,#ahci_2,#ugc_2,#others_2,#book_series_title_2')
                        .hide();


                    $('#button').show();

                }
                if (selectedValue === 'Book_Chapter') {

                    $('#TitleDiv,#titleofbook,#Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages, #check,#Scopus,#SCIE,#ESCI,#Others')
                        .show();
                    $('#Organized,#Proceedings,#Journal,#AHCI,#UGC').hide();

                    $('.title').text('Tittle of Chapter');
                    $('.Title_1').text('Tittle of Chapter');

                    $('#Title_1,#Title_of_Book_Series_1,#Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1,#Scopus_1,#SCIE_1,#ESCI_1,#Others_1')
                        .show();

                    $('#Organized_By_1,#Proceedings_Name_1,#Journal_Name_1,#AHCI_1,#UGC_1').hide();

                    $('#paper_title_2,#book_series_title_2,#publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2,#scopus_2,#scie_2,#esci_2,#others_2')
                        .show();

                    $('#organized_by_2,#proceeding_name_2,#journal_name_2,#ahci_2,#ugc_2').hide();
                    $('#button').show();

                }
                if (selectedValue === '') {
                    $('#main').hide();
                    $('#button').hide();

                }

                $('.publication-row').each(function() {
                    // Get the publication type value from the data attribute
                    var publicationType = $(this).data('publication-type');

                    // Update table cells with data based on the selected value and publication type value
                    if (selectedValue === publicationType) {
                        $(this).find('.col1-data').text($(this).data('col1-data'));
                        $(this).find('.col2-data').text($(this).data('col2-data'));
                        $(this).find('.col3-data').text($(this).data('col3-data'));
                        $(this).find('.col4-data').text($(this).data('col4-data'));
                        $(this).find('.col5-data').text($(this).data('col5-data'));
                        $(this).find('.col6-data').text($(this).data('col6-data'));
                        $(this).find('.col7-data').text($(this).data('col7-data'));
                        $(this).find('.col8-data').text($(this).data('col8-data'));
                        $(this).find('.col9-data').text($(this).data('col9-data'));
                        $(this).find('.col10-data').text($(this).data('col10-data'));
                        $(this).find('.col11-data').text($(this).data('col11-data'));
                        $(this).find('.col12-data').text($(this).data('col12-data'));
                        $(this).find('.col13-data').text($(this).data('col13-data'));
                        $(this).find('.col14-data').text($(this).data('col14-data'));
                        $(this).find('.col15-data').text($(this).data('col15-data'));
                        $(this).find('.col16-data').text($(this).data('col16-data'));
                        $(this).find('.col17-data').text($(this).data('col17-data'));
                        $(this).find('.col18-data').text($(this).data('col18-data'));

                        // Update more table cells with data here as needed
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            if (selectedValue === 'Journal') {
                $('#TitleDiv, #Journal, #Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages, #check,#Scopus,#SCIE,#ESCI,#AHCI,#UGC,#Others')
                    .show();
                $('#Organized,#Proceedings').hide();

                $('.Title_1').text('Tittle of Paper');
                $('.title').text('Tittle of Paper');
                $('.name').text('Journal Name');
                $('.Journal_Name_1').text('Journal Name');

                $('#Title_1, #Journal_Name_1, #Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1,#Scopus_1,#SCIE_1,#ESCI_1,#AHCI_1,#UGC_1,#Others_1')
                    .show();
                $('#Organized_By_1,#Proceedings_Name_1,#Title_of_Book_Series_1').hide();

                $('#organized_by_2,#proceeding_name_2,#book_series_title_2').hide();

                $('#paper_title_2, #journal_name_2, #publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2,#scopus_2,#scie_2,#esci_2,#ahci_2,#ugc_2,#others_2')
                    .show();

                $('#button').show();
            }

            if (selectedValue === 'Conference') {
                $('#TitleDiv, #Journal,#Organized,#Proceedings,#Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages, #check,#Scopus,#SCIE,#ESCI,#AHCI,#UGC,#Others')
                    .show();
                $('.Title_1').text('Tittle of Paper');
                $('.title').text('Tittle of Paper');
                $('.name').text('Conference Name');
                $('.Journal_Name_1').text('Conference Name');

                $('#Title_1, #Journal_Name_1,#Organized_By_1,#Proceedings_Name_1,#Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1,#Scopus_1,#SCIE_1,#ESCI_1,#AHCI_1,#UGC_1,#Others_1,#Title_of_Book_Series_1')
                    .show();

                $('#paper_title_2, #journal_name_2,#organized_by_2,#proceeding_name_2,#publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2,#scopus_2,#scie_2,#esci_2,#ahci_2,#ugc_2,#others_2,#book_series_title_2')
                    .show();
                $('#button').show();
            }
            if (selectedValue === 'Text_Book') {

                $('#TitleDiv,#Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages').show();

                $('#Organized,#Proceedings, #Journal,#check,#Scopus,#SCIE,#ESCI,#AHCI,#UGC,#Others')
                    .hide();
                $('.title').text('Tittle of Book');

                $('.Title_1').text('Tittle of Book');

                $('#Title_1,#Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1').show();

                $('#paper_title_2,#publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2')
                    .show();

                $('#Organized_By_1,#Proceedings_Name_1, #Journal_Name_1,#Scopus_1,#SCIE_1,#ESCI_1,#AHCI_1,#UGC_1,#Others_1,#Title_of_Book_Series_1')
                    .hide();

                $('#organized_by_2,#proceeding_name_2, #journal_name_2,#scopus_2,#scie_2,#esci_2,#ahci_2,#ugc_2,#others_2,#book_series_title_2')
                    .hide();
                $('#button').show();


            }
            if (selectedValue === 'Book_Chapter') {

                $('#TitleDiv,#titleofbook,#Publisher,#ISBN, #DOI, #Volume,#Issue, #Pages, #check,#Scopus,#SCIE,#ESCI,#Others')
                    .show();
                $('#Organized,#Proceedings,#Journal,#AHCI,#UGC').hide();

                $('.title').text('Tittle of Chapter');
                $('.Title_1').text('Tittle of Chapter');

                $('#Title_1,#Title_of_Book_Series_1,#Publisher_1,#ISBN_No_1, #DOI_1, #Volume_No_1,#Issue_1, #Pages_1,#Scopus_1,#SCIE_1,#ESCI_1,#Others_1')
                    .show();

                $('#Organized_By_1,#Proceedings_Name_1,#Journal_Name_1,#AHCI_1,#UGC_1').hide();

                $('#paper_title_2,#book_series_title_2,#publisher_2,#issn_no_2, #doi_2, #volume_no_2,#issue_2, #pages_2,#scopus_2,#scie_2,#esci_2,#others_2')
                    .show();

                $('#organized_by_2,#proceeding_name_2,#journal_name_2,#ahci_2,#ugc_2').hide();
                $('#button').show();
            }

        });
    </script>
@endsection
