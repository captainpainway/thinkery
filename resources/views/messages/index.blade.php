<?php
    use Octicons\Octicon;
    use Octicons\Options;
?>

@extends('layouts.app')

@section('content')
        <div class="flex-center position-ref">

            <div class="content">
                {{ Form::open(array('url' => 'posts')) }}
                    @csrf
                    <div class="form-group">
                        {{ Form::textarea('message', '', array('class' => 'form-control', 'id' => 'message-input', 'value' => 'What\'s your message?', 'rows' => 6, 'maxlength' => 500))}}
                    </div>
                    <div class="form-group row">
                        <div class="col-2">
                            <span id="remaining-chars"></span>
                        </div>
                        <div class="col-10 text-right">
                            <a id="clear-btn" class="btn btn-default">Clear</a>
                            {{ Form::submit('Submit', array('class' => 'btn btn-primary'))}}
                        </div>
                    </div>
                {{ Form::close() }}
                <hr>
                    <div>
                        @foreach ($messages as $key => $message)
                        <div class="message">
                            <p>@parsedown($message->message)</p>
                            <p class="small"><span>Post #{{ $message->id }}: </span><span class="date">{{ $message->created_at }}</span></p>
                            <div class="form-group text-right">
                                {{ Form::open(array('route' => array('posts.edit', $message->id), 'style' => 'display: inline', 'method' => 'GET')) }}
                                        <label>
                                            {{ Form::submit("Edit", array('class' => 'btn btn-primary hidden'))}}
                                            @php
                                                $octicon = new Octicon();
                                                $options = new Options();
                                                $options->addClass('icon-btn');
                                                $options->addClass('icon-edit');
                                                $options->setRatio(2);
                                                $icon = $octicon->icon('pencil', $options);
                                                echo $icon->toSvg();
                                            @endphp
                                        </label>
                                {{ Form::close() }}
                                <div style="display: inline">
                                    <label>
                                        <button class="btn btn-danger hidden delete-msg" data-toggle="modal" data-target="#deleteModal" data-id="{{ $message->id }}"></button>
                                        @php
                                            $octicon = new Octicon();
                                            $options = new Options();
                                            $options->addClass('icon-btn');
                                            $options->addClass('icon-delete');
                                            $options->setRatio(2);
                                            $icon = $octicon->icon('x', $options);
                                            echo $icon->toSvg();
                                        @endphp
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this post?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                {{ Form::open(array('url' => '', 'style' => 'display: inline', 'id' => 'delete-message')) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    {{ Form::submit("Delete", array('class' => 'btn btn-danger'))}}
                                {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    <script type="text/javascript">
        const dates = document.querySelectorAll('.date');
        [...dates].forEach(date => {
            const d = new Date(date.innerHTML);
            date.innerHTML = d.toLocaleDateString('en-US', {weekday: 'long', day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: 'numeric'});
        });

        const input = document.querySelector('#message-input');
        const remaining_chars = document.querySelector('#remaining-chars');
        remaining_chars.innerHTML = 500 - input.value.length;

        input.addEventListener('input', evt => {
            remaining_chars.innerHTML = 500 - input.value.length;
        });

        const delete_id = document.querySelector('#delete-message');
        const delete_btns = document.querySelectorAll('.delete-msg');
        [...delete_btns].forEach(btn => {
            btn.addEventListener('click', evt => {
                console.log(btn.getAttribute('data-id'))
                const message_id = btn.getAttribute('data-id');
                delete_id.setAttribute('action', `posts/${message_id}`);
            });
        });
    </script>
@endsection