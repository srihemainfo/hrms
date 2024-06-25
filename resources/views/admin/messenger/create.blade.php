@extends('admin.messenger.template')

@section('title', trans('global.new_message'))

@section('messenger-content')
<div class="row">
    <div class="col-md-12">
        <form name="myform" action="{{ route('admin.messenger.storeTopic') }}" method="POST" onsubmit="return validateform()">
            @csrf
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label for="recipient" class="control-label">
                                {{ trans('global.recipient') }}
                            </label>
                            <select name="recipient" class="form-control select2">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->employID != null ? ('('.$user->employID.')'):($user->register_no ? ('('.$user->register_no.')'):''); }}</option>
                                @endforeach
                            </select>

                            <span id="span_recipient" class="text-danger" style="display: none;">Enter Recipient</span>

                        </div>

                        <div class="col-lg-12 form-group">
                            <label for="subject" class="control-label">
                                {{ trans('global.subject') }}
                            </label>
                            <input type="text" name="subject" class="form-control" />
                            <span id="span_subject" class="text-danger" style="display: none;">Enter Subject</span>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label for="content" class="control-label">
                                {{ trans('global.content') }}
                            </label>
                            <textarea name="content" class="form-control"></textarea>
                            <span id="span_content" class="text-danger" style="display: none;">Enter Content</span>
                        </div>
                    </div>
                    <input type="submit" value="{{ trans('global.submit') }}" class="btn btn-success" />
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function validateform() {
        var recipient = document.myform.recipient.value;
        var subject = document.myform.subject.value;
        var content = document.myform.content.value;
        
        
        var spanRecipient = document.getElementById('span_recipient');
        var spanSubject = document.getElementById('span_subject');
        var spanContent = document.getElementById('span_content');

        
        if (recipient == '') {
            spanRecipient.style.display = 'block';
            spanContent.style.display = 'none';
            spanSubject.style.display = 'none';
            return false;
        }else if(subject == ''){

            spanSubject.style.display = 'block';
            spanRecipient.style.display = 'none';
            spanContent.style.display = 'none';
            return false;
        }else if(content == ''){

            spanContent.style.display = 'block';
            spanSubject.style.display = 'none';
            spanRecipient.style.display = 'none';
            return false;
        }
    }
</script>
@stop