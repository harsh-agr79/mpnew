@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 30px;">
            <ul class="collapsible">
                @foreach ($catsales as $item)
                <li>
                    <div class="collapsible-header row">
                        <div class="col s4">{{$item->category}}</div>
                        <div class="col s4">{{$item->sum}}</div>
                        <div class="col s4">{{money($item->samt - $item->damt)}}</div>
                    </div>
                    <div class="collapsible-body"><span>
                        @php
                            if ($item->category == 'powerbank') {
                                $prod = $pb;
                            }
                            if ($item->category == 'charger') {
                                $prod = $ch;
                            }
                            if ($item->category == 'cable') {
                                $prod = $ca;
                            }
                            if ($item->category == 'btitem') {
                                $prod = $bt;
                            }
                            if ($item->category == 'earphone') {
                                $prod = $ep;
                            }
                            if ($item->category == 'others') {
                                $prod = $oth;
                            }

                        @endphp
                        <table>
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>    
                                </tr>    
                            </thead>
                            <tbody>
                                @foreach ($prod as $item2)
                                    <tr>
                                        <td>{{$item2->item}}</td>
                                        <td>{{$item2->sum}}</td>
                                        <td>{{$item2->samt-$item2->damt}}</td>
                                    </tr>
                                @endforeach    
                            </tbody>    
                        </table>    
                    </span></div>
                  </li>
                @endforeach
              </ul>
        </div>
    </div>
@endsection