@extends('customer/layout')

@section('main')
        <div>
            <div class="center">
                <h5>Damage tickets</h5>
            </div>
            <div class="mp-card mp-container">
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Invoice ID</th>
                        <th>Details</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{$item->date}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->invoiceid}}</td>
                                <td><a href="{{url('user/ticket/'.$item->invoiceid)}}" class="btn-small amber">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection