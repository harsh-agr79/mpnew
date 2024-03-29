@extends('marketer/layout')

@section('main')
    <div>
        <div>
            <h5 class="center">All Orders</h5>
        </div>
        <div class="mp-card">
            <form id="filter">
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input type="date" name="date" value="{{$date}}" class="browser-default inp black-text">
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <input type="text" name="name" id="customer" value="{{$name}}" placeholder="Customer"
                            class="autocomplete browser-default inp black-text" autocomplete="off">
                    </div>
                    <div class="col m4 s12" style="margin-top: 20px">
                        <button class="btn amber darken-1">Apply</button>
                        <a class="btn amber darken-1" href="{{url('orders')}}">clear</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <div class="center">
                {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
            </div>
            <div class="mp-card" style="overflow-x: scroll;">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Date</th>
                            <th>Detail</th>
                            <th>Delivered</th>
                            <th>recieved</th>
                            @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                <th class="tamt" style="display: none;">Amount</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                                oncontextmenu="rightmenu({{ $item->orderid }}, '{{$item->mainstatus}}'); return false;"
                                ondblclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}', '{{$item->mainstatus}}')">
                                <td>
                                    <div id="{{ $item->orderid . 'order' }}" class="{{$item->mainstatus}}"
                                        style="height: 35px; width:10px;"></div>
                                </td>
                                <td>{{ getNepaliDate($item->created_at) }}</td>
                                <td>
                                    <div class="row" style="padding: 0; margin: 0;">
                                        <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name }}</div>
                                        <div class="col s12" style="font-size: 8px;">{{ $item->orderid }}</div>
                                    </div>
                                </td>
                                <td>@if ($item->delivered == 'on')
                                    <i class="material-icons textcol">check</i>
                                @else
                                    <i class="material-icons textcol">close</i>
                                @endif</td>
                                <td>{{$item->recieveddate}}</td>
                                @if ($admin->type == 'admin' || in_array('totalamount', $perms))
                                <td class="tamt" style="display: none;"> {{ getTotalAmount($item->orderid) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="center">
                {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
            </div>
        </div>
    </div>
    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
           
        </ul>
    </div>

    <script>
           function rightmenu(orderid, status) {
            console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "marketer" && status == 'blue') {
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/marketer/editorder/' + orderid);
                // $('#rmdeletelink').attr('href', 'marketer/deleteorder/' + orderid);
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
            else if(admintype == 'marketer'){
                window.open('/marketer/detail/' + orderid, "_self");
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/marketer/findcustomer',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#customer').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })
    </script>
@endsection