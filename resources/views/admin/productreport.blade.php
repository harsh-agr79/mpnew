@extends('admin/layout')

@section('main')
    <div class="center">
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
                <div class="input-field col s6 m4 l4">
                    <input type="text" name="name" id="customer" value="{{ $name }}" placeholder="Customer"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class="input-field col s6 m4 l4">
                    <select name="category" class="browser-default selectinp black-text">
                        @if ($category != '')
                            <option value="{{ $category }}" selected>{{ $category }}</option>
                            <option value="">Select Category</option>
                        @else
                            <option value="" selected>Select Category</option>
                        @endif
                        <option value="powerbank">Powerbank</option>
                        <option value="charger">Charger</option>
                        <option value="cable">Cable</option>
                        <option value="earphone">earphone</option>
                        <option value="btitem">btitem</option>
                        <option value="others">others</option>
                    </select>
                </div>
                <div class=" input-field col s6 m4 l4">
                    <div class="switch">
                        <label>
                            Active Products
                            <input type="checkbox" onclick="togglehidden()">
                            <span class="lever"></span>
                            All Products
                        </label>
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="input-field col s3 l1">
                        <button class="btn amber darken-1">Apply</button>
                    </div>
                    <div class="input-field col s3 l1">
                        <a class="btn amber darken-1" href="{{ url('/productreport') }}">Clear</a>
                    </div>
                </div>


            </form>
        </div>
        @php
            $chartdata = [];
            $a = 0;
            $months = ['first', 'Baisakh', 'Jeth', 'Asar', 'Shrawan', 'Bhadra', 'Asoj', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        @endphp
        @if ($sort == 'normal')
            <div class="mp-card" style="margin-top: 10px; overflow-x: scroll;">
                <table class="sortable">
                    <thead>
                        <th>Date/Category</th>
                        <th>Powerbank</th>
                        <th>Charger</th>
                        <th>Cable</th>
                        <th>Earphone</th>
                        <th>Btitem</th>
                        <th>Others</th>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($data); $i++)
                            @php
                                $chartdata[] = [$data[$i]['month'] . '-' . $data[$i]['year'], intval($data[$i]['powerbank']), intval($data[$i]['charger']), intval($data[$i]['cable']), intval($data[$i]['earphone']), intval($data[$i]['btitem']), intval($data[$i]['others'])];
                            @endphp
                            <tr>
                                <td sorttable_customkey="{{ $i }}" style="font-weight: 600">
                                    {{ $months[$data[$i]['month']] }}-{{ $data[$i]['year'] }}</td>
                                <td>{{ $data[$i]['powerbank'] }}</td>
                                <td>{{ $data[$i]['charger'] }}</td>
                                <td>{{ $data[$i]['cable'] }}</td>
                                <td>{{ $data[$i]['earphone'] }}</td>
                                <td>{{ $data[$i]['btitem'] }}</td>
                                <td>{{ $data[$i]['others'] }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="mp-card" style="margin-top: 10px; padding: 20px;">
                <div id="linechart_material" style="width: auto; height: 600px;"></div>
            </div>
        @elseif($sort == 'category')
            <div class="mp-card" style="margin-top: 10px; overflow-x: scroll;">
                <table>
                    <thead>
                        <th>Date/Category</th>
                        @foreach ($data as $item)
                            <th
                                @if ($item->hide == 'on') style="display: none;"
                           class="hidden" @endif>
                                <label>
                                    <input type="checkbox" value="{{ $item->produni_id }}" pname="{{ $item->name }}"
                                        class="chartcb" onclick="getchartdata();" />
                                    <span></span>
                                </label>{{ $item->name }}</th>
                        @endforeach
                    </thead>
                    <tbody>
                        @foreach (json_decode($testdata) as $item)
                            <tr>
                                <td sorttable_customkey="{{ $a = $a + 1 }}" class="date">
                                    {{ $months[$item->month] }}-{{ $item->year }}</td>
                                @foreach ($item->prod as $item2)
                                    <td
                                        @if ($item2->hide == 'on') style="display: none;"
                                    class="hidden {{ $item2->uniid }}"
                                @else
                                class="{{ $item2->uniid }}" @endif>
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
        @endif





        <script>
            google.charts.load('current', {
                'packages': ['line']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var chartdata = @json($chartdata);
                console.log(chartdata);

                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Date');
                data.addColumn('number', 'Powerbank');
                data.addColumn('number', 'Charger');
                data.addColumn('number', 'Cable');
                data.addColumn('number', 'Earphone');
                data.addColumn('number', 'BTitem');
                data.addColumn('number', 'Others');

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
                            chartdata[i][j+1] = parseInt(tds[0].innerText);
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

    </div>
@endsection
