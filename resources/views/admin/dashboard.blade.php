@extends('admin/layout')

@section('main')
    <div>
        <div>
            <h5 class="center">Dashboard</h5>
        </div>
        <label>
            <input type="checkbox" onclick="toggleamt()" />
            <span>View Total Amount</span>
        </label>
        <div class="row">
            <div class="col m6 s12">
                <div class="mp-card"  style="overflow-x: scroll">
                    <h6 class="center">Direct Orders</h6>
                    <table >
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>order Id</th>
                                <th>Seen By</th>
                                <th class="tamt" style="display: none;">Amount</th>
                                <th>Pack Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mpe as $item)
                                @if ($item->seen == '')
                                    <tr class="z-depth-2" oncontextmenu="rightmenu({{ $item->orderid }}); return false;" ondblclick="opendetail({{ $item->orderid }})">
                                    @else
                                    <tr oncontextmenu="rightmenu({{ $item->orderid }}); return false;" ondblclick="opendetail({{ $item->orderid }})">
                                @endif
                                <td>
                                    <div id="{{ $item->orderid . 'order' }}" class="{{ $stat = getpstat($item->orderid) }}"
                                        style="height: 35px; width:10px;"></div>
                                </td>
                                <td>{{ getNepaliDate($item->created_at) }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->orderid }}</td>
                                <td>{{ $item->seenby }}</td>
                                <td class="tamt" style="display: none;">
                                    @if ($stat == 'blue')
                                        {{ $item->sl - $item->dis }}
                                    @else
                                        {{ $item->sla - $item->disa }}
                                    @endif
                                </td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row col m6 s12">
                <div class="col s12">
                    <div class="mp-card">
                        <h6 class="center">Marketer Orders</h6>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>order Id</th>
                                    <th>Seen By</th>
                                    <th class="tamt" style="display: none;">Amount</th>
                                    <th>Pack Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dealer as $item)
                                @if ($item->seen == '')
                                    <tr class="z-depth-2" oncontextmenu="rightmenu({{ $item->orderid }}); return false;" ondblclick="opendetail({{ $item->orderid }})">
                                    @else
                                    <tr oncontextmenu="rightmenu({{ $item->orderid }}); return false;" ondblclick="opendetail({{ $item->orderid }})">
                                @endif
                                    <td>
                                        <div id="{{ $item->orderid . 'order' }}"
                                            class="{{ $stat = getpstat($item->orderid) }}"
                                            style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ getNepaliDate($item->created_at) }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->orderid }}</td>
                                    <td>{{ $item->seenby }}</td>
                                    <td class="tamt" style="display: none;">
                                        @if ($stat == 'blue')
                                            {{ $item->sl - $item->dis }}
                                        @else
                                            {{ $item->sla - $item->disa }}
                                        @endif
                                    </td>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col s12" style="margin-top: 30px;">
                    <div class="mp-card">
                        <h6 class="center">Pending Orders</h6>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>order Id</th>
                                    <th>Seen By</th>
                                    <th class="tamt" style="display: none;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pending as $item)
                                @if ($item->seen == '')
                                <tr class="z-depth-2" oncontextmenu="rightmenu({{ $item->orderid }}); return false;" ondblclick="opendetail({{ $item->orderid }})">
                                @else
                                <tr oncontextmenu="rightmenu({{ $item->orderid }}); return false;" ondblclick="opendetail({{ $item->orderid }})">
                            @endif
                                    <td>
                                        <div id="{{ $item->orderid . 'order' }}" class="{{ getpstat($item->orderid) }}"
                                            style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ getNepaliDate($item->created_at) }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->orderid }}</td>
                                    <td>{{ $item->seenby }}</td>
                                    <td class="tamt" style="display: none;">{{ $item->samt - $item->damt }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink"><li>Edit</li></a>
            <a id="rmdeletelink"><li>Delete</li></a>
        </ul>
    </div>

    <script>
        function toggleamt() {
            $('.tamt').toggle();
        }

        function rightmenu(orderid) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'block';
            rmenu.style.top = mouseY(event) + 'px';
            rmenu.style.left = mouseX(event) + 'px';
            $('#rmeditlink').attr('href', '/editorder/'+orderid);
            $('#rmdeletelink').attr('href', '/deleteorder/'+orderid);
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
        function opendetail(orderid){
            window.open('/detail/'+orderid, "_self");
        }
    </script>
    <script>
        function updatecln(orderid) {
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
    </script>
@endsection
