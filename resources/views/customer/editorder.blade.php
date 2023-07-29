

@extends('customer/layout')

@section('main')
    <form enctype="multipart/form-data" action="{{ route('user.editorder') }}" method="post">
        @csrf
        <input type="hidden" name="orderid" value="{{ $order[0]->orderid }}">
        <div class="mp-card" style="margin-top: 20px;">
            <div class="row">
                <div class="row col s12">
                    <div class="col s4 center">
                        <a class="btn amber modal-trigger" href="#cart" style="margin-top: 20px;">
                            Cart <i class="material-icons left">shopping_cart</i>
                        </a>
                    </div>
                    <div class='input-field col s8'>
                        <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                            autocomplete="off" type='search' id='search' />
                        <span class="field-icon" id="close-search"><span class="material-icons"
                                id="cs-icon">search</span></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="cart" class="modal">
            <div class="modal-content">
                <div class="center">
                    <h5>Cart</h5>
                </div>
                <table>
                    <thead>
                        <th>Name</th>
                        <th>price</th>
                        <th class="center">Quantity</th>
                    </thead>
                    <tbody>
                        @foreach ($order as $item)
                            <tr id={{ $item->id . 'list' }}>
                                <td>{{ $item->item }}</td>
                                <td>{{ $item->price }}</td>
                                <td class="center"><input type="number"
                                        @if ($item->status != 'pending') disabled onclick="M.toast({html: 'Order of this product already {{ $item->status }}, Cant edit'})" @endif
                                        id="{{ $item->id . 'listinp' }}" name="quantity[]" inputmode="numeric"
                                        pattern="[0-9]*" placeholder="Quantity" value="{{ $item->quantity }}"
                                        class="browser-default prod-inp" onchange="changequantity2({{ $item->id }})"
                                        onfocusout="changequantity2({{ $item->id }})"></td>
                                @if ($item->status != 'pending')
                                    <input type="hidden" name="quantity[]" value="{{ $item->quantity }}">
                                @endif
                                <input type="hidden" name="id[]" value="{{ $item->id }}">
                                <input type="hidden" name="item[]" value="{{ $item->item }}">
                                <input type="hidden" name="price[]" value="{{ $item->price }}">
                                {{-- <input type="hidden" name="prodid[]" value="{{ $item->produni_id }}"> --}}
                                {{-- <input type="hidden" name="category[]" value="{{ $item->category }}"> --}}
                                <input type="hidden" name="status[]" value="{{ $item->status }}">
                            </tr>
                        @endforeach
                        @foreach ($data as $item)
                            <tr style="display: none;"id={{ $item->id . 'list' }}>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->price }}</td>
                                <td class="center"><input type="number" id="{{ $item->id . 'listinp' }}" name="quantity[]"
                                        inputmode="numeric" pattern="[0-9]*" placeholder="Quantity"
                                        class="browser-default prod-inp" onchange="changequantity2({{ $item->id }})"
                                        onfocusout="changequantity2({{ $item->id }})"></td>
                                <input type="hidden" name="id[]" value="">
                                <input type="hidden" name="item[]" value="{{ $item->name }}">
                                <input type="hidden" name="price[]" value="{{ $item->price }}">
                                {{-- <input type="hidden" name="prodid[]" value="{{ $item->produni_id }}"> --}}
                                {{-- <input type="hidden" name="category[]" value="{{ $item->category }}"> --}}
                                <input type="hidden" name="status[]" value="pending">
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a class="btn red modal-close">
                    Edit
                </a>
                <button class="btn amber" type="submit">
                    Submit
                </button>
            </div>
        </div>
    </form>
    <div style="height: 65vh; overflow-y: scroll; margin-top: 10px;" class="prod-container">
        @foreach ($order as $item)
            <div class="mp-card row prod" style="margin: 3px; padding: 10px;">
                <div class="col s4" style="padding: 0;  margin: 0;">
                    <img src="{{ asset('storage/media/' . $item->img) }}" class="prod-img materialboxed" alt="">
                </div>
                <div class="col s8 row" style="padding: 0; margin: 0;">
                    <div class="col s12" style=" margin: 0; padding: 0;">
                        <span class="prod-title">{{ $item->item }}</span>
                    </div>
                    <div class="col s12 row" style="padding: 0;  margin: 0;">
                        <span class="prod-det col s6">{{ $item->category }} </span>
                        <span class="prod-det col s6">
                            @if ($item->stock == 'on')
                                <span class="red-text right">Out of Stock</span>
                            @else
                                <span class="green-text right">In Stock</span>
                            @endif
                        </span>
                    </div>
                    <div class="row col s12 price-line valign-wrapper" style="padding: 0;  margin: 0;">
                        <div class="col s4"><span class="prod-price">Rs.{{ $item->price }}</span></div>
                        <div class="col s8"><input type="number"
                                @if ($item->status != 'pending') disabled onclick="M.toast({html: 'Order of this product already {{ $item->status }}, Cant edit'})" @endif
                                id="{{ $item->id . 'viewinp' }}" inputmode="numeric" pattern="[0-9]*"
                                placeholder="Quantity" value="{{ $item->quantity }}"
                                class="browser-default prod-inp right" onchange="changequantity({{ $item->id }})">
                        </div>
                    </div>
                </div>

                @php
                    $subcat = explode('|', $item->subcat);
                @endphp
                @foreach ($subcat as $item)
                    <span class="hide">{{ $item }}</span>
                @endforeach

            </div>
        @endforeach
        @foreach ($data as $item)
            <div class="mp-card row prod" style="margin: 3px; padding: 10px;">
                <div class="col s4" style="padding: 0;  margin: 0;">
                    <img src="{{ asset('storage/media/' . $item->img) }}" class="prod-img materialboxed" alt="">
                </div>
                <div class="col s8 row" style="padding: 0; margin: 0;">
                    <div class="col s12" style=" margin: 0; padding: 0;">
                        <span class="prod-title">{{ $item->name }}</span>
                    </div>
                    <div class="col s12 row" style="padding: 0;  margin: 0;">
                        <span class="prod-det col s6">{{ $item->category }} </span>
                        <span class="prod-det col s6">
                            @if ($item->stock == 'on')
                                <span class="red-text right">Out of Stock</span>
                            @else
                                <span class="green-text right">In Stock</span>
                            @endif
                        </span>
                    </div>
                    <div class="row col s12 price-line valign-wrapper" style="padding: 0;  margin: 0;">
                        <div class="col s4"><span class="prod-price">Rs.{{ $item->price }}</span></div>
                        <div class="col s8"><input type="number" id="{{ $item->id . 'viewinp' }}" inputmode="numeric"
                                pattern="[0-9]*" placeholder="Quantity" class="browser-default prod-inp right"
                                onchange="changequantity({{ $item->id }})"></div>
                    </div>
                </div>

                @php
                    $subcat = explode('|', $item->subcat);
                @endphp
                @foreach ($subcat as $item)
                    <span class="hide">{{ $item }}</span>
                @endforeach

            </div>
        @endforeach
    </div>

    <div class="fixed-action-btn">
        <a class="btn btn-large red modal-trigger" href="#cart" style="border-radius: 10px;">
            Order
            <i class="left material-icons">send</i>
        </a>
    </div>
    <script>
        function changequantity(id) {
            var qval = $(`#${id}viewinp`).val();
            if (qval < 1 || qval == null) {
                $(`#${id}list`).hide();
                $(`#${id}listinp`).val('');
            } else {
                $(`#${id}list`).show();
                $(`#${id}listinp`).val(qval);
            }
        }

        function changequantity2(id) {
            var qval = $(`#${id}listinp`).val();
            if (qval < 1 || qval == null) {
                if ($(`#${id}listinp`).is(":focus")) {
                    // console.log('focus')
                    // $(`#${id}list`).hide();
                    $(`#${id}listinp`).val('');
                    $(`#${id}viewinp`).val(qval);
                } else {
                    // console.log('notfocus')
                    $(`#${id}list`).hide();
                    $(`#${id}listinp`).val('');
                    $(`#${id}viewinp`).val(qval);
                }
            } else {
                $(`#${id}list`).show();
                $(`#${id}listinp`).val(qval);
                $(`#${id}viewinp`).val(qval);
            }
        }
    </script>
    <script>
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let cont = document.getElementsByClassName('prod-container');
            let prod = $('.prod')
            clsBtn.addEventListener("click", function() {
                a.value = '';
                a.focus();
                var filter = '';
                for (var i = 0; i < prod.length; i++) {
                    prod[i].style.display = "";
                }
                $('#cs-icon').text('search')
            });
            if (filter === '') {
                $('#cs-icon').text('search')
            } else {
                $('#cs-icon').text('close')
            }

            for (var i = 0; i < prod.length; i++) {
                let span = prod[i].getElementsByTagName('span');
                // console.log(td);
                for (var j = 0; j < span.length; j++) {
                    if (span[j]) {
                        let textvalue = span[j].textContent || span[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            prod[i].style.display = "";
                            break;
                        } else {
                            prod[i].style.display = "none"
                        }
                    }
                }
            }
        }
    </script>
@endsection
