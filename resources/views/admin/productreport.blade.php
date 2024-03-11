@extends('admin/layout')

@section('main')
    <style>
        .cbth {
            margin: 0 !important;
            padding: 0 !important;
        }
        label {
            font-size: 8px;
        }
        [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
position: static!important;
left: 0px!important;
opacity: 1!important;
visibility: visible!important;
pointer-events: all!important;
}
    </style>
    <div class="center">
        <div class="mp-card" style="margin-top: 30px;">
            <form class="row">
                <div class="col s2" style="padding: 3px; margin: 0;">
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
                <div class="col s4" style="padding: 3px; margin: 0;">
                    <label>Start Year:</label>
                    <select name="startyear" class="browser-default selectinp">
                        <option value="">Select Start Year</option>
                        <option value="{{ $syear }}" selected>{{ $syear }}</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                    </select>
                </div>
                <div class="col s2" style="padding: 3px; margin: 0;">
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
                <div class="col s4" style="padding: 3px; margin: 0;">
                    <label>End Year:</label>
                    <select name="endyear" value="{{ $eyear }}" class="browser-default selectinp">
                        <option value="">Select End Year</option>
                        <option value="{{ $eyear }}" selected>{{ $eyear }}</option>
                        <option value="2078">2078</option>
                        <option value="2079">2079</option>
                        <option value="2080">2080</option>
                    </select>
                </div>
                <div class="input-field col s6 m4 l4" style="padding: 3px; margin: 0;">
                    <input type="text" name="name" id="customer" value="{{ $name }}" placeholder="Customer"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class="input-field col s6 m4 l4" style="padding: 3px; margin: 0;">
                    <select name="category" class="browser-default selectinp black-text">
                        @if ($category != '')
                            <option value="{{ $category }}" selected>{{ $category }}</option>
                            <option value="">Select Category</option>
                        @else
                            <option value="" selected>Select Category</option>
                        @endif
                        @foreach($categories as $cats)
                            <option value="{{$cats->category}}">{{$cats->category}}</option>
                        @endforeach
                    </select>
                </div>
                <div class=" input-field col s12 m4 l4" style="padding: 3px; margin: 0;">
                    <div class="switch">
                        <label style="font-size: 15px; !important">
                            Active Products
                            <input type="checkbox" onclick="togglehidden()">
                            <span class="lever"></span>
                            All Products
                        </label>
                    </div>
                </div>
                <div class="col s12 row" style="padding: 3px; margin: 0;">
                    <div class="input-field col s6 l1">
                        <button class="btn amber darken-1">Apply</button>
                    </div>
                    <div class="input-field col s6 l1">
                        <a class="btn amber darken-1" href="{{ url('/productreport') }}">Clear</a>
                    </div>
                </div>


            </form>
        </div>
        @php
            $chartdata = [];
            $avg = [];
            $a = 0;
            $months = ['first', 'Baisakh', 'Jeth', 'Asar', 'Shrawan', 'Bhadra', 'Asoj', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        @endphp
        @if ($sort == 'normal')
            <div class="mp-card" style="margin-top: 10px; overflow-x: scroll;">
                <table class="sortable">
                    <thead>
                        <th>Date/Category</th>
                        @foreach ($categories as $cats)
                        @php
                            $avg[$cats->category] = 0;
                        @endphp
                            <th>{{$cats->category}}</th>
                        @endforeach
                        {{-- <th>Powerbank</th>
                        <th>Charger</th>
                        <th>Cable</th>
                        <th>Earphone</th>
                        <th>Btitem</th>
                        <th>Others</th> --}}
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($data); $i++)
                            @php
                                $dat = [];
                                $dat[0] = $data[$i]['month'] . '-' . $data[$i]['year'];
                                foreach ($categories as $cat) {
                                    $dat[] = intval($data[$i][$cat->category]);
                                    $avg[$cat->category] = $avg[$cat->category] + intval($data[$i][$cat->category]);
                                }
                                $chartdata[] = $dat; 
                            @endphp
                            <tr>
                                <td sorttable_customkey="{{ $i }}" style="font-weight: 600">
                                    {{ $months[$data[$i]['month']] }}-{{ $data[$i]['year'] }}</td>
                                @foreach ($categories as $cats)
                                    <td>{{$data[$i][$cats->category]}}</td>
                                @endforeach
                                {{-- <td>{{ $data[$i]['powerbank'] }}</td>
                                <td>{{ $data[$i]['charger'] }}</td>
                                <td>{{ $data[$i]['cable'] }}</td>
                                <td>{{ $data[$i]['earphone'] }}</td>
                                <td>{{ $data[$i]['btitem'] }}</td>
                                <td>{{ $data[$i]['others'] }}</td> --}}
                            </tr>
                        @endfor
                        <tr>
                            <td style="font-weight: 600;">Averge</td>
                            @foreach ($categories as $cats)
                                <td>{{intval($avg[$cats->category]/count($data))}}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mp-card" style="margin-top: 10px; padding: 20px;">
                <div id="linechart_material" style="width: auto; height: 600px;"></div>
            </div>
            <script>
                google.charts.load('current', {
                    'packages': ['line']
                });
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var chartdata = @json($chartdata);
                    console.log(chartdata);
                    var cats = @json($categories);
                    console.log(cats);

                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Date');
                    for (let i = 0; i < cats.length; i++) {
                        data.addColumn('number', cats[i]['category'])
                    }
                    // data.addColumn('number', 'Powerbank');
                    // data.addColumn('number', 'Charger');
                    // data.addColumn('number', 'Cable');
                    // data.addColumn('number', 'Earphone');
                    // data.addColumn('number', 'BTitem');
                    // data.addColumn('number', 'Others');

                    data.addRows(chartdata);

                    var options = {
                        chart: {
                            title: 'Monthly Break Down Of Sales of Each Product Category',
                            subtitle: 'in Number of Items Sold'
                        },
                        height: 600,
                        backgroundColor: {
                            fill: 'transparent'
                        }
                    };

                    var chart = new google.charts.Line(document.getElementById('linechart_material'));

                    chart.draw(data, google.charts.Line.convertOptions(options));
                }
            </script>
        @elseif($sort == 'category')
            <div class="mp-card" style="margin-top: 10px; overflow-x: scroll;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach ($data as $item)
                                <th
                                    @if ($item->hide == 'on') style="display: none;"
                           class="hidden" @endif>
                                        <input type="checkbox" value="{{ $item->produni_id }}"
                                            pname="{{ $item->name }}" class="chartcb cbth" onclick="getchartdata();" />
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Date/Category</th>
                            @foreach ($data as $item)
                                <th @if ($item->hide == 'on') style="display: none;" class="hidden" @endif>
                                    {{ $item->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (json_decode($testdata) as $item)
                            <tr>
                                <td sorttable_customkey="{{ $a = $a + 1 }}" class="date">
                                    {{ $months[$item->month] }}-{{ $item->year }}</td>
                                @foreach ($item->prod as $item2)
                                    <td
                                        @if ($item2->hide == 'on') style="display: none;" class="hidden {{ $item2->uniid }}" @else class="{{ $item2->uniid }}" @endif>
                                        {{ $item2->quant }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mp-card" style="margin-top: 10px; padding: 20px;">
                <div id="linechart_material" style="width: auto; height: 600px;"></div>
            </div>
            <script>
                function getchartdata() {
                    google.charts.load('current', {
                        'packages': ['line']
                    });
                    google.charts.setOnLoadCallback(drawChart);



                    function drawChart() {
                        var trs = $('tbody tr');
                        var cb = $('.chartcb:checked');
                        var chartdata = [];
                        var chartcol = [];
                        for (let i = 0; i < cb.length; i++) {
                            chartcol[i] = cb[i].getAttribute('pname');
                        }
                        for (let i = 0; i < trs.length; i++) {
                            chartdata[i] = [];
                            var date = trs[i].getElementsByClassName('date');
                            chartdata[i][0] = date[0].innerText;
                            for (let j = 0; j < cb.length; j++) {
                                var tds = trs[i].getElementsByClassName(cb[j].value);
                                chartdata[i][j + 1] = parseInt(tds[0].innerText);
                            }
                        }
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'date');
                        for (let i = 0; i < chartcol.length; i++) {
                            data.addColumn('number', chartcol[i]);
                        }
                        data.addRows(chartdata);

                        var options = {
                            chart: {
                                title: 'Monthly Break Down Of Sales of Each Product',
                                subtitle: 'in Number of Items Sold'
                            },
                            height: 600,
                            backgroundColor: {
                                fill: 'transparent'
                            }
                        };

                        var chart = new google.charts.Line(document.getElementById('linechart_material'));

                        chart.draw(data, google.charts.Line.convertOptions(options));
                    }
                }
            </script>
        @endif
        <script>
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

            function togglehidden() {
                $('.hidden').toggle();
            }
        </script>
    </div>
@endsection
