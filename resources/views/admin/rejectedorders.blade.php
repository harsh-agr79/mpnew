@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="overflow-x: scroll; margin-top: 30px;">
            <div>
                <h5 class="center">Rejected Orders</h5>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>order Id</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class=" @if ($item->seen == '') z-depth-2 @endif"
                            oncontextmenu="rightmenu({{ $item->orderid }}); return false;"
                            ondblclick="opendetail({{ $item->orderid }}, '{{$item->seen}}')">
                            <td>
                                <div id="{{ $item->orderid . 'order' }}" class="{{$item->mainstatus}}"
                                    style="height: 35px; width:10px;"></div>
                            </td>
                            <td>{{ getNepaliDate($item->created_at) }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->orderid }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
@endsection