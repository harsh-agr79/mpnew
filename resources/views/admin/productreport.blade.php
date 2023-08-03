@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 10px;">
            <table>
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
                            <th>{{$data[$i]['date']}}</th>
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
    </div>
@endsection