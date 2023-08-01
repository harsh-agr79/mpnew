@extends('admin/layout')

@section('main')
    <div class="mp-card" style="margin-top: 20px;">
        <div class="row">
            <div class='input-field col s6'>
                <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
                    type='search' name='search' id='search' />
                <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
            </div>
            <div class="input-field col s6">
                <select multiple onchange="fieldsfilter()" id="fields">
                    <option value="" disabled>Select Fields</option>
                    <option value="stock">Stock</option>
                    <option value="hidden">Hidden</option>
                </select>
            </div>
        </div>
    </div>


    <div class="nav-content bg" style="margin-top: 20px; border-radius: 10px;">
        <ul class="tabs tabs-transparent center">
            <li class="tab"><a class="textcol" href="#all">ALL</a></li>
            <li class="tab"><a class="textcol @if (session('category') == 'powerbank') active @endif"
                    href="#pb">Powerbank</a></li>
            <li class="tab"><a class="textcol @if (session('category') == 'charger') active @endif" href="#ch">Charger</a>
            </li>
            <li class="tab"><a class="textcol @if (session('category') == 'cable') active @endif" href="#ca">Cable</a>
            </li>
            <li class="tab"><a class="textcol @if (session('category') == 'btitem') active @endif"
                    href="#bt">Bluetooth</a></li>
            <li class="tab"><a class="textcol @if (session('category') == 'earphone') active @endif"
                    href="#ep">Earphone</a></li>
            <li class="tab"><a class="textcol @if (session('category') == 'others') active @endif" href="#oth">Others</a>
            </li>
        </ul>
    </div>

    <div style="margin-top: 20px;">
        <div class="mp-card" id="all">
            <table class="sortable">
                <thead>
                    <th></th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Unique Id</th>
                    <th class="stock" style="display: none;">Stock</th>
                    <th class="hidden" style="display: none;">Hide</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            @php
                                if ($item->hide == 'on') {
                                    $clr = 'black';
                                } elseif ($item->stock == '') {
                                    $clr = 'green darken-2';
                                } elseif ($item->stock == 'on') {
                                    $clr = 'red darken-2';
                                } else {
                                    $clr = '';
                                }
                            @endphp
                            <td>
                                <div class="{{ $clr }}" style="height: 35px; width:10px;"></div>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->produni_id }}</td>
                            <td class="stock" style="display: none;">@if ($item->stock == 'on')
                                Out of Stock
                            @else
                                In Stock                                
                            @endif</td>
                            <td class="hidden" style="display: none">@if ($item->hide == 'on')
                                Hidden
                            @else                           
                            @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @foreach ($cat as $item)
            @php
                if ($item->category == 'powerbank') {
                    $id = 'pb';
                    $prod = $pb;
                }
                if ($item->category == 'charger') {
                    $id = 'ch';
                    $prod = $ch;
                }
                if ($item->category == 'cable') {
                    $id = 'ca';
                    $prod = $ca;
                }
                if ($item->category == 'earphone') {
                    $id = 'ep';
                    $prod = $ep;
                }
                if ($item->category == 'btitem') {
                    $id = 'bt';
                    $prod = $bt;
                }
                if ($item->category == 'others') {
                    $id = 'oth';
                    $prod = $oth;
                }
            @endphp
            <div class="mp-card" id="{{ $id }}">
                @if ($admin->type == 'admin' || in_array('arrangeprod', $perms))
                <form action="{{ route('arrange.prod') }}" method="POST">
                    @csrf
                    @endif
                    <table>
                        <thead>
                            <th></th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Unique Id</th>
                            <th class="stock" style="display: none;">Stock</th>
                            <th class="hidden" style="display: none;">Hide</th>
                        </thead>
                        <tbody @if ($admin->type == 'admin' || in_array('arrangeprod', $perms))
                            class="row_position"
                        @endif>
                            @foreach ($prod as $item)
                                <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                                    @php
                                        if ($item->hide == 'on') {
                                            $clr = 'black';
                                        } elseif ($item->stock == '') {
                                            $clr = 'green darken-2';
                                        } elseif ($item->stock == 'on') {
                                            $clr = 'red darken-2';
                                        } else {
                                            $clr = '';
                                        }
                                    @endphp
                                    <td>
                                        <div class="{{ $clr }}" style="height: 35px; width:10px;"></div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->produni_id }}</td>
                                    <td class="stock" style="display: none;">@if ($item->stock == 'on')
                                        Out of Stock
                                    @else
                                        In Stock                                
                                    @endif</td>
                                    <td class="hidden" style="display: none">@if ($item->hide == 'on')
                                        Hidden
                                    @else                           
                                    @endif</td>
                                    <input type="hidden" name="id[]" value="{{ $item->id }}">
                                    <input type="hidden" name="category" value="{{ $item->category }}">
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($admin->type == 'admin' || in_array('arrangeprod', $perms))
                    <div class="fixed-action-btn">
                        <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                            style="border-radius: 10px;">
                            Submit
                            <i class="left material-icons">send</i>
                        </button>
                    </div>
                </form>
                @endif
            </div>
        @endforeach
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $(".row_position>tr").each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });
    </script>
    <script>
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let table = document.getElementsByTagName('table');
            let tr = $('tr')
            clsBtn.addEventListener("click", function() {
                a.value = '';
                a.focus();
                var filter = '';
                for (var i = 0; i < tr.length; i++) {
                    tr[i].style.display = "";
                }
                $('#cs-icon').text('search')
            });
            if (filter === '') {
                $('#cs-icon').text('search')
            } else {
                $('#cs-icon').text('close')
            }

            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                // console.log(td);
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textvalue = td[j].textContent || td[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none"
                        }
                    }
                }
            }
        }
    </script>
    <script>
        function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin" || jQuery.inArray("editproduct/{id}", perms) > -1) {
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', 'editproduct/' + id);
                $('#rmdeletelink').attr('href', 'deleteprod/' + id);
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
    </script>
    <script>
        function fieldsfilter(){
            $('.stock').hide();
            $('.hidden').hide();
            var clsnames = '';
            const vals = $('#fields').val();
            vals.forEach(e => {
                $(`.${e}`).show();
            });
        }
    </script>
@endsection
