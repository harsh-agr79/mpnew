@extends('admin/layout')

@section('main')
    <div>
        <form action=""></form>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="row">
                <div class="input-field col s6">
                    <input type="date" class="inp browser-default black-text" value="{{ date('Y-m-d') }}">
                </div>
                <div class="input-field col s6">
                    <input type="text" name="name" id="customer" placeholder="Customer"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class='input-field col s8'>
                    <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                        autocomplete="off" type='search' id='search' />
                    <span class="field-icon" id="close-search"><span class="material-icons"
                            id="cs-icon">search</span></span>
                </div>
                <div class="col s4 center">
                    <button class="btn amber" style="margin-top: 20px;">
                        Cart <i class="material-icons left">shopping_cart</i>
                    </button>
                </div>
            </div>
        </div>
        <div style="height: 65vh; overflow-y: scroll; margin-top: 10px;" class="prod-container">
            @foreach ($data as $item)
                <div class="mp-card row" style="margin: 3px;">
                    <div class="col s4">
                        <img src="{{asset('storage/media/' . $item->img)}}" class="prod-img materialboxed" alt="">
                    </div>
                    <div class="col s8 row">
                        <div class="col s12">
                            <span class="prod-title">{{$item->name}}</span>
                        </div>
                        <div class="col s12">
                            <span class="prod-det">{{$item->category}} </span><span class="prod-det"> @if ($item->stock == 'on')
                                <span class="red-text">Out of Stock</span>
                            @else
                                <span class="green-text">In Stock</span>
                            @endif</span>
                        </div>
                        <div class="row col s12 price-line">
                            <div class="col s4">Rs.{{$item->price}}</div>
                            <div class="col s8"><input type="number"  inputmode="numeric"  pattern="[0-9]*" placeholder="Quantity" class="browser-default prod-inp"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

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
