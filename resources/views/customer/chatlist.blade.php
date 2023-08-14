@extends('customer/layout')

@section('main')
    <script type="text/javascript">
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                // Handle page restore.
                window.location.reload();
            }
        });
    </script>
    <style>
        .bold {
            font-weight: 700;
        }
    </style>
    <div>
        <div class="center">
            <h5>Channel's List</h5>
        </div>
        <div class="mp-container" id="chatlistuser">
            @foreach ($chatList as $item)
                @php
                    $chan = DB::table('channels')
                        ->where('shortname', $item->channel)
                        ->first();
                @endphp
                <a href="{{ url('user/chatbox/' . $chan->shortname) }}" class="mp-card row valign-wrapper textcol"
                    style="margin:5px; padding: 0;"
                    onclick="console.log('sup'); window.history.pushState(null, document.title, '/user/chatlist');">
                    <div class="col s3 center">
                        <div style="background: {{ $chan->color }}; height: 60px; width: 60px; border-radius: 50%;">

                        </div>
                    </div>
                    <div class="col s9">
                        <div>
                            <h5 style="font-size: 20px;"> {{ $chan->name }} </h5>
                        </div>
                        <div style="margin-top: 0;">
                            <div style="font-size: 15px; width: 180px; overflow: hidden;" class="left textcol">
                                {{ $item->message }}...
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            @foreach ($noChatList as $item)
                <a href="{{ url('user/chatbox/' . $item->shortname) }}" class="mp-card row valign-wrapper textcol"
                    style="margin:5px; padding: 10px;">
                    <div class="col s3 center">
                        <div style="background: {{ $item->color }}; height: 60px; width: 60px; border-radius: 50%;">

                        </div>
                    </div>
                    <div class="col s9">
                        <div>
                            <h5 style="font-size: 20px;"> {{ $item->name }} </h5>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <script>
         function chatlist() {
                $.ajax({
                    type: 'get',
                    url: '/user/getchatlist',
                    success: function(response) {
                        // console.log(response);
                        $('#chatlistuser').html("");
                        $.each(response, function(key, item) {
                            if (item.unseen > 0) {
                                var sn = 'bold';
                                var dep = 'z-depth-1';
                            } else {
                                var sn = '';
                                var dep = '';
                            }
                            $('#chatlistuser').append(`
                            <a href="/user/chatbox/${item.shortname}" class="mp-card row valign-wrapper textcol ${dep} ${sn}"
                    style="margin:5px; padding: 0;"
                    onclick="window.history.pushState(null, document.title, '/user/chatlist');">
                    <div class="col s3 center">
                        <div style="background: ${item.color}; height: 60px; width: 60px; border-radius: 50%;">

                        </div>
                    </div>
                    <div class="col s9">
                        <div>
                            <h5 style="font-size: 20px;" class="${sn} textcol"> ${item.name} </h5>
                        </div>
                        <div style="margin-top: 0;">
                            <div style="font-size: 15px; width: 180px; overflow: hidden" class="${sn} textcol left">
                                ${item.message}...
                            </div>
                            ${item.unseen > 0? `<div class="right" style="margin-right: 10px; margin-bottom: 10px;">
                                    <div class="red white-text" style="width:22px; height: 22px; font-size: 15px; border-radius: 50%; display:flex; align-items: center; justify-content: center; font-weight: 400;"><div>${item.unseen}</div></div>
                                    </div>` : ``}
                        </div>
                    </div>
                </a>
                            `)
                        })
                    }
                })
            }
        $(document).ready(function() {
            chatlist();
        })
        $(function() {
            let ip_address = 'socket.startuplair.com';
                // let socket_port = '3000';
                let socket = io(ip_address);
            let type = ['admin', 'staff', 'marketer']

            socket.on("sendMsgToClient", (message) => {
                chatlist();
            })
        })
    </script>
@endsection
