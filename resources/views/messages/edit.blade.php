<?php
    use Octicons\Octicon;
    use Octicons\Options;
?>

@extends('layouts.app')

@section('content')
        <div class="flex-center position-ref">

            <div class="content">
                <h2 style="font-family: 'Kodchasan'; font-weight: 200; margin-bottom: 1em;">Edit</h2>
                {{ Form::model($message, array('route' => array('posts.update', $message->id), 'method' => 'PUT')) }}
                    @csrf
                    <div class="form-group">
                        {{ Form::textarea('message', $message->message, array('class' => 'form-control', 'rows' => 6, 'id' => 'message-input', 'maxlength' => 500))}}
                    </div>
                    <div class="small preview">Preview:</div>
                    <div id="message-preview"></div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-2">
                            <span id="remaining-chars"></span>
                        </div>
                        <div class="col-10 text-right">
                            <a class="btn btn-default" href="{{ route('posts.index') }}">Cancel</a>
                            {{ Form::submit('Submit', array('class' => 'btn btn-primary'))}}
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script type="text/javascript">
        const input = document.querySelector('#message-input');
        const preview = document.querySelector('#message-preview');
        const remaining_chars = document.querySelector('#remaining-chars');
        preview.innerHTML = marked(input.value);
        remaining_chars.innerHTML = 500 - input.value.length;

        input.addEventListener('input', evt => {
            preview.innerHTML = marked(input.value);
            remaining_chars.innerHTML = 500 - input.value.length;
        })
    </script>
@endsection