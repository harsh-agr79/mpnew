@extends('admin/layout')

@section('main')

    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="row">
                <form>
                    <div class="input-field col s12 m4 l4">
                        <input type="text" name="name" id="customer" placeholder="Customer"
                            class="autocomplete browser-default inp black-text" value="{{ $name2 }}"
                            autocomplete="off">
                    </div>
                    <div class="col s6 m4 l4 center" style="margin-top: 15px;">
                        <button class="btn amber black-text">Apply</button>
                    </div>
                </form>
                <div class="col s6 m4 l4 center" style="margin-top: 15px;">
                    <a href="{{ url('/refererstatement') }}" class="btn amber black-text">Clear</a>
                </div>
            </div>
        </div>
        <div style="margin-top: 20px;">
            @if ($data == 'no data')
                <div class="center">
                    <h5>Select Referer</h5>
                </div>
            @else
                @php
                    $type = 'marketer';
                    $refid = DB::table('admins')
                        ->where('name', $name2)
                        ->first()->id;
                @endphp

                <div class="mp-card">
                    <div class="switch  row" style="margin: 20px;">
                        <div class='input-field col s12 m4'>
                            <input class='validate browser-default inp search black-text' onkeyup="searchFun()"
                                autocomplete="off" type='search' name='search' id='search' />
                            <span class="field-icon" id="close-search"><span class="material-icons"
                                    id="cs-icon">search</span></span>
                        </div>
                        <div class="col s12 m8" style="margin-top: 20px;">
                            <label>
                                Name
                                <input onchange="tog()" type="checkbox">
                                <span class="lever"></span>
                                Shop Name
                            </label>
                            <label style="margin-left: 30px;">
                                <input onchange="bal()" checked type="checkbox" />
                                <span> Balance</span>
                            </label>
                            <label>
                                <label>
                                    <input onchange="tdy()" type="checkbox" />
                                    <span>30days</span>
                                </label>
                                <label>
                                    <input type="checkbox" onchange="fdy()" />
                                    <span>45days</span>
                                </label>
                                <label>
                                    <input type="checkbox" onchange="sdy()" />
                                    <span>60days</span>
                                </label>
                                <label>
                                    <input type="checkbox" onchange="ndy()" />
                                    <span>90days</span>
                                </label>
                        </div>
                    </div>
                    <div class="mp-card" style="overflow-x: scroll;">
                        <table class="sortable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th class="name">Name</th>
                                    <th class="shop" style="display: none;">Shop</th>
                                    <th class="">type</th>
                                    <th class="bal ">Balance</th>
                                    <th class="tdy " style="display: none;">30 days</th>
                                    <th class="fdy " style="display: none;">45 days</th>
                                    <th class="sdy " style="display: none;">60 days</th>
                                    <th class="ndy " style="display: none;">90 days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $a = 0;
                                @endphp
                                @foreach ($data as $item)
                                    @php
                                        $bal = explode('|', $item->balance);
                                    @endphp
                                    <tr ondblclick="openbs('{{ $item->name }}')">
                                        <td>{{ $a = $a + 1 }}</td>
                                        <td class="name">{{ $item->name }}</td>
                                        <td class="shop" style="display: none;">{{ $item->shopname }}</td>
                                        <td
                                            class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                                            {{ $item->type }}</td>
                                        <td class="{{ $bal[0] }} lighten-5 bal black-text ">{{ $bal[1] }}</td>
                                        <td class="@if ($item->thirdays > 0) red lighten-5 @else green lighten-5 @endif tdy black-text "
                                            style="display: none;">{{ $item->thirdays }}</td>
                                        <td class="@if ($item->fourdays > 0) red lighten-5 @else green lighten-5 @endif fdy black-text "
                                            style="display: none;">{{ $item->fourdays }}</td>
                                        <td class="@if ($item->sixdays > 0) red lighten-5 @else green lighten-5 @endif sdy black-text "
                                            style="display: none;">{{ $item->sixdays }}</td>
                                        <td class="@if ($item->nindays > 0) red lighten-5 @else green lighten-5 @endif ndy black-text "
                                            style="display: none;">{{ $item->nindays }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    function tog() {
                        var name = document.getElementsByClassName('name');
                        var shop = document.getElementsByClassName('shop');
                        $(name).toggle();
                        $(shop).toggle();
                    }

                    function bal() {
                        $('.bal').toggle();
                    }

                    function tdy() {
                        $('.tdy').toggle();
                    }

                    function fdy() {
                        $('.fdy').toggle();
                    }

                    function sdy() {
                        $('.sdy').toggle();
                    }

                    function ndy() {
                        $('.ndy').toggle();
                    }
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

                    function openbs(name) {
                        window.open('/balancesheet/' + name, "_self");
                    }
                </script>

                <div class="mp-card" style="margin-top: 30px;">
                    <form class="row">
                        <div class="col s2" style="padding: 0; margin: 0;">
                            <label>Start Month:</label>
                            <select name="startmonth" class="browser-default selectinp">
                                <option value="">Select Start Month</option>
                                <option value="{{ $smonth }}" selected>{{ $smonth }}</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="8">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="col s4" style="padding: 0; margin: 0;">
                            <label>Start Year:</label>
                            <select name="startyear" class="browser-default selectinp">
                                <option value="">Select Start Year</option>
                                <option value="{{ $syear }}" selected>{{ $syear }}</option>
                                <option value="2078">2078</option>
                                <option value="2079">2079</option>
                                <option value="2080">2080</option>
                            </select>
                        </div>
                        <div class="col s2" style="padding: 0; margin: 0;">
                            <label>End Month:</label>
                            <select name="endmonth" value="{{ $emonth }}" class="browser-default selectinp">
                                <option value="">Select End Month</option>
                                <option value="{{ $emonth }}" selected>{{ $emonth }}</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="8">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="col s4" style="padding: 0; margin: 0;">
                            <label>End Year:</label>
                            <select name="endyear" value="{{ $eyear }}" class="browser-default selectinp">
                                <option value="">Select End Year</option>
                                <option value="{{ $eyear }}" selected>{{ $eyear }}</option>
                                <option value="2078">2078</option>
                                <option value="2079">2079</option>
                                <option value="2080">2080</option>
                            </select>
                        </div>
                        <input type="hidden" name="name" value="{{ $name2 }}">
                        <div class="input-field col s3 l1">
                            <button class="btn amber darken-1">Apply</button>
                        </div>
                        <div class="input-field col s3 l1">
                            <a href="{{ url('/refererstatement?name=' . $name2) }}" class="btn amber darken-1">Clear</a>
                        </div>
                    </form>
                </div>
                <div class="mp-card" style="margin-top: 20px;">
                    @php
                        $numbills = 0;
                        $totalsales = 0;
                        $nummonths = 0;
                        $months = ['first', 'Baisakh', 'Jeth', 'Asar', 'Shrawan', 'Bhadra', 'Asoj', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
                        $forchart = [];
                    @endphp

                    <ul class="collapsible">
                        <div>
                            <h6 class="center">Monthly Sales</h6>
                        </div>
                        <div class="container">
                            <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                        </div>
                        <div class="row">
                            <div class="col s6" style="font-weight: 700;">Month-Year</div>
                            <div class="col s6" style="font-weight: 700;">Sales</div>
                        </div>
                        @foreach ($data2 as $item)
                            @if ($item->nepyear > $syear && $item->nepyear < $eyear)
                                @php
                                    $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                @endphp
                                <li>
                                    <div class="collapsible-header row"
                                        @if ($name != null) ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}', '{{ $name }}')" @endif>
                                        <span
                                            class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                            class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                                    </div>
                                    @if ($name == null)
                                        @php
                                            $totalsales = $totalsales + ($item->sl - $item->dis);
                                            $nummonths = $nummonths + 1;
                                            $numbills =
                                                $numbills +
                                                DB::table('orders')
                                                    ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                    ->groupBy('orderid')
                                                    ->get()
                                                    ->count();
                                        @endphp
                                        <div class="collapsible-body"><span>
                                                @php
                                                    
                                                    $query = DB::table('orders');
                                                    if ($type == 'marketer') {
                                                        $cuslist = marketercuslist($refid);
                                                        $query = $query->WhereIn('orders.name', $cuslist);
                                                    }
                                                    $query = $query
                                                        ->where(['orders.deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                                                        ->orderBy('sl', 'DESC')
                                                        ->groupBy('name')
                                                        ->join('customers', 'orders.cusuni_id', '=', 'customers.cusuni_id')
                                                        ->get();
                                                    $sls = $query;
                                                    $cslist = $sls->pluck('name')->toArray();
                                                    $query2 = DB::table('customers')->whereNotIn('name', $cslist);
                                                    if ($type == 'marketer') {
                                                        $cuslist = marketercuslist($refid);
                                                        $query2 = $query2->WhereIn('name', $cuslist);
                                                    }
                                                    $query2 = $query2->get();
                                                    $sls2 = $query2;
                                                @endphp
                                                <table class="sortable">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>type</th>
                                                            <th>Sales</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($sls as $item2)
                                                            <tr
                                                                ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item2->nepyear, $item2->nepmonth, getLastDate($item2->nepmonth, date('y', strtotime($item2->nepyear)))) }}', '{{ $item2->name }}')">
                                                                <td>
                                                                    {{ $item2->name }}
                                                                </td>
                                                                <td
                                                                    class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                    {{ $item2->type }}</td>
                                                                <td>{{ money($item2->sl - $item2->dis) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach ($sls2 as $item2)
                                                            <tr>
                                                                <td>{{ $item2->name }}</td>
                                                                <td
                                                                    class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                    {{ $item2->type }}</td>
                                                                <td>0</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </span>
                                        </div>
                                    @else
                                        @php
                                            $totalsales = $totalsales + ($item->sl - $item->dis);
                                            $nummonths = $nummonths + 1;
                                            $numbills =
                                                $numbills +
                                                DB::table('orders')
                                                    ->where('name', $name)
                                                    ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                    ->groupBy('orderid')
                                                    ->get()
                                                    ->count();
                                        @endphp
                                    @endif

                                </li>
                            @elseif($syear == $eyear)
                                @if ($smonth <= $item->nepmonth && $emonth >= $item->nepmonth && $item->nepyear == $syear)
                                    @php
                                        $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                    @endphp
                                    <li>
                                        <div class="collapsible-header row"
                                            @if ($name != null) ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}', '{{ $name }}')" @endif>
                                            <span
                                                class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                                class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                                        </div>
                                        @if ($name == null)
                                            @php
                                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                                $nummonths = $nummonths + 1;
                                                $numbills =
                                                    $numbills +
                                                    DB::table('orders')
                                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->groupBy('orderid')
                                                        ->get()
                                                        ->count();
                                            @endphp
                                            <div class="collapsible-body"><span>
                                                    @php
                                                        
                                                        $query = DB::table('orders');
                                                        if ($type == 'marketer') {
                                                            $cuslist = marketercuslist($refid);
                                                            $query = $query->WhereIn('orders.name', $cuslist);
                                                        }
                                                        $query = $query
                                                            ->where(['orders.deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                            ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                                                            ->orderBy('sl', 'DESC')
                                                            ->groupBy('name')
                                                            ->join('customers', 'orders.cusuni_id', '=', 'customers.cusuni_id')
                                                            ->get();
                                                        $sls = $query;
                                                        
                                                        $cslist = $sls->pluck('name')->toArray();
                                                        $query2 = DB::table('customers')->whereNotIn('name', $cslist);
                                                        if ($type == 'marketer') {
                                                            $cuslist = marketercuslist($refid);
                                                            $query2 = $query2->WhereIn('name', $cuslist);
                                                        }
                                                        $query2 = $query2->get();
                                                        $sls2 = $query2;
                                                    @endphp
                                                    <table class="sortable">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>type</th>
                                                                <th>Sales</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($sls as $item2)
                                                                <tr
                                                                    ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item2->nepyear, $item2->nepmonth, getLastDate($item2->nepmonth, date('y', strtotime($item2->nepyear)))) }}', '{{ $item2->name }}')">
                                                                    <td>
                                                                        {{ $item2->name }}
                                                                    </td>
                                                                    <td
                                                                        class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                        {{ $item2->type }}</td>
                                                                    <td>{{ money($item2->sl - $item2->dis) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            @foreach ($sls2 as $item2)
                                                                <tr>
                                                                    <td>{{ $item2->name }}</td>
                                                                    <td
                                                                        class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                        {{ $item2->type }}</td>
                                                                    <td>0</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </span>
                                            </div>
                                        @else
                                            @php
                                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                                $nummonths = $nummonths + 1;
                                                $numbills =
                                                    $numbills +
                                                    DB::table('orders')
                                                        ->where('name', $name)
                                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->groupBy('orderid')
                                                        ->get()
                                                        ->count();
                                            @endphp
                                        @endif
                                    </li>
                                @endif
                            @elseif($item->nepyear == $syear)
                                @if ($smonth <= $item->nepmonth)
                                    @php
                                        $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                    @endphp
                                    <li>
                                        <div class="collapsible-header row"
                                            @if ($name != null) ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}', '{{ $name }}')" @endif>
                                            <span
                                                class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                                class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                                        </div>
                                        @if ($name == null)
                                            @php
                                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                                $nummonths = $nummonths + 1;
                                                $numbills =
                                                    $numbills +
                                                    DB::table('orders')
                                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->groupBy('orderid')
                                                        ->get()
                                                        ->count();
                                            @endphp
                                            <div class="collapsible-body"><span>
                                                    @php
                                                        
                                                        $query = DB::table('orders');
                                                        if ($type == 'marketer') {
                                                            $cuslist = marketercuslist($refid);
                                                            $query = $query->WhereIn('orders.name', $cuslist);
                                                        }
                                                        $query = $query
                                                            ->where(['orders.deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                            ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                                                            ->orderBy('sl', 'DESC')
                                                            ->groupBy('name')
                                                            ->join('customers', 'orders.cusuni_id', '=', 'customers.cusuni_id')
                                                            ->get();
                                                        $sls = $query;
                                                        $cslist = $sls->pluck('name')->toArray();
                                                        $query2 = DB::table('customers')->whereNotIn('name', $cslist);
                                                        if ($type == 'marketer') {
                                                            $cuslist = marketercuslist($refid);
                                                            $query2 = $query2->WhereIn('name', $cuslist);
                                                        }
                                                        $query2 = $query2->get();
                                                        $sls2 = $query2;
                                                    @endphp
                                                    <table class="sortable">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>type</th>
                                                                <th>Sales</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($sls as $item2)
                                                                <tr
                                                                    ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item2->nepyear, $item2->nepmonth, getLastDate($item2->nepmonth, date('y', strtotime($item2->nepyear)))) }}', '{{ $item2->name }}')">
                                                                    <td>
                                                                        {{ $item2->name }}
                                                                    </td>
                                                                    <td
                                                                        class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                        {{ $item2->type }}</td>
                                                                    <td>{{ money($item2->sl - $item2->dis) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            @foreach ($sls2 as $item2)
                                                                <tr>
                                                                    <td>{{ $item2->name }}</td>
                                                                    <td
                                                                        class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                        {{ $item2->type }}</td>
                                                                    <td>0</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </span>
                                            </div>
                                        @else
                                            @php
                                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                                $nummonths = $nummonths + 1;
                                                $numbills =
                                                    $numbills +
                                                    DB::table('orders')
                                                        ->where('name', $name)
                                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->groupBy('orderid')
                                                        ->get()
                                                        ->count();
                                            @endphp
                                        @endif
                                    </li>
                                @endif
                            @elseif($item->nepyear == $eyear)
                                @if ($emonth >= $item->nepmonth)
                                    @php
                                        $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                    @endphp
                                    <li>
                                        <div class="collapsible-header row"
                                            @if ($name != null) ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}', '{{ $name }}')" @endif>
                                            <span
                                                class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                                class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                                        </div>
                                        @if ($name == null)
                                            @php
                                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                                $nummonths = $nummonths + 1;
                                                $numbills =
                                                    $numbills +
                                                    DB::table('orders')
                                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->groupBy('orderid')
                                                        ->get()
                                                        ->count();
                                            @endphp
                                            <div class="collapsible-body"><span>
                                                    @php
                                                        
                                                        $query = DB::table('orders');
                                                        if ($type == 'marketer') {
                                                            $cuslist = marketercuslist($refid);
                                                            $query = $query->WhereIn('orders.name', $cuslist);
                                                        }
                                                        $query = $query
                                                            ->where(['orders.deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                            ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                                                            ->orderBy('sl', 'DESC')
                                                            ->groupBy('name')
                                                            ->join('customers', 'orders.cusuni_id', '=', 'customers.cusuni_id')
                                                            ->get();
                                                        $sls = $query;
                                                        $cslist = $sls->pluck('name')->toArray();
                                                        
                                                        $query2 = DB::table('customers')->whereNotIn('name', $cslist);
                                                        if ($type == 'marketer') {
                                                            $cuslist = marketercuslist($refid);
                                                            $query2 = $query2->WhereIn('name', $cuslist);
                                                        }
                                                        $query2 = $query2->get();
                                                        $sls2 = $query2;
                                                    @endphp
                                                    <table class="sortable">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>type</th>
                                                                <th>Sales</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($sls as $item2)
                                                                <tr
                                                                    ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item2->nepyear, $item2->nepmonth, getLastDate($item2->nepmonth, date('y', strtotime($item2->nepyear)))) }}', '{{ $item2->name }}')">
                                                                    <td>
                                                                        {{ $item2->name }}
                                                                    </td>
                                                                    <td
                                                                        class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                        {{ $item2->type }}</td>
                                                                    <td>{{ money($item2->sl - $item2->dis) }}</td>
                                                                </tr>
                                                            @endforeach
                                                            @foreach ($sls2 as $item2)
                                                                <tr>
                                                                    <td>{{ $item2->name }}</td>
                                                                    <td
                                                                        class="black-text  @if ($item2->type == 'dealer') purple lighten-5 @elseif($item2->type == 'wholesaler') lime lighten-5 @elseif($item2->type == 'retailer') light-blue lighten-5 @else @endif">
                                                                        {{ $item2->type }}</td>
                                                                    <td>0</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </span>
                                            </div>
                                        @else
                                            @php
                                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                                $nummonths = $nummonths + 1;
                                                $numbills =
                                                    $numbills +
                                                    DB::table('orders')
                                                        ->where('name', $name)
                                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                                        ->groupBy('orderid')
                                                        ->get()
                                                        ->count();
                                            @endphp
                                        @endif
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="row" style="margin-top: 20px;">
                    @if ($numbills > 0)
                        <div class="col s12 m6">
                            <div class="mp-card">
                                <div>
                                    <h6 class="center">Average Sales Report</h6>
                                </div>
                                <div class="container">
                                    <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                                </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Particular</th>
                                            <th>Sales</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>Total Sales</td>
                                        <td>{{ money($totalsales) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Average Sales Per Bill </td>
                                        <td>{{ money($totalsales / $numbills) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Average Sales Per Month</td>
                                        <td>{{ money($totalsales / $nummonths) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Average Sales Per Day</td>
                                        <td>{{ money($totalsales / getNepaliDays($smonth, date('y', strtotime($syear)), $emonth, date('y', strtotime($eyear)), getNepaliDay(today()), getNepaliMonth(today()), date('y', strtotime(getNepaliYear(today()))))) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <div>
                            <h5 class="center">
                                No Sales Data for Given Date Range
                            </h5>
                        </div>
                    @endif
                    <div class="col s12 m6">
                        <div class="mp-card">
                            <div>
                                <h6 class="center">Quaterly Sales</h6>
                            </div>
                            <div class="container">
                                <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Quater</th>
                                        <th>Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $quatdata = [];
                                        
                                        foreach ($fquat as $item) {
                                            $quatdata[] = ['id' => $item->created_at, 'year' => $item->nepyear, 'quat' => 'First Quater -' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                        }
                                        foreach ($squat as $item) {
                                            $quatdata[] = ['id' => $item->created_at, 'year' => $item->nepyear, 'quat' => 'Second Quater -' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                        }
                                        foreach ($tquat as $item) {
                                            $quatdata[] = ['id' => $item->created_at, 'year' => $item->nepyear, 'quat' => 'Third Quater -' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                        }
                                        foreach ($frquat as $item) {
                                            $quatdata[] = ['id' => $item->created_at, 'year' => $item->nepyear, 'quat' => 'Fourth Quater -' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                                        }
                                        usort($quatdata, function ($a, $b) {
                                            return strtotime($a['id']) - strtotime($b['id']);
                                        });
                                        
                                        $qdata = collect($quatdata);
                                        $forqdata = [];
                                    @endphp
                                    @for ($i = 0; $i < count($qdata); $i++)
                                        @if ($qdata[$i]['year'] >= $syear && $qdata[$i]['year'] <= $eyear)
                                            @php
                                                $forqdata[] = ['quater' => $qdata[$i]['quat'], 'sales' => $qdata[$i]['amount']];
                                            @endphp
                                            <tr>
                                                <td>{{ $qdata[$i]['quat'] }}</td>
                                                <td>{{ money($qdata[$i]['amount']) }}</td>
                                            </tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               

                <div class="mp-card container" style="margin-top: 20px;">
                    <div class="bar" id="top_x_div" style="width: auto; height: 500px;"></div>
                </div>
                <div class="mp-card container" style="margin-top: 20px;">
                    <div class="bar" id="top_x_div2" style="width: auto; height: 500px;"></div>
                </div>
                <div class="mp-card" style="margin-top: 10px;">
                    <ul class="collapsible">
                        @foreach ($catsales as $item)
                            @php
                                $amtchart[] = ['Category' => $item->category, 'Amount' => $item->samt - $item->damt];
                                $quantchart[] = ['Category' => $item->category, 'Quantity' => $item->sum + 0];
                            @endphp
                            <li>
                                <div class="collapsible-header row">
                                    <div class="col s4 blue-text">{{ $item->category }}</div>
                                    <div class="col s4">{{ $item->sum }}</div>
                                    <div class="col s4">{{ money($item->samt - $item->damt) }}</div>
                                </div>
                                <div class="collapsible-body"><span>
                                        @php
                                            if ($item->category == 'powerbank') {
                                                $prod = $pdatapowerbank;
                                                $prod2 = $pdata2powerbank;
                                            }
                                            if ($item->category == 'charger') {
                                                $prod = $pdatacharger;
                                                $prod2 = $pdata2charger;
                                            }
                                            if ($item->category == 'cable') {
                                                $prod = $pdatacable;
                                                $prod2 = $pdata2cable;
                                            }
                                            if ($item->category == 'btitem') {
                                                $prod = $pdatabtitem;
                                                $prod2 = $pdata2btitem;
                                            }
                                            if ($item->category == 'earphone') {
                                                $prod = $pdataearphone;
                                                $prod2 = $pdata2earphone;
                                            }
                                            if ($item->category == 'others') {
                                                $prod = $pdataothers;
                                                $prod2 = $pdata2others;
                                            }
                                            
                                        @endphp
                                        <div>
                                            @php
                                                $subcates = DB::table('subcategory')
                                                    ->where('parent', $item->category)
                                                    ->pluck('subcategory')
                                                    ->toArray();
                                            @endphp
                                            <form id="{{ $item->category }}form">
                                                @foreach ($subcates as $item3)
                                                    <label style="margin-right: 15px;">
                                                        <input type="checkbox" name="{{ $item3 }}"
                                                            value="{{ $item3 }}"
                                                            onclick="Filter('{{ $item->category }}')" />
                                                        <span>{{ $item3 }}</span>
                                                    </label>
                                                @endforeach
                                                <label style="margin-right: 15px;">
                                                    <input type="checkbox" name="incall" value="incall"
                                                        onclick="Filter('{{ $item->category }}')" />
                                                    <span>Must Include All Selected Tags</span>
                                                </label>
                                            </form>
                                        </div>
                                        <table class="sortable">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($prod as $item2)
                                                    @php
                                                        $sbc = '';
                                                        $sc = '';
                                                        if ($item2->subcat != null) {
                                                            $sbc = explode('|', $item2->subcat);
                                                        }
                                                    @endphp
                                                    <tr class="{{ $item->category }} @if ($sbc != null) @foreach ($sbc as $sc){{ $sc }} @endforeach @endif"
                                                        ondblclick="openanadetail('{{ $date }}', '{{ $date2 }}','{{ $name }}', '{{ $item2->item }}')">
                                                        <td>{{ $item2->item }}</td>
                                                        <td>{{ $item2->sum }}</td>
                                                        <td>{{ money($item2->samt - $item2->damt) }}</td>
                                                    </tr>
                                                @endforeach
                                                @foreach ($prod2 as $item2)
                                                    @php
                                                        $sbc = '';
                                                        $sc = '';
                                                        if ($item2->subcat != null) {
                                                            $sbc = explode('|', $item2->subcat);
                                                        }
                                                    @endphp
                                                    <tr
                                                        class="{{ $item->category }} @if ($sbc != null) @foreach ($sbc as $sc){{ $sc }} @endforeach @endif">
                                                        <td>{{ $item2->name }}</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </span></div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <script>
                    function openanadetail(date, date2, name) {
                        window.open('/mainanalytics?date=' + date + '&date2=' + date2 + '&name=' + name,
                            "_self");
                    }
                    $(document).ready(function() {
                        $.ajax({
                            type: 'get',
                            url: '/findcustomer',
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
                <script type="text/javascript">
                    $(document).ready(function() {
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawStuff);
                        google.charts.setOnLoadCallback(drawStufftwo);
                    })


                    function drawStuff() {
                        var chartdata = @json($forchart);
                        const mta = chartdata.map(d => Array.from(Object.values(d)))
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', '');
                        data.addColumn('number', '');
                        data.addRows(mta);

                        var options = {
                            title: 'Monthly Sales',
                            backgroundColor: {
                                fill: 'transparent'
                            },
                            legend: {
                                position: 'none'
                            },
                            chart: {
                                title: 'Monthly Sales',
                                subtitle: 'By Amount'
                            },
                            bars: 'vertical',
                            axes: {
                                x: {
                                    0: {
                                        side: 'bottom',
                                        label: 'Months'
                                    } // Top x-axis.
                                },
                                y: {
                                    0: {
                                        side: 'left',
                                        label: 'Sales'
                                    } // left y axis
                                }
                            },
                            bar: {
                                groupWidth: "80%"
                            }
                        };

                        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
                        chart.draw(data, options);
                    };

                    function drawStufftwo() {
                        var chartdata = @json($forqdata);
                        const mta = chartdata.map(d => Array.from(Object.values(d)))
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', '');
                        data.addColumn('number', '');

                        data.addRows(mta);

                        var options = {
                            title: 'Quaterly Sales',
                            backgroundColor: {
                                fill: 'transparent'
                            },
                            legend: {
                                position: 'none'
                            },
                            chart: {
                                title: 'Quaterly Sales',
                                subtitle: 'By Amount'
                            },
                            bars: 'vertical',
                            axes: {
                                x: {
                                    0: {
                                        side: 'bottom',
                                        label: 'Quaters'
                                    } // Top x-axis.
                                },
                                y: {
                                    0: {
                                        side: 'left',
                                        label: 'Sales'
                                    } // left y axis
                                }
                            },
                            bar: {
                                groupWidth: "80%"
                            }
                        };

                        var chart = new google.charts.Bar(document.getElementById('top_x_div2'));
                        chart.draw(data, options);
                    };

                    function Filter(cat) {
                        $(`.${cat}`).hide();
                        var formData = $(`#${cat}form`).serializeArray()
                        if (formData.length == 0) {
                            $(`.${cat}`).show();
                        } else if (formData[formData.length - 1].name === 'incall') {
                            var clsnames = '';
                            for (let i = 0; i < formData.length - 1; i++) {
                                clsnames += "." + formData[i].name
                            }
                            $(`${clsnames}`).show();
                        } else {
                            if (formData.length > 0) {
                                for (let i = 0; i < formData.length; i++) {
                                    $(`.${formData[i].name}`).show();
                                }
                            } else {
                                $(`.${cat}`).show();
                            }
                        }

                    }
                </script>
            @endif
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '{!! URL::to('getref') !!}',
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
