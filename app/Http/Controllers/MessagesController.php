<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Message;
use App\Http\Resources\PostsResource;
use Redirect;

class MessagesController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $messages = Message::whereUser($user_id)->orderBy('created_at', 'desc')->whereDeleted(0)->get();
        return view('messages.index')->with('messages', $messages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $message = new Message;
        $message->message = Input::get('message');

        $image = Input::file('image');
        $ext = $image->getClientOriginalExtension();
        Storage::disk('public')->put($image->getFilename() . '.' . $ext, File::get($image));

        $message->user = $user_id;
        $message->mime = $image->getClientMimeType();
        $message->original_filename = $image->getClientOriginalName();
        $message->filename = $image->getFilename() . '.' . $ext;
        $message->save();

        return Redirect::to('posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messages = Message::whereUser($id)->orderBy('created_at', 'desc')->whereDeleted(0)->get();
        foreach ($messages as $message) {
            if ($message['filename']) {
                $message['image'] = env('APP_URL') . '/public/uploads/' . $message['filename'];
            }
            unset($message->deleted);
            unset($message->filename);
            unset($message->mime);
            unset($message->original_filename);
        }
        PostsResource::withoutWrapping();
        return new PostsResource($messages);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Auth::id();
        $message_user_id = Message::whereId($id)->first()->user;

        if ($user_id == $message_user_id) {
            $message = Message::find($id);
            return view('messages.edit')->with('message', $message);
        } else {
            return Redirect::to('posts');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        $message->message = Input::get('message');
        $message->save();

        return Redirect::to('posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        $message->deleted = '1';
        $message->save();

        return Redirect::to('posts');
    }
}
