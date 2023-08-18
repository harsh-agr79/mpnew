@extends('admin/layout')

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
    @if ($admin->type == 'staff')
        @php
            $channels = DB::table('channels')->get();
            $perms = DB::table('permission')
                ->where('userid', session()->get('ADMIN_ID'))
                ->pluck('perm')
                ->toArray();
            $chn = [];
            foreach ($channels as $item) {
                if (in_array($item->shortname, $perms)) {
                    array_push($chn, $item->shortname);
                }
            }
        @endphp
    @else
        @php
            $chn = ['general'];
        @endphp
    @endif
    <div class="mp-card" style="margin-top: 10px;">
        <div class="input-field">
            <select data-id="26" id="MySelct" serachname="myselectsearch" searchable="Select User"
                onchange="startnewchat();">
                <option value="" selected disabled>New Conversation</option>
                @foreach ($customer as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div style="margin-top: 10px;" id="chatlist2">
        @foreach ($allchats as $item)
            @php
                $u = DB::table('customers')
                    ->where('id', $item->sid)
                    ->first();
                $uns = DB::table('chat')
                    ->where('sid', $item->sid)
                    ->where('sendtype', 'user')
                    ->where('seen', null)
                    ->get();
                $unseen = count($uns);
                
                if ($item->seen != 'seen' && $item->sendtype == 'user') {
                    $cls = 'bold';
                    $txt = '';
                    $setg = 'hide';
                    $dep = 'chat-unseen';
                } elseif ($item->seen == 'seen' && $item->sendtype == 'user') {
                    $cls = '';
                    $txt = '';
                    $setg = 'hide';
                    $dep = '';
                } else {
                    if ($item->seen != 'seen') {
                        $cls = '';
                        $txt = 'You:';
                        $setg = 'hide';
                        $dep = '';
                    } else {
                        $cls = '';
                        $txt = 'You:';
                        $setg = '';
                        $dep = '';
                    }
                }
            @endphp
            <a href="{{ url('/admin/m/chats/' . $item->sid . '/' . $item->channel) }}"
                class="row valign-wrapper chat-list-item textcol {{ $dep }}"
                onclick="window.history.pushState(null, document.title, '/admin/m/chatlist');">
                <div class="col s3">
                    @if ($u->profileimg != null)
                        <img src="{{ asset($u->profileimg) }}" class="chat-list-img">
                    @else
                        <img src="{{ asset('user.jpg') }}" class="chat-list-img">
                    @endif

                </div>
                <div class="col s9" style="width: 100%">
                    <div class="left-align">
                        <span class="chat-list-username {{ $cls }}">{{ $u->name }}</span>
                        <span class="chat-list-channel {{ $cls }}">{{ $item->channel }}</span>
                    </div>
                    <div>
                        <div class="{{ $cls }} left chat-list-message">
                            {{ $txt }}{{ $item->message }}</div>

                        <div class="right {{ $setg }}" style="margin-right: 10px;">
                            @if ($u->profileimg != null)
                                <img src="{{ asset($u->profileimg) }}" height="15" style="border-radius: 50%"
                                    alt="">
                            @else
                                <img src="{{ asset('user.jpg') }}" height="15" style="border-radius: 50%"
                                    alt="">
                            @endif
                        </div>
                        @if ($unseen > 0)
                            <div class="right" style="margin-right: 10px;">
                                <div class="red white-text"
                                    style="width:22px; height: 22px; font-size: 15px; border-radius: 50%; display:flex; align-items: center; justify-content: center;">
                                    <div>{{ $unseen }}</div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <script src="{{ asset('assets/chat.js') }}"></script>
    <script>
        function startnewchat() {
            console.log($('#MySelct').val());
            window.open(`/admin/m/chats/${$('#MySelct').val()}/{{ $chn[0] }}`, '_self');
        }
    </script>
@endsection
