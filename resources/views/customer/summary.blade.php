@extends('customer/layout')

@section('main')
<div class="mp-container">


    @php
        $bal = explode('|', $user->balance);
    @endphp
    <style>
        label {
            font-size: 8px;
        }
    </style>
    <div class="center">
        <h5>Detailed Summary</h5>
    </div>
    <div class="amber center" style="padding: 5px; margin-top: 10px; border-radius: 10px;">
        <h5 class="black-text" style="font-weight: 600;">Balance -@if ($bal[0] == 'red')
                To Pay:
            @else
                To Recieve:
            @endif
            {{ money($bal[1]) }}</h5>
    </div>
    <div class="mp-card" style="margin-top: 10px;">
        <table>
            <thead>
                <th>Outstanding Amount in Days</th>
            </thead>
            <tbody>
                <tr>

                    <td>Balance -@if ($bal[0] == 'red')
                            To Pay:
                        @else
                            To Recieve:
                        @endif
                    </td>

                    <td>{{ money($bal[1]) }}</td>
                </tr>
                <tr>
                    <td>Above 30 Days</td>
                    <td>{{ money($user->thirdays) }}</td>
                </tr>
                <tr>
                    <td>Above 45 Days</td>
                    <td>{{ money($user->fourdays) }}</td>
                </tr>
                <tr>
                    <td>Above 60 Days</td>
                    <td>{{ money($user->sixdays) }}</td>
                </tr>
                <tr>
                    <td>Above 90 Days</td>
                    <td>{{ money($user->nindays) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <div class="mp-card row" style="margin-top: 10px;">
            <div class="col s12">
                <a href="{{ url('user/statement') }}" class="home-btn">Click Here To See Full Statement</a>
            </div>
            <div class="col s12" style="margin-top: 5px;">
                <a class="home-btn modal-trigger" href="#modal1">Click Here To See Target Details</a>
            </div>
        </div>

    </div>
    <div class="mp-card" style="margin-top: 10px;">
        <form class="row" style="padding: 0; margin: 0;">
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
            <div class="input-field col l1">
                <button class="btn amber darken-1">Apply</button>
            </div>
            <div class="input-field col l1">
                <a class="btn amber darken-1" href="{{ url('user/summary') }}">Clear</a>
            </div>
        </form>
    </div>

    <div class="mp-card" style="margin-top: 10px;">
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
                            onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                            <span
                                class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                        </div>

                        @php
                            $totalsales = $totalsales + ($item->sl - $item->dis);
                            $nummonths = $nummonths + 1;
                            $numbills =
                                $numbills +
                                DB::table('orders')
                                    ->where('name', $user->name)
                                    ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                    ->groupBy('orderid')
                                    ->get()
                                    ->count();
                        @endphp
                    </li>
                @elseif($syear == $eyear)
                    @if ($smonth <= $item->nepmonth && $emonth >= $item->nepmonth && $item->nepyear == $syear)
                        @php
                            $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                        @endphp
                        <li>
                            <div class="collapsible-header row"
                                onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                <span
                                    class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                    class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                            </div>

                            @php
                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                $nummonths = $nummonths + 1;
                                $numbills =
                                    $numbills +
                                    DB::table('orders')
                                        ->where('name', $user->name)
                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                        ->groupBy('orderid')
                                        ->get()
                                        ->count();
                            @endphp
                        </li>
                    @endif
                @elseif($item->nepyear == $syear)
                    @if ($smonth <= $item->nepmonth)
                        @php
                            $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                        @endphp
                        <li>
                            <div class="collapsible-header row"
                                onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                <span
                                    class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                    class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                            </div>

                            @php
                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                $nummonths = $nummonths + 1;
                                $numbills =
                                    $numbills +
                                    DB::table('orders')
                                        ->where('name', $user->name)
                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                        ->groupBy('orderid')
                                        ->get()
                                        ->count();
                            @endphp
                        </li>
                    @endif
                @elseif($item->nepyear == $eyear)
                    @if ($emonth >= $item->nepmonth)
                        @php
                            $forchart[] = ['date' => $item->nepmonth . '-' . $item->nepyear, 'amount' => $item->sl - $item->dis];
                        @endphp
                        <li>
                            <div class="collapsible-header row"
                                onclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
                                <span
                                    class="left col s6 blue-text">{{ $months[$item->nepmonth] }}-{{ $item->nepyear }}</span><span
                                    class="right col s6">{{ money($item->sl - $item->dis) }}</span>
                            </div>

                            @php
                                $totalsales = $totalsales + ($item->sl - $item->dis);
                                $nummonths = $nummonths + 1;
                                $numbills =
                                    $numbills +
                                    DB::table('orders')
                                        ->where('name', $user->name)
                                        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'nepmonth' => $item->nepmonth, 'nepyear' => $item->nepyear])
                                        ->groupBy('orderid')
                                        ->get()
                                        ->count();
                            @endphp
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
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
                            {{-- @php
                                if ($item->category == 'powerbank') {
                                    $prod = $datapowerbank;
                                    $prod2 = $data2powerbank;
                                }
                                if ($item->category == 'charger') {
                                    $prod = $datacharger;
                                    $prod2 = $data2charger;
                                }
                                if ($item->category == 'cable') {
                                    $prod = $datacable;
                                    $prod2 = $data2cable;
                                }
                                if ($item->category == 'btitem') {
                                    $prod = $databtitem;
                                    $prod2 = $data2btitem;
                                }
                                if ($item->category == 'earphone') {
                                    $prod = $dataearphone;
                                    $prod2 = $data2earphone;
                                }
                                if ($item->category == 'others') {
                                    $prod = $dataothers;
                                    $prod2 = $data2others;
                                }
                                
                            @endphp --}}
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
                                                value="{{ $item3 }}" onclick="Filter('{{ $item->category }}')" />
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
                                    @foreach ($catdata[$item->category] as $item2)
                                        @php
                                            $sbc = '';
                                            $sc = '';
                                            if ($item2->subcat != null) {
                                                $sbc = explode('|', $item2->subcat);
                                            }
                                        @endphp
                                        <tr class="{{ $item->category }} @if ($sbc != null) @foreach ($sbc as $sc){{ $sc }} @endforeach @endif"
                                            onclick="openanadetail('{{ $date }}', '{{ $date2 }}', '{{ $item2->item }}')">
                                            <td>{{ $item2->item }}</td>
                                            <td>{{ $item2->sum }}</td>
                                            <td>{{ money($item2->samt - $item2->damt) }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach ($catdata2[$item->category] as $item2)
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

    @if ($numbills > 0)
        <div style="margin-top: 10px;">
            <div class="mp-card">
                <div>
                    <h6 class="center">Average Purchase Report</h6>
                </div>
                <div class="container">
                    <hr style="background-color: rgb(211, 211, 211); border: none; height: 1px;">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Particular</th>
                            <th>Purchase</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>Total Purchase</td>
                        <td>{{ money($totalsales) }}</td>
                    </tr>
                    <tr>
                        <td>Average Purchase Per Bill </td>
                        <td>{{ money($totalsales / $numbills) }}</td>
                    </tr>
                    <tr>
                        <td>Average Purchase Per Month</td>
                        <td>{{ money($totalsales / $nummonths) }}</td>
                    </tr>
                    <tr>
                        <td>Average Purchase Per Day</td>
                        <td>{{ money($totalsales / getNepaliDays($smonth, date('y', strtotime($syear)), $emonth, date('y', strtotime($eyear)), getNepaliDay(today()), getNepaliMonth(today()), date('y', strtotime(getNepaliYear(today()))))) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Bill Count</td>
                        <td>{{ $numbills }}</td>
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
    <div style="margin-top: 10px;">
        <div class="mp-card">
            <div>
                <h6 class="center">Quaterly Purchase</h6>
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

    <div class="mp-card container" style="margin-top: 10px;">
        <div class="bar" id="top_x_div" style="width: auto; height: 500px;"></div>
    </div>
    <div class="mp-card container" style="margin-top: 10px;">
        <div class="bar" id="top_x_div2" style="width: auto; height: 500px;"></div>
    </div>

    <div id="modal1" class="modal black-text">
        <div class="modal-content">
            @php
                $today = date('Y-m-d');
                $target = DB::table('target')
                    ->where('customerid', $user->id)
                    ->where('startdate', '<=', $today)
                    ->where('enddate', '>=', $today)
                    ->get();
                
            @endphp
            @if (count($target) > 0)
                @php
                    $ach = DB::table('orders')
                        ->where(['deleted' => null])
                        ->where('name', $user->name)
                        ->where('created_at', '>=', $target[0]->startdate)
                        ->where('created_at', '<=', $target[0]->enddate)
                        ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                        ->groupBy('name')
                        ->where('status', 'approved')
                        ->get();
                    if (!$ach->isEmpty()) {
                        $gross = ($ach['0']->sum * 251.2) / $target[0]->gross;
                        $net = (($ach['0']->sum - $ach['0']->dis) * 251.2) / $target[0]->net;
                        $net2 = (($ach['0']->sum - $ach['0']->dis) * 100) / $target[0]->net;
                        $net3 = $ach['0']->sum - $ach['0']->dis;
                    } else {
                        $gross = 0;
                        $net = 0;
                        $net2 = 0;
                        $net3 = 0;
                    }
                @endphp
                <h4 class="">Target Details</h4>
                <h6 class="left-align">Starting Date: {{ $target[0]->startdate }} <br> Ending Date:
                    {{ $target[0]->enddate }}</h6>
                <div class="row" style="margin-top: 50px;">
                    <div class="col s12 m6 left-align">
                        <span style="font-size: 20px; font-weight:600;">Gross Target:
                            {{ money($target[0]->gross) }}</span><br>
                        <span style="font-size: 20px; font-weight:600;">Net Target:
                            {{ money($target[0]->net) }}</span><br>
                        <span style="font-size: 20px; font-weight:600;">Achieved : <span class="red-text">
                                {{ money($net3) }}</span></span>
                    </div>
                    <div class="col s12 m6 ">
                        <svg viewbox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="#FDB900" />
                            <path fill="none" stroke-linecap="round" stroke-width="5" stroke="#fff"
                                stroke-dasharray="{{ $net }},{{ 251.2 - $net }}"
                                d="M50 10
                     a 40 40 0 0 1 0 80
                     a 40 40 0 0 1 0 -80" />
                            <text x="50" y="50" text-anchor="middle" dy="7"
                                font-size="20">{{ round($net2) }}%</text>
                        </svg>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <script>
        function openanadetail(date, date2, name) {
            window.open('/user/analytics?date=' + date + '&date2=' + date2 + '&name=' + name,
                "_self");
        }
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

    <script>
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
</div>
@endsection
