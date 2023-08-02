@extends('admin/layout')

@section('main')
    <div>
        <div>
            <div class="row">
                <div class='input-field col l6 m6 s12'>
                    <input class='validate browser-default inp search black-text' onkeyup="searchFun()" autocomplete="off"
                        type='search' name='search' id='search' />
                    <span class="field-icon" id="close-search"><span class="material-icons"
                            id="cs-icon">search</span></span>
                </div>
                <div class="input-field col s12 m6 l6">
                    <select multiple onchange="fieldsfilter()" id="fields">
                        <option value="" disabled>Select Fields</option>
                        <option value="contact">Contact</option>
                        <option value="userid">User Id</option>
                        <option value="referer">Referer</option>
                        <option value="uniqueid">Unique Id</option>
                        <option value="activity">Activity</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mp-card" style="overflow-x: scroll;">
            <form action="{{route('addup')}}" method="POST" enctype="multipart/form-data">
                @csrf
           
            <table>
                <thead>
                    <th>|</th>
                    <th>SN</th>
                    <th>Name</th>
                    <th>shop</th>
                    {{-- <th>Address</th> --}}
                    <th>Type</th>
                    {{-- <th>Bill Count</th> --}}
                    <th>Address</th>
                    <th>Area</th>
                    <th>State</th>
                    <th>District</th>
                    <th class="contact" style="display: none;">Contact</th>
                    <th class="userid" style="display: none;">User id</th>
                    <th class="referer" style="display: none;">referer</th>
                    <th class="uniqueid" style="display: none;">Unique Id</th>
                    <th class="activity" style="display: none;">activity</th>
                </thead>
                <tbody>
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($data as $item)
                    <input type="hidden" name="id[]" value="{{$item->id}}">
                        <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td sorttable_customkey="{{ $item->activity }}">
                                <div class="{{ $stat = $item->actcolor }}" style="height: 35px; width:10px;"></div>
                            </td>
                            <td>{{$item->id}}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->shopname }}</td>
                            {{-- <td>{{ $item->address }}</td> --}}
                            <td>{{ $item->type }}</td>
                            {{-- <td>{{ $item->billcnt }}</td> --}}
                            <td sorttable_customkey="{{$item->address}}"><input type="text" name="address[]" value="{{ $item->address }}"
                                    class="browser-default inp"></td>
                            <td><input type="text" name="area[]" value="{{ $item->area }}"
                                    class="browser-default inp"></td>
                            <td> <select id="state{{ $item->id }}" name="state[]"
                                    class="browser-default selectinp black-text" onchange="district({{ $item->id }})"
                                    >
                                    @if ($item->state != null)
                                        <option selected value="{{ $item->state }}">{{ $item->state }}</option>
                                        <option class="black-text" value="">State</option>
                                    @else
                                        <option class="black-text" value="" selected>State</option>
                                    @endif
                                    <option class="black-text" value="Bagmati">Bagmati</option>
                                    <option class="black-text" value="Gandaki">Gandaki</option>
                                    <option class="black-text" value="Karnali">Karnali</option>
                                    <option class="black-text" value="Lumbini">Lumbini</option>
                                    <option class="black-text" value="Madhesh">Madhesh</option>
                                    <option class="black-text" value="Koshi">Koshi</option>
                                    <option class="black-text" value="Sudur Paschim">Sudur Paschim</option>
                                </select></td>
                            <td> <select id="district{{ $item->id }}" name="district[]"
                                    class="browser-default selectinp black-text">
                                    @if ($item->state != null)
                                    <option selected value="{{ $item->district }}">{{ $item->district }}</option>
                                    @else
                                    <option class="black-text" value="" selected>District</option>
                                    @endif
                                </select>
                            </td>
                            <td class="contact" style="display: none;">{{ $item->contact }}</td>
                            <td class="userid" style="display: none;">{{ $item->user_id }}</td>
                            <td class="referer" style="display: none;">{{ $item->refname }}</td>
                            <td class="uniqueid" style="display: none;">{{ $item->cusuni_id }}</td>
                            <td class="activity" style="display: none;">{{ $item->activity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="fixed-action-btn">
                <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                    style="border-radius: 10px;">
                    Submit
                    <i class="left material-icons">send</i>
                </button>
            </div>
        </form>
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
        function fieldsfilter() {
            $('.contact').hide();
            $('.userid').hide();
            $('.referer').hide();
            $('.uniqueid').hide();
            $('.activity').hide();
            var clsnames = '';
            const vals = $('#fields').val();
            vals.forEach(e => {
                $(`.${e}`).show();
            });
        }
    </script>
    <script>
        function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin" || jQuery.inArray("editcustomer/{id}", perms) > -1) {
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/editcustomer/' + id);
                $('#rmdeletelink').attr('href', '/deletecustomer/' + id);
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

        function district(id) {
            var value = $(`#state${id}`).val()
            // console.log(value);
            var bag = [
                "sindhuli",
                "ramechhap",
                "dolakha",
                "bhaktapur",
                "dhading",
                "kathmandu",
                "kavrepalanchok",
                "lalitpur",
                "nuwakot",
                "rasuwa",
                "sindhupalchok",
                "chitwan",
                "makwanpur"
            ]
            var gan = [
                "baglung",
                "gorkha",
                "kaski",
                "lamjung",
                "manang",
                "mustang",
                "myagdi",
                "nawalpur",
                "parbat",
                "syangja",
                "tanahun"
            ]
            var kar = [
                "western rukum ",
                "salyan",
                "dolpa",
                "humla",
                "jumla",
                "kalikot",
                "mugu",
                "surkhet",
                "dailekh",
                "jajarkot"
            ]
            var lum = [
                "kapilvastu",
                "rupandehi",
                "arghakhanchi",
                "gulmi",
                "palpa",
                "dang",
                "pyuthan",
                "rolpa",
                "eastern rukum ",
                "banke",
                "bardiya"
            ]
            var mad = [
                "sarlahi",
                "dhanusha",
                "bara",
                "rautahat",
                "saptari",
                "siraha",
                "mahottari",
                "parsa"
            ]
            var kos = [
                "bhojpur",
                "dhankuta",
                "ilam",
                "jhapa",
                "khotang",
                "morang",
                "okhaldhunga",
                "panchthar",
                "sankhuwasabha",
                "solukhumbu",
                "sunsari",
                "taplejung",
                "terhathum",
                "udayapur"
            ]
            var sud = [
                "achham",
                "baitadi",
                "bajhang",
                "bajura",
                "dadeldhura",
                "darchula",
                "doti",
                "kailali",
                "kanchanpur"
            ]
           
            if(value == 'Bagmati'){
                var dis = bag;
            }
            if(value == 'Gandaki'){
                var dis = gan;
            }
            if(value == 'Karnali'){
                var dis = kar
            }
            if(value == 'Madhesh'){
                var dis = mad
            }
            if(value == 'Koshi'){
                var dis = kos
            }
            if(value == 'Lumbini'){
                var dis = lum
            }
            if(value == 'Sudur Paschim'){
               var dis = sud
            }
            console.log(dis)
            var sc = $(`#district${id}`);
            sc.empty();
            sc.append($('<option></option>').attr('value', null).attr('selected', 'true').text(
                        'select District'));
            // dis.forEach(element => {
            //     $sc.append($('<option></option>')
            //                 .attr("value", value.subcategory).text(value.subcategory))
            // });
            for (let i = 0; i < dis.length; i++) {
                sc.append($('<option></option>')
                            .attr("value", dis[i]).text(dis[i]))
            }
        }
    </script>
@endsection
