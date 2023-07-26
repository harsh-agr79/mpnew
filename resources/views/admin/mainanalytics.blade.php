@php
    if($admin->type == 'marketer'){
        $type = 'marketer';
    }
    else{
        $type = 'admin';
   }
@endphp

@extends($type.'/layout')

@section('main')
    @php
        $quantchart = [];
        $amtchart = [];
    @endphp
    <div>
        <div class="mp-card" style="margin-top: 30px;">
            <form>
                <div class="row">
                    <div class="input-field col l4 m4 s6">
                        <label>From:</label>
                        <input type="date" name="date" value="{{ $date }}" class="inp browser-default black-text">
                    </div>
                    <div class="input-field col l4 m4 s6">
                        <label>To:</label>
                        <input type="date" name="date2" value="{{ $date2 }}"
                            class="inp browser-default black-text">
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <input type="text" name="name" id="customer" value="{{ $name }}"
                            placeholder="Customer" class="autocomplete browser-default inp black-text" autocomplete="off">
                    </div>
                    <div class="col s6 m2 l2">
                        <button class="btn amber darken-1">Apply</button>
                    </div>
                    <div class="col s6 m2 l2">
                        <a class="btn amber darken-1" href="{{ url('/mainanalytics') }}">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="amber center" style="padding: 5px; margin-top: 20px; border-radius: 10px;">
            <h5 class="black-text" style="font-weight: 600;">Total Sales:
                {{ money($totalsales[0]->samt - $totalsales[0]->damt) }}</h5>
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

        <div class="row mp-card" style="margin-top: 20px;">
            <div class="col m6 s12">
                <div class="mp-chart" id="piechart_3d" style="width: auto; height: 500px;"></div>
            </div>
            <div class="col m6 s12">
                <div class="mp-chart" id="piechart_3d2" style="width: auto; height: 500px;"></div>
            </div>
        </div>

    </div>
    <script>
        function openanadetail(date, date2, name, product) {
            window.open('/sortanalytics?date=' + date + '&date2=' + date2 + '&name=' + name + '&product=' + product,
                "_self");
        }

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

    <script>
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawChart2);

        function drawChart() {

            var chartdata = @json($amtchart);
            console.log(chartdata)
            const mta = chartdata.map(d => Array.from(Object.values(d)))
            var data = new google.visualization.DataTable();
            data.addColumn('string', '');
            data.addColumn('number', '');

            data.addRows(mta);

            var options = {
                title: 'Sales By Amount',
                is3D: true,
                backgroundColor: {
                    fill: 'transparent'
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }

        function drawChart2() {

            var chartdata = @json($quantchart);
            console.log(chartdata)
            const mta = chartdata.map(d => Array.from(Object.values(d)))
            var data = new google.visualization.DataTable();
            data.addColumn('string', '');
            data.addColumn('number', '');

            data.addRows(mta);

            var options = {
                title: 'Sales By Quantity',
                is3D: true,
                backgroundColor: {
                    fill: 'transparent'
                },
                textStyle: {
                    color: '#FFF'
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d2'));
            chart.draw(data, options);
        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '{!! URL::to('findcustomer') !!}',
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
