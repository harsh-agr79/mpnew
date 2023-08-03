@extends('admin/layout')

@section('main')
    <div>
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
                <div class="input-field col s3 l1">
                    <button class="btn amber darken-1">Apply</button>
                </div>
                <div class="input-field col s3 l1">
                    <a class="btn amber darken-1" href="{{ url('/productreport') }}">Clear</a>
                </div>
            </form>
        </div>
        @php
              $months = ['first', 'Baisakh', 'Jeth', 'Asar', 'Shrawan', 'Bhadra', 'Asoj', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];
        @endphp
        <div class="mp-card" style="margin-top: 10px;">
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
                        <tr>
                            <td sorttable_customkey="{{$i}}" style="font-weight: 600">{{$months[$data[$i]['month']]}}-{{$data[$i]['year']}}</td>
                            <td>{{$data[$i]['powerbank']}}</td>
                            <td>{{$data[$i]['charger']}}</td>
                            <td>{{$data[$i]['cable']}}</td>
                            <td>{{$data[$i]['earphone']}}</td>
                            <td>{{$data[$i]['btitem']}}</td>
                            <td>{{$data[$i]['others']}}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div id="linechart_material" style="width: 900px; height: 500px"></div>
        <script>
                  google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Day');
      data.addColumn('number', 'Guardians of the Galaxy');
      data.addColumn('number', 'The Avengers');
      data.addColumn('number', 'Transformers: Age of Extinction');

      data.addRows([
        [1,  37.8, 80.8, 41.8],
        [2,  30.9, 69.5, 32.4],
        [3,  25.4,   57, 25.7],
        [4,  11.7, 18.8, 10.5],
        [5,  11.9, 17.6, 10.4],
        [6,   8.8, 13.6,  7.7],
        [7,   7.6, 12.3,  9.6],
        [8,  12.3, 29.2, 10.6],
        [9,  16.9, 42.9, 14.8],
        [10, 12.8, 30.9, 11.6],
        [11,  5.3,  7.9,  4.7],
        [12,  6.6,  8.4,  5.2],
        [13,  4.8,  6.3,  3.6],
        [14,  4.2,  6.2,  3.4]
      ]);

      var options = {
        chart: {
          title: 'Box Office Earnings in First Two Weeks of Opening',
          subtitle: 'in millions of dollars (USD)'
        },
        width: 900,
        height: 500
      };

      var chart = new google.charts.Line(document.getElementById('linechart_material'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
        </script>
    </div>
@endsection