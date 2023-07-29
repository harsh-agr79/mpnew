@extends('customer/layout')

@section('main')
    @php
        $total = 0;
        $cus = DB::table('customers')
            ->where('name', $data[0]->name)
            ->first();
    @endphp
    <div class="mp-container">
        <div>
            <h6>Customer: {{ $data[0]->name }}</h6>
            <h6>Shop Name: {{ $cus->shopname }}</h6>
            <h6>Order Id: {{ $data[0]->orderid }}</h6>
            <h6>Date: {{ date('Y-m-d', strtotime($data[0]->created_at)) }}</h6>
            <h6>Miti: {{ getNepaliDate($data[0]->created_at) }}</h6>
        </div>
        <div class="mp-card" style="overflow-x: scroll">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th class="center">status</th>
                        <th class="center">Quantity</th>
                        <th class="center">Approved Quantity</th>
                        <th class="center">Price</th>
                        <th>total</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data as $item)
                        <tr>
                            <td @if ($item->stock == 'on') style="text-decoration: underline solid red 25%;" @endif>
                                {{ $item->item }}</td>
                            <td class="center">{{ $item->status }}</td>
                            <td class="center">{{ $item->quantity }}</td>
                            <td class="center">{{ $item->approvedquantity }}</td>
                            <td class="center">
                                {{ $item->price }}
                            </td>
                            <td>
                                {{ $a = $item->quantity * $item->price }}
                                <span class="hide">{{ $total = $total + $a }}</span>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center" style="font-weight: 700">Total</td>
                        <td style="font-weight: 700">{{ $total }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center" style="font-weight: 700">Discount</td>
                        <td style="font-weight: 700">{{ $data[0]->discount }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="center" style="font-weight: 700">Net Total</td>
                        <td style="font-weight: 700">{{ $total - $total * 0.01 * $data[0]->discount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-content mp-card" style="margin-top:30px;">
            <div class="input-field col s12">
                User Remarks: {{ $data['0']->remarks }}
            </div>
        </div>
    </div>
@endsection
