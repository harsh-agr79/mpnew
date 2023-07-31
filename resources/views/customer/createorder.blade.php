@extends('customer/layout')

@section('main')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="row bg-content textcol" style="margin: 0; padding: 0;">
        <div class="col s2 center" style="padding:5px;">
            <a data-target="#powerbank" class="browser-default scroll-link">
                <div><i class="fa-solid fa-car-battery textcol" style="font-size: 25px;"></i></div>
                <div style="font-size: 8px; text-transform: uppercase; margin-top:4px;" class="textcol">powerbank</div>
            </a>
        </div>
        <div class="col s2 center" style="padding:5px;">
            <a data-target="#charger" class="browser-default scroll-link">
                <div><i class="fa-solid fa-plug-circle-bolt textcol" style="font-size: 25px;"></i></div>
                <div style="font-size: 8px; text-transform: uppercase; margin-top:4px;" class="textcol">charger</div>
            </a>
        </div>
        <div class="col s2 center" style="padding:5px; scroll-link">
            <a data-target="#cable" class="browser-default scroll-link">
                <div><i class="fa-brands fa-usb textcol" style="font-size: 25px;"></i></div>
                <div style="font-size: 8px; text-transform: uppercase; margin-top:4px;" class="textcol">cable</div>
            </a>
        </div>
        <div class="col s2 center" style="padding:5px;">
            <a data-target="#earphone" class="browser-default scroll-link">
                <div><i class="fa-sharp fa-solid fa-ear-listen textcol" style="font-size: 25px;"></i></div>
                <div style="font-size: 8px; text-transform: uppercase; margin-top:4px;" class="textcol">earphone</div>
            </a>
        </div>
        <div class="col s2 center" style="padding:5px;">
            <a data-target="#btitem" class="browser-default scroll-link">
                <div><i class="fa-brands fa-bluetooth-b textcol" style="font-size: 25px;"></i></div>
                <div style="font-size: 8px; text-transform: uppercase; margin-top:4px;" class="textcol">bluetooth</div>
            </a>
        </div>
        <div class="col s2 center" style="padding:5px;">
            <a data-target="#others" class="browser-default scroll-link">
                <div><i class="fa-sharp fa-solid fa-cart-plus textcol" style="font-size: 25px;"></i></div>
                <div style="font-size: 8px; text-transform: uppercase; margin-top:4px;" class="textcol">others</div>
            </a>
        </div>
    </div>
    <form enctype="multipart/form-data" class="mp-container" action="{{ route('user.addorder') }}" method="post">
        @csrf
        <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s') }}" required>
        <input type="hidden" name="name" value="{{ $user->name }}" required>
        <div class="mp-card">
            <div class="row" style="margin: 0; padding: 0;">
                <div class="col s2 center" style="margin: 0; padding: 5px;">
                    <a class="btn amber modal-trigger" href="#cart"><i
                            class="material-icons">shopping_cart</i>
                    </a>
                </div>
                <div class='input-field col s10' style="margin:0; padding: 5px;">
                    <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                        autocomplete="off" type='search' id='search' />
                    <span class="field-icon" id="close-search"><span class="material-icons"
                            id="cs-icon">search</span></span>
                </div>
                <div class="right" style="margin: 0; padding: 0;">
                   Bill Amount: <span id="totalamt"></span>
                </div>
            </div>
        </div>
        <div id="cart" class="modal">
            <div class="modal-content bg-content">
                <div class="right">
                    Bill Amount: <span id="totalamt2"></span>
                 </div>
                <div class="center">
                    <h5>Cart</h5>
                </div>
                <table class="gttable">
                    <thead>
                        <th>Name</th>
                        <th>price</th>
                        <th class="center">Quantity</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr style="display: none;" id="{{ $item->id . 'list' }}">
                                <td>{{ $item->name }}</td>
                                <td class="gtprice">{{ $item->price }}</td>
                                <td class="center"><input type="number" id="{{ $item->id . 'listinp' }}" name="quantity[]"
                                        inputmode="numeric" pattern="[0-9]*" placeholder="Quantity"
                                        class="browser-default prod-inp gtquantity" onkeyup="changequantity2({{ $item->id }})"
                                        onfocusout="changequantity2({{ $item->id }})"></td>
                                <input type="hidden" name="item[]" value="{{ $item->name }}">
                                <input type="hidden" name="price[]" value="{{ $item->price }}">
                                <input type="hidden" name="prodid[]" value="{{ $item->produni_id }}">
                                <input type="hidden" name="category[]" value="{{ $item->category }}">
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-content">
                <button class="btn green left" type="submit" name="submit" value="save">
                    Save
                </button>
                <a class="btn red modal-close">
                    Edit
                </a>
                <button class="btn amber" type="submit" name="submit" value="submit">
                    Confirm
                </button>
            </div>
        </div>
    </form>
    <div class="prod-container">
       @php
        $category = '';
        $category2 = '';
       @endphp
        @foreach ($data as $item)
            @php
                if ($category2 == $item->category) {
                    $category = '';
                }
                else{
                    $category = $item->category;
                }
                $category2 = $item->category
            @endphp
            <div class="mp-card row prod" id="{{$category}}" style="margin: 3px; padding: 10px;">
                <div class="col s4" style="padding: 0;  margin: 0;">
                    <img src="{{ asset('storage/media/' . $item->img) }}" class="prod-img materialboxed" alt="">
                </div>
                <div class="col s8 row" style="padding: 0; margin: 0;">
                    <div class="col s12" style=" margin: 0; padding: 0;">
                        <span class="prod-title" style="cursor: pointer;" onclick="details({{ $item->id }})">{{ $item->name }}</span>
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
                        <div class="col s4 center"><span class="prod-price">Rs.{{ $item->price }}</span></div>
                        <div class="col s8"><input type="number" id="{{ $item->id . 'viewinp' }}" inputmode="numeric"
                                pattern="[0-9]*" placeholder="Quantity" class="browser-default prod-inp right"
                                onkeyup="changequantity({{ $item->id }})"></div>
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

    <div id="details" class="modal bottom-sheet bg-content">
        <div class="modal-content bg-content">
            <div class="row bg-content">
                <div class="row col s12">
                    <div class="col s6">
                        <img id="mod-img1" class="materialboxed" height="100" src="" alt="">
                    </div>
                    <div class="col s6">
                        <img id="mod-img2" class="materialboxed" height="100" src="" alt="">
                    </div>
                </div>
                <div class="col s12">
                    <h5 id="mod-name"></h5>
                </div>
                <div class="col s6">
                    <span id="mod-price" style="font-weight: 600;"></span>
                </div>
                <div class="col s6">
                    <span id="mod-category" style="font-weight: 600;"></span>
                </div>
                <div class="col s6" style="margin-top: 10px;">
                    <span style="font-weight: 600;">Tags:</span> <span id="mod-tags"></span>
                </div>
                <div class="col s12" style="margin-top: 10px;">
                    <span style="font-weight: 600;">Details:</span>
                    <div style="white-space: pre-wrap" id="mod-details"></div>
                </div>
            </div>
        </div>
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
            getTotal();
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
            getTotal();
        }

        function getTotal(){
            var price = $('.gtprice');
            var quantity = $('.gtquantity');
            var total = 0;
            for (let i = 0; i < price.length; i++) {
                if(quantity[i].value > 0){
                    total = total + price[i].innerHTML * quantity[i].value;
                }
            }
            $('#totalamt').text(total);
            $('#totalamt2').text(total);
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

        function details(id) {
            $.ajax({
                type: "GET",
                url: "/user/finditem/" + id,
                dataType: "json",
                success: function(response) {
                    $('#mod-name').text(response.name)
                    $('#mod-price').text('Rs.'+response.price)
                    $('#mod-category').text(response.category)
                    $('#mod-tags').text(response.subcat)
                    $('#mod-details').text(response.details)
                    $('#mod-img1').attr('src', '/storage/media/' + response.img)
                    $('#mod-img2').attr('src', '/storage/media/' + response.img2)
                    $('#details').modal('open');
                    history.pushState(null, document.title, location.href);
                }
            })
        }
    $('.materialboxed').on('click', function(){
        history.pushState(null, document.title, location.href);
    })
    $('.scroll-link').on('click', function(e){
        e.preventDefault();
        console.log($(this).attr('data-target'));
        $(".prod-container").animate({
            scrollTop: $('#powerbank').offset().top - 250,
        }, 0);
        $(".prod-container").animate({
                  scrollTop: $($(this).attr('data-target')).offset().top - 240,
        }, 800);
        // $('.prod-container').scrollTo($(`${$(this).attr('data-target')}`))
    })
    window.onpopstate = function () {
        var prodimg = $('.materialboxed');
        var proddet = $('#details');
        if(proddet.hasClass('open') || prodimg.hasClass('active')){
            $('#details').modal('close');
            $('.active').materialbox('close');
            // $('.prod-container').css()
        }
        else{
            history.back();
        }
    };
    </script>
@endsection
