@extends('customer/layout')

@section('main')
@php
$bal = explode('|', $user->balance);
@endphp
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
            <a href="{{url('user/statement')}}" class="home-btn">Click Here To See Full Statement</a>
        </div>
        <div class="col s12">
            <a class="home-btn">Click Here To See Target Details</a>
        </div>
    </div>
       
    </div>
    <div class="mp-card" style="margin-top: 10px;">
        <form class="row">
            <div class="row col s12 m6">
                <div class="col s12">
                    <span>Start Date</span>
                </div>
                <div class="col row s12">
                    <select name="startmonth" class="browser-default col s3 m3 selectinp">
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
                    <select name="startyear" class="browser-default col s9 m9 selectinp">
                        <option value="">Select Start Year</option>
                        <option value="{{ $syear }}" selected>{{ $syear }}</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                    </select>
                </div>
            </div>
            <div class="row col s12 m6">
                <div class="col s12">
                    <span>End Date</span>
                </div>
                <div class="row col s12">
                    <select name="endmonth" value="{{ $emonth }}" class="browser-default col s3 m3 selectinp">
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
                    <select name="endyear" value="{{ $eyear }}" class="browser-default col s9 m9 selectinp">
                        <option value="">Select End Year</option>
                        <option value="{{ $eyear }}" selected>{{ $eyear }}</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                    </select>
                </div>
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
                            ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
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
                                ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
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
                                ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
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
                                ondblclick="openanadetail('{{ getEnglishDate($item->nepyear, $item->nepmonth, 1) }}', '{{ getEnglishDate($item->nepyear, $item->nepmonth, getLastDate($item->nepmonth, date('y', strtotime($item->nepyear)))) }}')">
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
@endsection
