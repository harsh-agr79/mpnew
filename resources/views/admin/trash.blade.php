@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <h5 class="center">Customers</h5>
            <div>
                <table>
                    <thead>
                        <th>Name</th>
                        <th>Restore</th>
                        <th>Permanent Delete</th>
                    </thead>
                    <tbody>
                        @foreach ($customer as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td><a href="{{url('restorecus/'.$item->id)}}" class="btn-small amber "><i class="material-icons">autorenew</i></a></td>
                                <td><a href="{{url('permdeletecus/'.$item->id)}}" class="btn-small red "><i class="material-icons">delete</i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <h5 class="center">Products</h5>
            <div>
                <table>
                    <thead>
                        <th>Name</th>
                        <th>Restore</th>
                        <th>Permanent Delete</th>
                    </thead>
                    <tbody>
                        @foreach ($product as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td><a href="{{url('restoreprod/'.$item->id)}}" class="btn-small amber "><i class="material-icons">autorenew</i></a></td>
                                <td><a href="{{url('permdeleteprod/'.$item->id)}}" class="btn-small red "><i class="material-icons">delete</i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <h5 class="center">Orders</h5>
            <div>
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Orderid</th>
                        <th>Restore</th>
                        <th>Permanent Delete</th>
                    </thead>
                    <tbody>
                        @foreach ($order as $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->orderid}}</td>
                                <td><a href="{{url('restoreorder/'.$item->orderid)}}" class="btn-small amber "><i class="material-icons">autorenew</i></a></td>
                                <td><a href="{{url('permdeleteorder/'.$item->orderid)}}" class="btn-small red "><i class="material-icons">delete</i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <h5 class="center">Payments</h5>
            <div>
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Paymentid</th>
                        <th>Amount</th>
                        <th>Restore</th>
                        <th>Permanent Delete</th>
                    </thead>
                    <tbody>
                        @foreach ($payment as $item)
                            <tr>
                                <td>{{$item->date}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->paymentid}}</td>
                                <td>{{$item->amount}}</td>
                                <td><a href="{{url('restorepayment/'.$item->paymentid)}}" class="btn-small amber "><i class="material-icons">autorenew</i></a></td>
                                <td><a href="{{url('permdeletepayment/'.$item->paymentid)}}" class="btn-small red "><i class="material-icons">delete</i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection