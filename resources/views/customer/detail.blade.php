@extends('customer/layout')

@section('main')
    @php
        $total = 0;
        $cus = DB::table('customers')
            ->where('name', $data[0]->name)
            ->first();
    @endphp
    <div class="mp-container">
        <div class="right center row">
            @if ($data[0]->mainstatus == 'blue')
            <div class="col s12">
                <div style="margin: 10px 0;">
                    <a href="{{ url('/user/editorder/' . $data[0]->orderid) }}"
                        class="btn-small amber white-text">
                        Edit
                        <i class="material-icons right">edit</i>
                    </a>
                </div>
                <div>
                    <a href="{{ url('/user/deleteorder/' . $data[0]->orderid) }}"
                        class="btn-small red white-text">
                        Delete
                        <i class="material-icons right">delete</i>
                    </a>
                </div>
            </div>

            @endif
            @if ($data[0]->mainstatus != 'blue')
                <div class="col s12">
                    <div style="margin: 10px 0;">
                        <a href="{{ url('/user/saveorder/' . $data[0]->orderid) }}" target="_blank"
                            class="btn-small amber white-text">
                            Img <i class="material-icons right">file_download</i>
                        </a>
                    </div>
                    <div>
                        <a href="{{ url('/user/printorder/' . $data[0]->orderid) }}" target="_blank"
                            class="btn-small amber white-text">
                            PDF <i class="material-icons right">picture_as_pdf</i>
                        </a>
                    </div>
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
                                @if ($item->status == 'pending')
                                    {{ $a = $item->quantity * $item->price }}
                                @else
                                    {{ $a = $item->approvedquantity * $item->price }}
                                @endif
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
        <form method="post" action="{{ route('user.detailedit') }}">
            @csrf
            <input type="hidden" name="orderid" value="{{ $data[0]->orderid }}">
            <div class="bg-content mp-card" style="margin-top:30px;">
                <div class="input-field col s12">
                    User Remarks:
                    <textarea name="userremarks" class="browser-default inp textcol" cols="30" rows="10">{{ $data[0]->userremarks }}</textarea>
                </div>
            </div>
            <div class="fixed-action-btn">
                <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})" style="border-radius: 10px;">
                    Submit
                    <i class="left material-icons">send</i>
                </button>
            </div>
        </form>

        <div class="mp-card row" style="margin-top: 10px;">
            <div class="col s12">
                Admin Remarks: {{ $data[0]->remarks }}
            </div>
            <div class="col s12">
                Cartoons: {{ $data[0]->cartoons }}
            </div>
            <div class="col s12">
                Transport Detail: {{ $data[0]->transport }}
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        clickButton()

        function clickButton() {
            document.querySelector('.eddl').click();
        }
    </script>
@endsection
