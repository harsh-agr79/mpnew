@extends('customer/layout')

@section('main')
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
                    <div class="col s12">
                        <div class="user-message-div user-message-right">
                            <span style="font-size: 12px;">
                                {{ $item->message }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="col s12">
                        <div class="user-message-div user-message-left">
                            <span style="font-size: 12px;">
                                {{ $item->message }}
                            </span><br>
                            <span style="font-size: 7px">{{ $item->sentname }}</span>
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
                    <a class="btn-flat">
                        <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                            image
                        </i>
                    </a>
                </div>
                <div style="margin:0; padding: 0; width: 70vw;">
                    <input type="text" class="browser-default msginp" id="msgval" name="message"
                        placeholder="Type Message...">
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
        $(document).ready(function() {
            var msgSection = document.querySelector("#userchatbox");
            msgSection.scrollTo(0, msgSection.scrollHeight);
        });
        $(function() {
            let ip_address = "socket.startuplair.com";
            // let socket_port = "3000";
            let socket = io(ip_address);
            let type = ['admin', 'staff', 'marketer']

            socket.on("sendMsgToClient", (message) => {
                if (message[0].sid == `{{ $user->id }}` && message[0].channel == `{{ $channel }}` && type.indexOf(message[0].sendtype) > -1)
                    $('#userchatbox').append(`
                <div class="col s12">\
                        <div class="user-message-div user-message-left">\
                            <span style="font-size: 12px;">\
                              ${message[0].message} \
                            </span><br>\
                            <span style="font-size: 7px">${message[0].sentname}</span>\
                        </div>\
                    </div>\
                `)
                var msgSection = document.querySelector("#userchatbox");
                msgSection.scrollTo(0, msgSection.scrollHeight);

            });
        });
        $('#sendmessage').on('submit', function(e) {
            e.preventDefault();
            let ip_address = "socket.startuplair.com";
            // let socket_port = "3000";
            let socket = io(ip_address);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/addmsguser",
                data: $("#sendmessage").serialize(),
                type: "post",
                success: function(response) {
                    console.log(response)
                    socket.emit("sendMsgToServer", response);
                    $('#userchatbox').append(`
                    <div class="col s12">\
                        <div class="user-message-div user-message-right">\
                            <span style="font-size: 12px;">\
                              ${response[0].message} \
                            </span><br>\
                            <span style="font-size: 7px"></span>\
                        </div>\
                    </div>\
                `)
                    var msgSection = document.querySelector("#userchatbox");
                    msgSection.scrollTo(0, msgSection.scrollHeight);
                },
            });
            $("#msgval").val("");
        })
    </script>
@endsection
