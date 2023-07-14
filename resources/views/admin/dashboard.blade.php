@extends('admin/layout')

@section('main')
    <div>
        <div>
            <h5 class="center">Dashboard</h5>
        </div>
        @if ($admin->type == 'admin' || in_array('totalamount', $perms))
            <label>
                <input type="checkbox" onclick="toggleamt()" />
                <span>View Total Amount</span>
            </label>
        @endif
        <div class="row">
            <div class="col l6 s12">
                <div class="mp-card" style="overflow-x: scroll">
                    <h6 class="center">Direct Orders</h6>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Seen By</th>
                                @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                    <th class="tamt" style="display: none;">Amount</th>
                                @endif
                                @if ($admin->type == 'admin' || in_array('updatecln', $perms))
                                    <th>Pack Order</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mpe as $item)
                                <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                                    oncontextmenu="rightmenu({{ $item->orderid }}); return false;"
                                    ondblclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}', '{{$item->mainstatus}}')">
                                    <td>
                                        <div id="{{ $item->orderid . 'order' }}" class="{{ $stat = $item->mainstatus }}"
                                            style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ getNepaliDay($item->created_at) }}-{{ getNepaliMonth($item->created_at) }}
                                        {{ date('H:i', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <div class="row" style="padding: 0; margin: 0;">
                                            <div class="col s12" style="font-size: 12px;">{{ $item->name }}</div>
                                            <div class="col s12" style="font-size: 8px;">{{ $item->orderid }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->seenby }}</td>
                                    @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                        <td class="tamt" style="display: none;">
                                            {{ getTotalAmount($item->orderid) }}
                                        </td>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('updatecln', $perms))
                                        <td class="center">
                                            <form id="{{ $item->orderid }}">
                                                <input type="hidden" name="orderid" value="{{ $item->orderid }}">
                                                <label>
                                                    <input type="checkbox" value="packorder" name="packorder"
                                                        @if ($stat == 'blue' || $stat == 'red') disabled
                                                @elseif($stat == 'amber darken-1')
                                                @elseif($stat == 'green')
                                                checked disabled
                                                @elseif($stat == 'deep-purple')
                                                checked @endif
                                                        onclick="updatecln({{ $item->orderid }})" />
                                                    <span></span>
                                                </label>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col l6 s12" style="margin-top: 30px;">
                <div class="mp-card" style="overflow-x: scroll">
                    <h6 class="center">Marketer Orders</h6>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Seen By</th>
                                @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                    <th class="tamt" style="display: none;">Amount</th>
                                @endif
                                @if ($admin->type == 'admin' || in_array('updatecln', $perms))
                                    <th>Pack Order</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dealer as $item)
                                <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                                    oncontextmenu="rightmenu({{ $item->orderid }}); return false;"
                                    ondblclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}', '{{$item->mainstatus}}')">
                                    <td>
                                        <div id="{{ $item->orderid . 'order' }}" class="{{ $stat = $item->mainstatus }}"
                                            style="height: 35px; width:10px;">
                                        </div>
                                    </td>
                                    <td>{{ getNepaliDay($item->created_at) }}-{{ getNepaliMonth($item->created_at) }}
                                        {{ date('H:i', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <div class="row" style="padding: 0; margin: 0;">
                                            <div class="col s12" style="font-size: 12px;">{{ $item->name }}</div>
                                            <div class="col s12" style="font-size: 8px;">{{ $item->orderid }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->seenby }}</td>
                                    @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                        <td class="tamt" style="display: none;">
                                            {{ getTotalAmount($item->orderid) }}
                                        </td>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('updatecln', $perms))
                                        <td class="center">
                                            <form id="{{ $item->orderid }}">
                                                <input type="hidden" name="orderid" value="{{ $item->orderid }}">
                                                <label>
                                                    <input type="checkbox" value="packorder" name="packorder"
                                                        @if ($stat == 'blue' || $stat == 'red') disabled
                                                    @elseif($stat == 'amber darken-1')
                                                    @elseif($stat == 'green')
                                                    checked disabled
                                                    @elseif($stat == 'deep-purple')
                                                    checked @endif
                                                        onclick="updatecln({{ $item->orderid }})" />
                                                    <span></span>
                                                </label>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col l6 s12" style="margin-top: 30px;">
                <div class="mp-card" style="overflow-x: scroll">
                    <h6 class="center">Pending Orders</h6>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Seen By</th>
                                @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                    <th class="tamt" style="display: none;">Amount</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pending as $item)
                                <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                                    oncontextmenu="rightmenu({{ $item->orderid }}); return false;"
                                    ondblclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}', '{{$item->mainstatus}}')">
                                    <td>
                                        <div id="{{ $item->orderid . 'order' }}" class="{{ $item->mainstatus }}"
                                            style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ getNepaliDate($item->created_at) }}
                                        {{ date('H:i', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <div class="row" style="padding: 0; margin: 0;">
                                            <div class="col s12" style="font-size: 12px;">{{ $item->name }}</div>
                                            <div class="col s12" style="font-size: 8px;">{{ $item->orderid }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->seenby }}</td>
                                    <td class="tamt" style="display: none;"> {{ getTotalAmount($item->orderid) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink">
                <li class="border-top">Delete</li>
            </a>
        </ul>
    </div>

    <script>
        function toggleamt() {
            $('.tamt').toggle();
        }

        function rightmenu(orderid) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin") {
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/editorder/' + orderid);
                $('#rmdeletelink').attr('href', '/deleteorder/' + orderid);
            }
        }

        $(document).bind("click", function(event) {
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'none';
        });

        function mouseX(evt) {
            if (evt.pageX) {
                return evt.pageX;
            } else if (evt.clientX) {
                return evt.clientX + (document.documentElement.scrollLeft ?
                    document.documentElement.scrollLeft :
                    document.body.scrollLeft);
            } else {
                return null;
            }
        }

        // Set Top Style Proparty
        function mouseY(evt) {
            if (evt.pageY) {
                return evt.pageY;
            } else if (evt.clientY) {
                return evt.clientY + (document.documentElement.scrollTop ?
                    document.documentElement.scrollTop :
                    document.body.scrollTop);
            } else {
                return null;
            }
        }

        function opendetail(orderid, seen ,ms) {
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin" || jQuery.inArray("detail/{id}", perms) > -1) {
                if (admintype == "admin" || seen == 'seen' || jQuery.inArray("firstorderview", perms) > -1) {
                    window.open('/detail/' + orderid, "_self");
                }
            }
            else if(admintype == 'staff' && jQuery.inArray("chalan", perms) > -1 && ms == 'deep-purple'){
                window.open('/chalandetail/' + orderid, "_self");
            }
        }
    </script>
    <script>
        function updatecln(orderid) {
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin" || jQuery.inArray("updatecln", perms) > -1) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/updatecln",
                    data: $(`#${orderid}`).serialize(),
                    type: 'post',
                    success: function(response) {
                        if (response.hasOwnProperty('packorder')) {
                            $(`#${response.orderid}order`).removeAttr('class');
                            $(`#${response.orderid}order`).addClass("deep-purple");
                        } else {
                            $(`#${response.orderid}order`).removeAttr('class');
                            $(`#${response.orderid}order`).addClass("amber darken-1");
                        }

                    }
                })
            }
        }
    </script>
@endsection
