@extends('customer/layout')

@section('main')
    <div class="mp-container">
        <div class="center mp-card" style="font-size: 15px; margin-top: 10px;">
            <div>Name: {{ $data[0]->name }}</div>
            <div>Date: {{ $data[0]->date }}</div>
            <div>invoiceid: {{ $data[0]->invoiceid }}</div>
        </div>
        <div class="mp-card" style="margin-top: 10px;">
            @foreach ($data as $item)
                <div class="center row">
                    <div class="col s4">Item: {{ $item->item }}</div>
                    <div class="col s4">Quantity: {{ $item->quantity }}</div>
                    <div class="col s4">Your Remarks: {{ $item->cusremarks }}</div>
                </div>
                @php
                    
                    $prod = DB::table('damage')
                        ->where('invoiceid', $item->invoiceid)
                        ->where('item', $item->item)
                        ->get();
                    
                @endphp
                <table>
                    <thead>
                        <th>Group Quantity</th>
                        <th>Condition</th>
                        <th>Warranty Status</th>
                        <th>Problem</th>
                        <th>Solution</th>
                        <th>Remarks</th>
                    </thead>
                    <tbody>
                        @foreach ($prod as $item2)
                        <tr>
                            <td>{{$item2->grpqty}}</td>
                            <td>{{$item2->condition}}</td>
                            <td>{{$item2->warranty}}</td>
                            <td>{{$item2->problem}}</td>
                            <td>{{$item2->solution}}</td>
                            <td>{{$item2->adremarks}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              
            @endforeach
        </div>
    </div>
@endsection
