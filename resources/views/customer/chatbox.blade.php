@extends('customer/layout')

@section('main')
<style>
    .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
</style>
<span class="hide" id="url">{{url()->full()}}</span>
<span class="hide" id="channel">{{$channel}}</span>
<span class="hide" id="userid">{{$user->id}}</span>
@php
if ($chatidad >= $chatidus) {
    $chatid = $chatidad;
} else {
    $chatid = $chatidus;
}
@endphp
    <div style="margin: 0; padding: 0;">
        <div class="row user-chat-header valign-wrapper" style="margin: 0">
            <div class="col s3">
                <div style="background: {{ $chan->color }}; height: 40px; width: 40px; border-radius: 50%;">
                </div>
            </div>
            <div class="col s9">
                <h5 style="font-size: 20px;">{{ $chan->name }}</h5>
            </div>
        </div>
        <div class="row user-chat-box" id="userchatbox" style="margin: 0; padding: 0;">
            @foreach ($chat as $item)
                @if ($item->sendtype == 'user')
                    @if ($item->msgtype == 'text')
                    <div class="col s12" id="{{$item->id}}">
                        <div class="user-message-div user-message-right">
                            <span style="font-size: 12px;">
                                {{ $item->message }}
                            </span><br>
                            <span class="right" style="font-size: 7px;">
                                {{date('d-M H:i', $item->created_at)}}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="col s12" id="{{ $item->id }}" style="margin: 5px 0; padding: 0;">
                        <div class="right bg-content">
                            <img src="{{ asset($item->image) }}" class="materialboxed" height="150"
                                alt="">
                                <div style="font-size: 7px; padding: 3px; margin: 0; width: 100%">
                                    <span class="right">{{date('d-M H:i', $item->created_at)}}</span>
                                </div>
                        </div>
                    </div>
                    @endif
                   
                @else
                @if ($item->msgtype == 'text')
                    <div class="col s12" id="{{$item->id}}">
                        <div class="user-message-div user-message-left">
                            <span style="font-size: 12px;">
                                {{ $item->message }}
                            </span><br>
                            <span style="font-size: 7px">
                                <span class="left">{{ $item->sentname }}</span><span class="right">{{date('d-M H:i', $item->created_at)}}</span></span>
                        </div>
                    </div>
                    @else
                    <div class="col s12" id="{{ $item->id }}" style="margin: 5px 0; padding: 5px;">
                        <div class="left">
                            <img src="{{ asset($item->image) }}" class="materialboxed" height="150"
                                alt="">
                                <div class="user-img-msg-bg" style="font-size: 7px; padding-bottom: 10px; margin: 0; width: 100%">
                                    <span class="left">{{ $item->sentname }} </span><span class="right"> {{date('d-M H:i', $item->created_at)}}</span>
                                </div>
                        </div>
                    </div>
                    
                    @endif
                @endif
                @if ($item->id == $chatid)
                <div class="col s12" id="seenbox">
                    <div class="right">
                        <span style="font-size: 10px; margin-right: 20px;">seen</span>
                    </div>
                </div>
                @endif
            @endforeach
          
        </div>
        <div class="center" style="margin: 0;">
            <form id="sendmessage" class="user-chat-messageinp ">
                <input type="hidden" name="userid" value="{{ $user->id }}">
                <input type="hidden" name="channel" value="{{ $chan->shortname }}">
                <div style="margin:0; padding: 0;">
                    <span class="btn-flat btn-file">
                        <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                            image
                        </i>
                        <input type="file" name="img" onchange="$('#sendmessage').submit()">
                    </span>
                </div>
                <div style="margin:0; padding: 0; width: 70vw;">
                    <input type="text" class="browser-default msginp" id="msgval" name="message"
                        placeholder="Type Message..." autocomplete="off">
                </div>
                <div style="margin:0; padding: 0;">
                    <button class="btn-flat">
                        <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                            send
                        </i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/cuschat.js') }}"></script>
    <script>
      
    </script>
@endsection
