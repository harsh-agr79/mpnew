<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="icon" href="{{ asset('assets/light.png') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/chat.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/assets/' . $admin->mode . '.css') }}">
    <title>Admin Chats</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @extends('style')
    <style>
        .newcon::placeholder {
            color: white;
        }
    </style>
    <nav>
        <div class="nav-wrapper bg">
            <a href="#" class="brand-logo center"><img src="{{ asset('assets/' . $admin->mode . '.png') }}"
                    height="60" alt=""></a>
            <ul id="nav-mobile" class="left hide-on-med-and-down">
                <li><a href="#channelsList" class="modal-trigger">Channels</a></li>
                <li><a href="#addchannel" class="modal-trigger">Add Channel</a></li>
                <li>
                    <nav style="box-shadow: none;">
                        <div class="nav-wrapper bg">
                            <form id="newconvo">
                                <div class="input-field">
                                    <input id="search" type="search" placeholder="Start New Conversation"
                                        class="newcon" autocomplete="off">
                                    {{-- <label class="label-icon" for="search"><i class="material-icons">search</i></label> --}}
                                    {{-- <i class="material-icons">close</i> --}}
                                </div>
                            </form>
                        </div>
                    </nav>
                </li>
            </ul>
        </div>
    </nav>
    <main>
        <div class="row" style="margin:0; padding: 0;">
            <div class="col s3 chat-list center bg-content" id="chatlist" style="margin: 0; padding: 0;">
                @foreach ($allchats as $item)
                    @php
                        $u = DB::table('customers')
                            ->where('id', $item->sid)
                            ->first();
                        if ($user->id == $item->sid) {
                            $act = 'chat-active';
                        } else {
                            $act = '';
                        }
                    @endphp
                    <a href="{{ url('chats/' . $item->sid . '/' . $item->channel) }}"
                        class="row valign-wrapper chat-list-item textcol {{ $act }}">
                        <div class="col s3">
                            @if ($u->profileimg != null)
                                <img src="{{ asset($u->profileimg) }}" class="chat-list-img">
                            @else
                                <img src="{{ asset('user.jpg') }}" class="chat-list-img">
                            @endif

                        </div>
                        <div class="col s9">
                            <span class="chat-list-username">{{ $u->name }}</span><span
                                class="chat-list-channel">{{ $item->channel }}</span><br>
                            <span class="chat-list-message">{{ $item->message }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="col s6" style="height: 80vh;">
                <div class="Chat-box row" style="margin:0, padding: 0;">
                    <div class="col s12 bg-content" style="margin: 0;">
                        <div class="chat-box-header valign-wrapper">
                            <div class="chat-box-dp">
                                @if ($user->profileimg != null)
                                    <img src="{{ asset($user->profileimg) }}" height="50" style="border-radius: 50%"
                                        alt="">
                                @else
                                    <img src="{{ asset('user.jpg') }}" height="50" style="border-radius: 50%"
                                        alt="">
                                @endif
                            </div>
                            <div class="chat-box-username">
                                {{ $user->name }}
                            </div>
                        </div>
                    </div>
                    <div class="col s12 chat-box-convo row" id="chatboxmsgdiv" style="margin:0, padding: 0;">
                        @foreach ($chat as $item)
                            @if ($admin->type == $item->sendtype)
                                <div class="col s12" style="margin:0, padding: 0;">
                                    <div class="chat-message message-right right">
                                        {{ $item->message }}<br>
                                        <span style="font-size: 7px; padding: 0; margin: 0;">
                                            {{ $item->sentname }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="col s12" style="margin:0, padding: 0;">
                                    <div class="chat-message message-left left">
                                        {{ $item->message }}
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    <div class="col s12" style="margin:0, padding: 0;">
                        <div class="chat-box-messageinp row" style="margin:0, padding: 0;">
                            <form id="message-inp">
                                <input type="hidden" name="sid" value="{{ $user->id }}">
                                <input type="hidden" name="channel" value="{{ $channel }}">
                                <div class=" col s1" style="margin:0, padding: 0;">
                                    <a class="btn-flat">
                                        <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                                            image
                                        </i>
                                    </a>
                                </div>
                                <div class="col s10" style="margin:0, padding: 0;">
                                    <input type="text" class="browser-default msginp" id="msgval" name="message"
                                        placeholder="Type Message...">
                                </div>
                                <div class="col s1" style="margin:0, padding: 0;">
                                    <button class="btn-flat">
                                        <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                                            send
                                        </i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s3 chat-detail" style="margin:0, padding: 0;">
                <div class="chat-detail-box">
                    <div class="center" style="margin-top: 20px;">
                        @if ($user->profileimg != null)
                            <img src="{{ asset($user->profileimg) }}" class="chat-detail-img" alt="">
                        @else
                            <img src="{{ asset('user.jpg') }}" class="chat-detail-img" alt="">
                        @endif
                        <div class="chat-detail-username">
                            <h5>{{ $user->name }}</h5>
                        </div>
                        @php
                            $chan = DB::table('channels')
                                ->where('shortname', $channel)
                                ->first();
                        @endphp
                        <div class="chat-detail-actchannel" style="background: {{ $chan->color }}">
                            <h6>{{ $chan->name }}</h6>
                        </div>
                    </div>
                    <div class="row center chat-box-channel-list">
                        {{-- <div class="col s12 chat-box-channel">
                            <span class="channel-item">Channel 1 <span class="unseen-msg">4</span></span>
                        </div> --}}
                        @foreach ($channels as $item)
                            <div class="col s12 chat-box-channel">
                                <a class="channel-item textcol"
                                    href="{{ url('chats/' . $user->id . '/' . $item->shortname) }}"
                                    style="background: {{ $item->color }}">{{ $item->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div id="channelsList" class="modal">
        <div class="modal-content">
            <div class="center">
                <h4>Channel's List</h4>
            </div>
            <table>
                <thead>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Color</th>
                    <th>Admin Message Only</th>
                    <th>Edit</th>
                </thead>
                <tbody>
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($channels as $item)
                        <tr>
                            <td>{{ $a = $a + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->shortname }}</td>
                            <td>
                                <div style="background: {{ $item->color }}; height: 20px; width: 50px;"></div>
                            </td>
                            <td>{{ $item->adminonly }}</td>
                            <td><a class="btn amber textcol modal-trigger modal-close" href="#editchannel"
                                    onclick="editchannel({{ $item->id }});"><i
                                        class="material-symbols-outlined textcol">
                                        edit
                                    </i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="addchannel" class="modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('addchannel') }}">
                @csrf
                <div class="row">
                    <div class="row col s12">
                        <div class="col s3">Name: </div>
                        <div class="col s9"> <input type="text" name="name"
                                class="inp black-text browser-default" placeholder="Name" required></div>
                    </div>
                    <div class="row col s12">
                        <div class="col s3">Short Name: </div>
                        <div class="col s9"> <input type="text" name="shortname"
                                class="inp black-text browser-default" placeholder="Short Name" required></div>
                    </div>
                    <div class="row col s12">
                        <div class="col s3">Color: </div>
                        <div class="col s9"> <input type="color" name="color" value="#00000000"
                                class="browser-default" placeholder="Color" required></div>
                    </div>
                    <div class="row col s12">
                        <div class="col s3">Only Admin Can Message: </div>
                        <div class="col s9"> <label>
                                <input type="checkbox" name="adminonly" />
                                <span>Only Admin</span>
                            </label></div>
                    </div>
                    <div class="col s12 center">
                        <button class="btn amber">
                            Add Channel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="editchannel" class="modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('editchannel') }}">
                @csrf
                <input type="hidden" name="id" id="chid">
                <div class="row">
                    <div class="row col s12">
                        <div class="col s3">Name: </div>
                        <div class="col s9"> <input type="text" name="name" id="chname"
                                class="inp black-text browser-default" placeholder="Name" required></div>
                    </div>
                    <div class="row col s12">
                        <div class="col s3">Short Name: </div>
                        <div class="col s9"> <input type="text" id="chsname" name="shortname"
                                class="inp black-text browser-default" placeholder="Short Name" required></div>
                    </div>
                    <div class="row col s12">
                        <div class="col s3">Color: </div>
                        <div class="col s9"> <input type="color" id="chcolor" name="color" value="#00000000"
                                class="browser-default" placeholder="Color" required></div>
                    </div>
                    <div class="row col s12">
                        <div class="col s3">Only Admin Can Message: </div>
                        <div class="col s9"> <label>
                                <input type="checkbox" id="chaonly" name="adminonly" />
                                <span>Only Admin</span>
                            </label></div>
                    </div>
                    <div class="col s12 center">
                        <button class="btn amber">
                            Edit Channel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/chat.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.modal').modal();
        });
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '{!! URL::to('findcustomer') !!}',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#search').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })
        $('#search').on('keyup', function(e) {
            e.preventDefault();
            var val = $('#search').val();
            var datacust2 = {};
            $.ajax({
                type: 'get',
                url: '{!! URL::to('findcustomer') !!}',
                success: function(response2) {
                    var custarray2 = response2;
                    for (var i = 0; i < custarray2.length; i++) {
                        datacust2[custarray2[i].name] = null;
                    }
                    var custdata = Object.keys(datacust2);
                    if (custdata.indexOf(val) > -1) {
                        window.open('/chats/' + custarray2[custdata.indexOf(val)].id + '/billing',
                            "_self");
                    }
                }
            })
            // console.log(custdata);

        })
        $(function() {
            let ip_address = "socket.startuplair.com";
            // let socket_port = "3000";
            let socket = io(ip_address);

            socket.on("sendMsgToClient", (message) => {
                console.log(message);
                if (message[0].sendtype == 'user' && message[0].sid == `{{ $user->id }}` && message[0]
                    .channel == `{{ $channel }}`) {
                    $("#chatboxmsgdiv").append(`\
        <div class="col s12" style="margin:0, padding: 0;">\
                                    <div class="chat-message message-left">\
                                        ${message[0].message}\
                                    </div>\
                                </div>\
        `)
        chatlist(message[0].sid)
                    var msgSection = document.querySelector("#chatboxmsgdiv");
                    msgSection.scrollTo(0, msgSection.scrollHeight);
                }
            });
        });
    </script>
</body>

</html>
