@extends('marketer/layout')

@section('main')
    @php
        $total = 0;
        $cus = DB::table('customers')
            ->where('name', $data[0]->name)
            ->first();
    @endphp
    <div>
        <div class="right center">
            @if ($data[0]->mainstatus == 'blue')
                <div>
                    <a class="btn-flat dropdown-trigger" data-target="menu">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul id='menu' class='dropdown-content'>
                        <li><a href="{{ url('/marketer/editorder/' . $data[0]->orderid) }}">Edit</a></li>
                        <li><a href="{{ url('/marketer/deleteorder/' . $data[0]->orderid) }}">Delete</a></li>
                    </ul>
                </div>
            @endif
            @if ($data[0]->mainstatus != 'blue')
            <div style="margin: 10px 0;">
                <a href="{{ url('/marketer/saveorder/' . $data[0]->orderid) }}" target="_blank"
                    class="btn-small amber white-text">
                    Img <i class="material-icons right">file_download</i>
                </a>
            </div>
            <div>
                <a href="{{ url('/marketer/printorder/' . $data[0]->orderid) }}" target="_blank"
                    class="btn-small amber white-text">
                    PDF <i class="material-icons right">picture_as_pdf</i>
                </a>
            </div>
            @endif
        </div>
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
                            <th class="center">Quantity</th>
                            <th class="center">Approved Quantity</th>
                            <th class="center">Price</th>
                            <th>total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $item)
                            <tr>
                                <td
                                    @if ($item->stock == 'on') style="text-decoration: underline solid red 25%;" @endif>
                                    {{ $item->item }}</td>
                                <td class="center">{{ $item->quantity }}</td>
                                <td class="center">{{ $item->approvedquantity }}</td>
                                <td class="center">
                                  {{$item->price}}
                                </td>
                                <td>
                                    {{ $a = $item->approvedquantity * $item->price }}
                                    <span class="hide">{{ $total = $total + $a }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
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
                            <td class="center" style="font-weight: 700">Discount</td>
                            <td style="font-weight: 700">{{ $data[0]->discount }}</td>
                        </tr>
                        <tr>
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
