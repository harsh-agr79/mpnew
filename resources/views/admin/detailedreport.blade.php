@php
    if ($admin->type == 'marketer') {
        $type = 'marketer';
        $url = '/marketer';
    } else {
        $type = 'admin';
        $url = '';
    }
@endphp

@extends($type . '/layout')

@section('main')
    <style>
        label {
            font-size: 8px;
        }
    </style>
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
            <div class="input-field col s5 m4 l4">
                <input type="text" name="name" id="customer" value="{{ $name }}" placeholder="Customer"
                    class="autocomplete browser-default inp black-text" autocomplete="off">
            </div>
            <div class="input-field col s3 l1">
                <button class="btn amber darken-1">Apply</button>
            </div>
            <div class="input-field col s3 l1">
                <a class="btn amber darken-1" href="{{ url($url . '/detailedreport') }}">Clear</a>
            </div>
            @if ($name != null)
                <div class="col s12 l6 row" style="margin-top: 20px;">
                    <div class="col s6 center">
                        <a href="{{ url($url . '/balancesheet/' . $name) }}" class="btn amber darken-2">Statement</a>
                    </div>
                    <div class="col s6 center">
                        @php
                            if (getNepaliMonth(today()) == $emonth && getNepaliYear(today()) == $eyear) {
                                $eday = getNepaliDay(today());
                            } else {
                                $eday = getLastDate($emonth, date('y', strtotime($eyear)));
                            }
                            
                        @endphp
                        <a href="{{ url($url . '/mainanalytics?date=' . getEnglishDate($syear, $smonth, 1) . '&date2=' . getEnglishDate($eyear, $emonth, $eday) . '&name=' . $name) }}"
                            class="btn amber darken-2">Product Analytics</a>
                    </div>
                </div>
            @endif
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
            @foreach ($data as $item)
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
                                            $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                            $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                                $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                                $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                                $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                                $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                                $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
                                                $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
            @if ($custs != 'no data')
            <li>
                <div class="collapsible-header row">
                    <span class="left col s6 blue-text">Total</span><span
                        class="right col s6">{{ money($tss[0]->sum - $tss[0]->dis) }}</span>
                </div>
                <div class="collapsible-body"><span>
                        <table class="sortable">
                            <thead>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Sales</th>
                            </thead>
                            <tbody>
                                @foreach ($custs as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td
                                            class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                                            {{ $item->type }}</td>
                                        <td>{{ money($item->sum - $item->dis) }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($cusnts as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td
                                        class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                                        {{ $item->type }}</td>
                                        <td>0</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </span></div>
            </li>
            @endif
          
        </ul>
    </div>
    <div class="row" style="margin-top: 20px;">
        @if ($name != null)
            <div class="col s12 m6">
                <div class="hide">
                    @php
                        $payment = DB::table('payments')
                            ->where('deleted', null)
                            ->where('name', $name)
                            ->selectRaw('*, SUM(amount) as sum')
                            ->get();
                        $order = DB::table('orders')
                            ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'name' => $name])
                            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount * 0.01 * approvedquantity * price) as dis')
                            ->where('status', 'approved')
                            ->get();
                        $slr = DB::table('salesreturns')
                            ->where('name', $name)
                            ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount * 0.01 * quantity * price) as dis')
                            ->get();
                        $exp = DB::table('expenses')
                            ->where('name', $name)
                            ->selectRaw('*, SUM(amount) as sum')
                            ->get();
                        $cus = DB::table('customers')
                            ->where('name', $name)
                            ->first();
                    @endphp
                    <span>{{ $py = $payment[0]->sum }}</span>
                    <span>{{ $ex = $exp[0]->sum }}</span>
                    <span>{{ $sr = $slr[0]->sum - $slr[0]->dis }}</span>
                    <span>{{ $or = $order[0]->sum - $order[0]->dis }}</span>
                    @if ($cus->obtype == 'debit')
                        <span>{{ $od = $cus->openbalance }}
                            {{ $oc = 0 }}</span>
                    @elseif($cus->obtype == 'credit')
                        <span>{{ $od = 0 }}
                            {{ $oc = $cus->openbalance }}</span>
                    @elseif($cus->obtype == null)
                        <span>{{ $od = 0 }}
                            {{ $oc = 0 }}</span>
                    @else
                        <span>{{ $od = 0 }}
                            {{ $oc = 0 }}</span>
                    @endif
                    {{ $tbd = $or + $od + $ex }}
                    {{ $tbc = $py + $sr + $oc }}
                    @if ($or + $od + $ex > $py + $sr + $oc)
                        <span>{{ $bal = $or + $od + $ex - ($py + $sr + $oc) }}
                        @elseif($or + $od + $ex < $py + $sr + $oc)
                            <span>{{ $bal = $py + $sr + $oc - ($or + $od + $ex) }}
                            @else
                                <span>{{ $bal = 0 }}
                                    {{ $col = 'green lighten-5' }}</span>
                    @endif
                </div>

                <div class="mp-card">
                    <table>
                        <thead>
                            <th>Outstanding Amount in Days</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: 700;">Total Balance @if ($tbd > $tbc)
                                        | To Recieve |
                                    @else
                                        | To Give |
                                    @endif
                                </td>
                                <td style="font-weight: 700;">{{ money($bal) }}</td>
                            </tr>
                            @if (count($thirdays) > 0)
                                <tr>
                                    <td class="hide">99999999999999999999999999999999999999999999</td>
                                    <td style="font-weight: 700;">Above 30 days</td>
                                    @if ($tbd > $tbc)
                                        @if ($tbd - $tbc - ($thirdays[0]->sum - $thirdays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbd - $tbc - ($thirdays[0]->sum - $thirdays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @elseif($tbd < $tbc)
                                        @if ($tbc - $tbd - ($thirdays[0]->sum - $thirdays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbc - $tbd - ($thirdays[0]->sum - $thirdays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @endif
                                </tr>
                            @else
                                <tr>
                                    <td style="font-weight: 700;">Above 30 days</td>

                                    @if ($tbc - $tbd > 0)
                                        <td style="font-weight: 700;">{{ money($tbc - $tbd) }}</td>
                                    @else
                                        <td style="font-weight: 700;">{{ money($tbd - $tbc) }}</td>
                                    @endif
                                </tr>
                            @endif

                            @if (count($fourdays) > 0)
                                <tr>
                                    <td class="hide">99999999999999999999999999999999999999999999</td>
                                    <td style="font-weight: 700;">Above 45 days</td>
                                    @if ($tbd > $tbc)
                                        @if ($tbd - $tbc - ($fourdays[0]->sum - $fourdays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbd - $tbc - ($fourdays[0]->sum - $fourdays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @elseif($tbd < $tbc)
                                        @if ($tbc - $tbd - ($fourdays[0]->sum - $fourdays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbc - $tbd - ($fourdays[0]->sum - $fourdays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @endif
                                </tr>
                            @else
                                <tr>
                                    <td style="font-weight: 700;">Above 45 days</td>

                                    @if ($tbc - $tbd > 0)
                                        <td style="font-weight: 700;">{{ money($tbc - $tbd) }}</td>
                                    @else
                                        <td style="font-weight: 700;">{{ money($tbd - $tbc) }}</td>
                                    @endif
                                </tr>
                            @endif

                            @if (count($sixdays) > 0)
                                <tr>
                                    <td class="hide">99999999999999999999999999999999999999999999</td>
                                    <td style="font-weight: 700;">Above 60 days</td>
                                    @if ($tbd > $tbc)
                                        @if ($tbd - $tbc - ($sixdays[0]->sum - $sixdays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbd - $tbc - ($sixdays[0]->sum - $sixdays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @elseif($tbd < $tbc)
                                        @if ($tbc - $tbd - ($sixdays[0]->sum - $sixdays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbc - $tbd - ($sixdays[0]->sum - $sixdays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @endif
                                </tr>
                            @else
                                <tr>
                                    <td style="font-weight: 700;">Above 60 days</td>

                                    @if ($tbc - $tbd > 0)
                                        <td style="font-weight: 700;">{{ money($tbc - $tbd) }}</td>
                                    @else
                                        <td style="font-weight: 700;">{{ money($tbd - $tbc) }}</td>
                                    @endif
                                </tr>
                            @endif
                            @if (count($nindays) > 0)
                                <tr>
                                    <td class="hide">99999999999999999999999999999999999999999999</td>
                                    <td style="font-weight: 700;">Above 90 days</td>
                                    @if ($tbd > $tbc)
                                        @if ($tbd - $tbc - ($nindays[0]->sum - $nindays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbd - $tbc - ($nindays[0]->sum - $nindays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @elseif($tbd < $tbc)
                                        @if ($tbc - $tbd - ($nindays[0]->sum - $nindays[0]->dis) > 0)
                                            <td style="font-weight: 700;">
                                                {{ money($tbc - $tbd - ($nindays[0]->sum - $nindays[0]->dis)) }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    @endif
                                </tr>
                            @else
                                <tr>
                                    <td style="font-weight: 700;">Above 90 days</td>

                                    @if ($tbc - $tbd > 0)
                                        <td style="font-weight: 700;">{{ money($tbc - $tbd) }}</td>
                                    @else
                                        <td style="font-weight: 700;">{{ money($tbd - $tbc) }}</td>
                                    @endif
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
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
    <script>
        function openanadetail(date, date2, name) {
            window.open(`{{ $url }}` + '/mainanalytics?date=' + date + '&date2=' + date2 + '&name=' + name,
                "_self");
        }
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: `{{ $url }}` + '/findcustomer',
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
    </script>
@endsection
