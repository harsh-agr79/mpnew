@extends('admin/layout')

@section('nmmain')
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
    @php
        if ($chatidad >= $chatidus) {
            $chatid = $chatidad;
        } else {
            $chatid = $chatidus;
        }
        $chan = DB::table('channels')
            ->where('shortname', $channel)
            ->first();
    @endphp
    <span class="hide" id="url">{{ url()->full() }}</span>
    <span class="hide" id="channel">{{ $channel }}</span>
    <span class="hide" id="userid">{{ $user->id }}</span>
    <span class="hide" id="userimg">{{ $user->profileimg }}</span>
    <div>
        <div class="bg-content row valign-wrapper"
            style="margin: 0; padding: 0; position:fixed; right: 0; left: 0; z-index:1;">
            <div class="col s2" style="padding-top: 7px;">
                @if ($user->profileimg == NULL)
                    <img src="{{ asset('user.jpg') }}" height="40" style="border-radius: 50%" alt="">
                @else
                    <img src="{{ asset($user->profileimg) }}" height="40" style="border-radius: 50%" alt="">
                @endif
            </div>
            <div class="col s5">
                <h6 style="font-weight: 600;">{{ $user->name }}</h6>
            </div>
            <div class="col s5 left-align">
                <a class='dropdown-trigger textcol center btn-flat' href='#' data-target='chanlist' style="background: {{ $chan->color }}; border-radius: 20px;" >
                    {{ $chan->shortname }}<i class="material-icons right">expand_more</i></a>
                    <div class="red white-text center valign-wrapper"
                    style="position: absolute; top:15px; margin-left: 105px; z-index:1; height: 15px; padding: 5px 3px; border-radius:50%; font-size: 10px;">
                    <span class="center" id="chanunmsg">0</span>
                </div>
            </div>
            <ul id='chanlist' class='dropdown-content'>
                @foreach ($channels as $item)
                <li style="background: {{$item->color}};"><a href="{{url('/admin/m/chats/'.$user->id.'/'.$item->shortname)}}">{{$item->name}} <span class="red" style="font-size: 12px; padding: 3px 4px; border-radius: 20px;">0</span></a></li>
                @endforeach
              </ul>
        </div>
        <div id="chatboxmsgdiv" class="row mobile-chatbox-div" style="">
            @foreach ($chat as $item)
                @if ($admin->type == $item->sendtype)
                    @if ($item->msgtype == 'text')
                        <div class="col s12" id="{{ $item->id }}" style="margin:0; padding: 0;">
                            <div class="chat-message message-right right">
                                {{ $item->message }}<br>
                                <span style="font-size: 7px; padding: 0; margin: 0;">
                                    <span class="left">{{ $item->sentname }}</span> <span
                                        class="right">{{ date('d-M H:i', $item->created_at) }}</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="col s12" id="{{ $item->id }}" style="margin: 5px 0; padding: 5px;">
                            <div class="right bg-content">
                                <img src="{{ asset($item->image) }}" class="materialboxed" height="150" alt="">
                                <div style="font-size: 7px; padding: 3px; margin: 0; width: 100%">
                                    <span class="left">{{ $item->sentname }}</span> <span
                                        class="right">{{ date('d-M H:i', $item->created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @if ($item->msgtype == 'text')
                        <div class="col s12" id="{{ $item->id }}" style="margin:0, padding: 0;">
                            <div class="chat-message message-left left">
                                {{ $item->message }}<br>
                                <span style="font-size: 7px; padding: 0; margin: 0;">
                                    <span class="left black-text">{{ date('d-M H:i', $item->created_at) }}</span>
                                </span>
                            </div>

                        </div>
                    @else
                        <div class="col s12" id="{{ $item->id }}" style="margin:5px 0; padding: 0;">
                            <div class="left ">
                                <img src="{{ asset($item->image) }}" class="materialboxed" height="150" alt="">
                                <div class="user-img-msg-bg" style="font-size: 7px; padding-bottom: 10px; width: 100%;">
                                    <span class="left">{{ date('d-M H:i', $item->created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($item->id == $chatid)
                    <div class="col s12" id="seenbox" style="margin:0, padding: 0;">
                        <div class="right">
                            @if ($user->profileimg != null)
                                <img src="{{ asset($user->profileimg) }}" height="20" style="border-radius: 50%"
                                    alt="">
                            @else
                                <img src="{{ asset('user.jpg') }}" height="20" style="border-radius: 50%"
                                    alt="">
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div>
            <form id="message-inp" enctype="multipart/form-data">

                <div class="row valign-wrapper" style="position: fixed; bottom: 0; right: 0; left: 0;">
                    <input type="hidden" name="sid" value="{{ $user->id }}">
                    <input type="hidden" name="channel" value="{{ $channel }}">
                    <div class="col s1 center" style="padding: 7px 0 0 2px;">
                        <span class="btn-file">
                            <i class="material-symbols-outlined textcol" style="font-size: 25px;">
                                image
                            </i>
                            <input type="file" id="imginp" name="img" onchange="$('#message-inp').submit()">
                        </span>
                    </div>
                    <div class="col s10" style="padding: 0;">
                        <input type="text" class="browser-default msginp" id="msgval" name="message"
                            placeholder="Type Message..." autocomplete="off">
                    </div>
                    <div class="col s1 center" style="padding: 7px 2px 0 0;">
                        <button>
                            <i class="material-symbols-outlined textcol" style="font-size: 25px;">
                                send
                            </i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script src="{{ asset('assets/chat.js') }}"></script>
@endsection
