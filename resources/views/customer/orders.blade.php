@extends('customer/layout')

@section('main')
    <div class="mp-container">
        <div class="row">
            <div class="col s4">
                <div class="valign-wrapper">
                    <div class="blue" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Pending</span>
                </div>
                <div class="valign-wrapper">
                    <div class="amber darken-1" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Approved</span>
                </div>
                <div class="valign-wrapper">
                    <div class="deep-purple" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Packing Order</span>
                </div>
            </div>
            <div class="col s4">
                <h5 class="center" style="text-transform: capitalize;">{{$page}} orders</h5>
            </div>
            <div class="col s4">
                <div class="valign-wrapper">
                    <div class="red" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Rejected</span>
                </div>
                <div class="valign-wrapper">
                    <div class="green" style="width: 10px; height:10px; margin-right: 5px;"></div><span>Delivered</span>
                </div>
            </div>
        </div>
        <div class="mp-card">
            <form id="filter">
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input type="date" name="date" value="{{ $date }}" class="browser-default inp black-text">
                    </div>
                    <div class="col m4 s6" style="margin-top: 20px">
                        <button class="btn amber darken-1">Apply</button>
                        <a class="btn amber darken-1" href="{{ url('user/oldorders') }}">clear</a>
                    </div>
                    <div class="col m4 s6" style="margin-top: 20px">
                        @if ($page == 'old')
                            @php
                                $u = 'saved';
                            @endphp
                        @else
                            @php
                                $u = 'old'
                            @endphp
                        @endif
                        <a class="btn amber darken-1" href="{{url('/user/'.$u.'orders')}}">VIEW {{$u}} Orders</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <div class="center">
                {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
            </div>
            <div class="mp-card" style="margin-top: 10px;">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Date</th>
                            <th>Detail</th>
                            <th>Amount</th>
                            <th>Delivered</th>
                            <th>recieved</th>
                            @if ($page == 'saved')
                                <th>Confirm</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr
                                oncontextmenu="rightmenu({{ $item->orderid }}, '{{ $item->mainstatus }}'); return false;"
                              >
                                <td>
                                    <div id="{{ $item->orderid . 'order' }}" class="{{ $item->mainstatus }}"
                                        style="height: 35px; width:10px;"></div>
                                </td>
                                <td>{{ getNepaliDate($item->created_at) }}</td>
                                <td   onclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}', '{{ $item->mainstatus }}')">
                                    <div class="row" style="padding: 0; margin: 0;">
                                        <div class="col s12 blue-text" style="font-size: 12px; font-weight: 600;">{{ $item->name }}
                                        </div>
                                        <div class="col s12 blue-text" style="font-size: 8px;">{{ $item->orderid }}</div>
                                    </div>
                                </td>
                                <td>{{getTotalAmount($item->orderid)}}</td>
                                <td>
                                    @if ($item->delivered == 'on')
                                        <i class="material-icons textcol">check</i>
                                    @else
                                        <i class="material-icons textcol">close</i>
                                    @endif
                                </td>
                                <td>
                                    <label>
                                        <input @if ($item->recieved == 'on')
                                            checked
                                        @endif @if($item->mainstatus != 'green') disabled @endif type="checkbox" onclick="recieve({{$item->orderid}})"/>
                                        <span></span>
                                      </label>
                                </td>
                                @if ($page == 'saved')
                                    <td>
                                        <a href="{{url('user/confirmorder/'.$item->orderid)}}" class="btn amber">
                                            Send
                                        </a>
                                    </td>
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
            <a id="rmdeletelink">
                <li>Delete</li>
            </a>

        </ul>
    </div>

    <script>
        function rightmenu(orderid, status) {
            var rmenu = document.getElementById("rightmenu");
            if (status == 'blue') {
            console.log(orderid)
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/user/editorder/' + orderid);
                $('#rmdeletelink').attr('href', '/user/deleteorder/' + orderid);
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

        function opendetail(orderid, seen, ms) {
                window.open('/user/detail/' + orderid, "_self");
        }
        function recieve(id){
            $.ajax({
                    type: 'get',
                    url: '/user/recieve/' + id,
                    success: function(response) {
                        console.log(response);
                    }
                })
        }
    </script>
@endsection
