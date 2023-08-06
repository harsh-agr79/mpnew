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
                        <option value="address">Address</option>
                        <option value="area">Area</option>
                        <option value="state">state</option>
                        <option value="district">District</option>
                        <option value="startdate">Target Start Date</option>
                        <option value="enddate">Target End Date</option>
                        <option value="target">Target Net</option>
                        <option value="sales">Total Sales</option>
                        <option value="completed">Target Completed</option>
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
            <form>
                <table class="sortable">
                    <thead>
                        <th>|</th>
                        <th>SN</th>
                        <th>Name</th>
                        <th>shop</th>
                        {{-- <th>Address</th> --}}
                        <th>Type</th>
                        {{-- <th>Bill Count</th> --}}
                        <th class="address" style="display: none;">Address</th>
                        <th class="area" style="display: none;">Area</th>
                        <th class="state" style="display: none;">State</th>
                        <th class="district" style="display: none;">District</th>
                        <th class="startdate" style="display: none;">Target Start Date</th>
                        <th class="enddate" style="display: none;">Target End Date</th>
                        <th class="target" style="display: none;">Target net</th>
                        <th class="sales" style="display: none;">Total Sales</th>
                        <th class="completed" style="display: none;">Target Completed</th>
                        <th class="contact" style="display: none;">Contact</th>
                        <th class="userid" style="display: none;">User id</th>
                        <th class="referer" style="display: none;">referer</th>
                        <th class="uniqueid" style="display: none;">Unique Id</th>
                        <th class="activity" style="display: none;">activity</th>
                        @if ($admin->type == 'admin')
                            <th>Direct Login</th>
                        @endif
                    </thead>
                    <tbody>
                        @php
                            $a = 0;
                        @endphp
                        @foreach ($data as $item)
                            <tr oncontextmenu="rightmenu({{ $item->id }}); return false;">
                                <td sorttable_customkey="{{ $item->activity }}">
                                    <div class="{{ $stat = $item->actcolor }}" style="height: 35px; width:10px;"></div>
                                </td>
                                <td>{{ $a = $a + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->shopname }}</td>
                                {{-- <td>{{ $item->address }}</td> --}}
                                <td>{{ $item->type }}</td>
                                {{-- <td>{{ $item->billcnt }}</td> --}}
                                {{-- <input type="hidden" name="id[]" value="{{$item->id}}"> --}}
                                <td class="address" style="display: none;" sorttable_customkey="{{ $item->address }}">
                                    {{ $item->address }}</td>
                                <td class="area" style="display: none;">{{ $item->area }}</td>
                                <td class="state" style="display: none;">{{ $item->state }}</td>
                                <td class="district" style="display: none;">{{ $item->district }}</td>
                                @php
                                    $target = DB::table('target')
                                        ->where('customerid', $item->id)
                                        ->where('enddate', '>=', date('Y-m-d'))
                                        ->where('startdate', '<=', date('Y-m-d'))
                                        ->first();
                                    if ($target != null) {
                                        $sales = DB::table('orders')
                                            ->where('name', $item->name)
                                            ->where('created_at', '>=', $target->startdate)
                                            ->where('created_at', '<=', $target->enddate)
                                            ->where(['deleted' => null, 'save' => null, 'status' => 'approved'])
                                            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
                                            ->selectRaw('*,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
                                            ->groupBy('deleted')
                                            ->get();
                                    } else {
                                        if (date('Y-m-d') < date('Y-10-18')) {
                                            $date2 = date('Y-10-17');
                                            $date = date('Y-m-d', strtotime($date2 . ' -1 year +1 day'));
                                        } else {
                                            $date = date('Y-10-18');
                                            $date2 = date('Y-m-d', strtotime($date . ' + 1 year -1 day'));
                                        }
                                        $sales = DB::table('orders')
                                            ->where('name', $item->name)
                                            ->where('created_at', '>=', $date)
                                            ->where('created_at', '<=', $date2)
                                            ->where(['deleted' => null, 'save' => null, 'status' => 'approved'])
                                            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
                                            ->selectRaw('*,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
                                            ->groupBy('deleted')
                                            ->get();
                                    }
                                @endphp
                                @if ($target != null)
                                    <td class="startdate" style="display: none;">{{ $target->startdate }}</td>
                                    <td class="enddate" style="display: none;">{{ $target->enddate }}</td>
                                    <td class="target" style="display: none;">{{ money($t = $target->net) }}</td>
                                    @if (!$sales->isEmpty())
                                        <td class="sales" style="display: none;">
                                            {{ money($s = $sales[0]->samt - $sales[0]->damt) }}</td>
                                        <td class="completed" style="display: none;">{{ round(($s / $t) * 100) }}%</td>
                                    @else
                                        <td class="sales"style="display: none;"></td>
                                        <td class="completed" style="display: none;"></td>
                                    @endif
                                @else
                                    <td class="startdate" style="display: none;"></td>
                                    <td class="enddate" style="display: none;"></td>
                                    <td class="target" style="display: none;"></td>
                                    @if (!$sales->isEmpty())
                                        <td class="sales" style="display: none;">
                                            {{ money($s = $sales[0]->samt - $sales[0]->damt) }}</td>
                                        <td class="completed" style="display: none;">{{ round(($s / $t) * 100) }}%</td>
                                    @else
                                        <td class="sales"style="display: none;"></td>
                                        <td class="completed" style="display: none;"></td>
                                    @endif
                                @endif
                                <td class="contact" style="display: none;">{{ $item->contact }}</td>
                                <td class="userid" style="display: none;">{{ $item->user_id }}</td>
                                <td class="referer" style="display: none;">{{ $item->refname }}</td>
                                <td class="uniqueid" style="display: none;">{{ $item->cusuni_id }}</td>
                                <td class="activity" style="display: none;">{{ $item->activity }}</td>
                                @if ($admin->type == 'admin')
                                    <td><a href="{{ url('directlogin/customer/' . $item->id) }}"
                                            class="btn-small amber textcol"><i class="material-symbols-outlined textcol">
                                                login
                                            </i></a></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td id="totalrows"></td>
                            <td>Total Rows</td>
                        </tr>
                    </tfoot>
                </table>
                {{-- <div class="fixed-action-btn">
                <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                    style="border-radius: 10px;">
                    Submit
                    <i class="left material-icons">send</i>
                </button>
            </div> --}}
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
            let tr = $('tbody tr')
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

            let sum = 0;
            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                // console.log(td);
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textvalue = td[j].textContent || td[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            sum = sum + 1;
                            break;
                        } else {
                            tr[i].style.display = "none"
                        }
                    }
                }
            }
            $('#totalrows').text(sum);
        }
    </script>
    <script>
        function fieldsfilter() {
            $('.address').hide();
            $('.area').hide();
            $('.state').hide();
            $('.district').hide();
            $('.startdate').hide();
            $('.enddate').hide();
            $('.target').hide();
            $('.sales').hide();
            $('.completed').hide();
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

            if (value == 'Bagmati') {
                var dis = bag;
            }
            if (value == 'Gandaki') {
                var dis = gan;
            }
            if (value == 'Karnali') {
                var dis = kar
            }
            if (value == 'Madhesh') {
                var dis = mad
            }
            if (value == 'Koshi') {
                var dis = kos
            }
            if (value == 'Lumbini') {
                var dis = lum
            }
            if (value == 'Sudur Paschim') {
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
