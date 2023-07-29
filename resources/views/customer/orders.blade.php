@extends('customer/layout')

@section('main')
    <div class="mp-container">
        <div>
            <h5 class="center" style="text-transform: capitalize;">{{$page}} orders</h5>
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
                            <th>Delivered</th>
                            <th>recieved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr
                                oncontextmenu="rightmenu({{ $item->orderid }}, '{{ $item->mainstatus }}'); return false;"
                                onclick="opendetail({{ $item->orderid }}, '{{ $item->seen }}', '{{ $item->mainstatus }}')">
                                <td>
                                    <div id="{{ $item->orderid . 'order' }}" class="{{ $item->mainstatus }}"
                                        style="height: 35px; width:10px;"></div>
                                </td>
                                <td>{{ getNepaliDate($item->created_at) }}</td>
                                <td>
                                    <div class="row" style="padding: 0; margin: 0;">
                                        <div class="col s12" style="font-size: 12px; font-weight: 600;">{{ $item->name }}
                                        </div>
                                        <div class="col s12" style="font-size: 8px;">{{ $item->orderid }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->delivered == 'on')
                                        <i class="material-icons textcol">check</i>
                                    @else
                                        <i class="material-icons textcol">close</i>
                                    @endif
                                </td>
                                <td>{{ $item->recieveddate }}</td>
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
    </script>
@endsection
