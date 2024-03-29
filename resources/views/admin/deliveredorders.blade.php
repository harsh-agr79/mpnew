@extends('admin/layout')

@section('main')
@php
    if(in_array('updatedeliver', $perms) || $admin->type == 'admin'){
        $dis = '';
    }
    else{
        $dis = 'disabled';
    }
@endphp
<div class="center">
    {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
</div>
    <div class="mp-card" style="overflow-x: scroll; margin-top: 30px;">
        <h6 class="center">Delivered Orders</h6>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Detail</th>
                    @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                        <th class="tamt" style="display: none;">Amount</th>
                    @endif
                    <th>Deliver</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr id="{{$item->orderid}}tr" class=" @if ($item->seen == '') z-depth-2 @endif"
                        oncontextmenu="rightmenu({{ $item->orderid }}); return false;"
                        ondblclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}')">
                        <td>
                            <div id="{{ $item->orderid . 'order' }}" class="{{$stat = $item->mainstatus}}"
                                style="height: 35px; width:10px;"></div>
                        </td>
                        <td>{{ getNepaliDate($item->created_at) }}</td>
                        <td>
                            <div class="row" style="padding: 0; margin: 0;">
                                <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name }}</div>
                                <div class="col s4 m3 l3" style="font-size: 8px;">{{ $item->orderid }}</div>
                                <div class="col s8 m4 l4" style="font-size: 8px;">{{ $item->refname }}</div>
                            </div>
                        </td>
                        @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                            <td class="tamt" style="display: none;">
                                {{ getTotalAmount($item->orderid) }}
                            </td>
                        @endif
                        <td>
                            <form id="{{$item->orderid}}deliverform">
                            <label>
                                <input type="hidden" name="orderid" value="{{ $item->orderid }}">
                                <input id="{{ $item->orderid.'deliver' }}" {{$dis}} type="checkbox" name="delivered"
                                   @if ($stat == 'green')
                                    checked
                                   @endif onchange="updatedeliver({{$item->orderid}})" />
                                <span id="{{ $item->orderid . 'deliverspan' }}">@if ($stat == 'green')
                                    Delivered
                                   @endif</span>
                            </label>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="center">
        {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
    </div>
    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            @if ($admin->type == 'admin')
            <a id="rmdeletelink">
                <li class="border-top">Delete</li>
            </a>
            @endif
        </ul>
    </div>
    <script>
        function rightmenu(orderid) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin" || jQuery.inArray("editorder/{id}", perms) > -1) {
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

        function opendetail(orderid, seen) {
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            // console.log(seen);
            if (admintype == "admin" || jQuery.inArray("detail/{id}", perms) > -1) {
                if (admintype == "admin" || seen == 'seen' || jQuery.inArray("firstorderview", perms) > -1) {
                    window.open('/detail/' + orderid, "_self");
                }
            }
        }
    </script>
    <script>
        function updatedeliver(orderid) {
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin" || jQuery.inArray("approvedorders", perms) > -1) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/updatedeliver",
                    data: $(`#${orderid}deliverform`).serialize(),
                    type: 'post',
                    success: function(response) {
                        if (!response.hasOwnProperty('delivered')) {
                        $(`#${response.orderid}tr`).remove();
                        }
                    }
                })
            }
        }
    </script>
@endsection
