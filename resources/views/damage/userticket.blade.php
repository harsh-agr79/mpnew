@extends('customer/layout')

@section('main')
    <script type="text/javascript">
        function preback() {
            window.history.forward();
        }
        setTimeout("preback()", 0);
        window.onunload = function() {
            null
        };
    </script>
    <style>
        textarea {
  resize: vertical;
  height: 60px;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <form enctype="multipart/form-data" class="mp-container" id="rform" action="{{ route('tkt') }}" method="post">
        @csrf
        <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s') }}" required>
        <input type="hidden" name="name" value="{{ $user->name }}" required>
        <div class="mp-card" style="margin: 10px 0;">
            <div class="row" style="margin: 0; padding: 0;">
                <div class="col s2 center" style="margin: 0; padding: 5px;">
                    <a class="btn amber modal-trigger" href="#cart"><i class="material-icons">production_quantity_limits</i>
                    </a>
                </div>
                <div class='input-field col s10' style="margin:0; padding: 5px;">
                    <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                        autocomplete="off" type='search' id='search' />
                    <span class="field-icon" id="close-search"><span class="material-icons"
                            id="cs-icon">search</span></span>
                </div>
            </div>
        </div>
        <div id="cart" class="modal bg-under">
            <div class="modal-content bg-under">
                <div class="center">
                    <h5>Damaged Goods</h5>
                </div>
                <div>
                    @foreach ($data as $item)
                    <div style="display: none; margin-top: 5px;" class="mp-card" id="{{ $item->id . 'list' }}">
                        <div class="row" style="margin:3px; padding: 0;">
                            <div class="col s6" style="font-weight: 600;">{{ $item->name }}</div>
                            <div class="col s6"><input type="number" id="{{ $item->id . 'listinp' }}" name="quantity[]"
                                    inputmode="numeric" pattern="[0-9]*" placeholder="Quantity"
                                    class="browser-default inp gtquantity"
                                    onkeyup="changequantity2({{ $item->id }})"
                                    onfocusout="changequantity2({{ $item->id }})"></div>
                        </div>
                        <div style="margin: 0;">
                            <div class="center"><textarea type="text" id="{{ $item->id . 'listinpdet' }}" name="detail[]"
                                placeholder="Detail"
                                class="browser-default inp"
                                onkeyup="changequantity2({{ $item->id }})"
                                onfocusout="changequantity2({{ $item->id }})"></textarea></div>
                        </div>
                        
                        
                        <input type="hidden" name="item[]" value="{{ $item->name }}">
                        <input type="hidden" name="price[]" value="{{ $item->price }}">
                        <input type="hidden" name="prodid[]" value="{{ $item->produni_id }}">
                        <input type="hidden" name="category[]" value="{{ $item->category }}">
                    </div>
                @endforeach
                </div>
                       
            </div>
            <div class="modal-footer bg-under">
                <a class="btn red modal-close">
                    Edit
                </a>
                <button class="btn amber sub-btn" type="submit" onclick="changebtn()">
                    Confirm
                </button>
            </div>
        </div>
    </form>
    <div class="prod-container" style="height: 78vh;">
        @php
            $category = '';
            $category2 = '';
        @endphp
        @foreach ($data as $item)
            @php
                if ($category2 == $item->category) {
                    $category = '';
                } else {
                    $category = $item->category;
                }
                $category2 = $item->category;
            @endphp
            <div class="mp-card row prod" id="{{ $category }}" style="margin: 3px; padding: 10px;">
                <div class="col s3" style="padding: 0;  margin: 0;">
                    <img src="{{ asset('storage/media/' . $item->img) }}" class="prod-img materialboxed" alt="">
                </div>
                <div class="col s9 row" style="padding: 0; margin: 0;">
                    <div class="col s6" style=" margin: 0; padding: 0;">
                        <span class="prod-title">{{ $item->name }}</span>
                    </div>
                    <div class="col s6"><input type="number" id="{{ $item->id . 'viewinp' }}" inputmode="numeric"
                            pattern="[0-9]*" placeholder="Quantity" class="browser-default inp"
                            onkeyup="changequantity({{ $item->id }})"></div>
                    <div class="row col s12 price-line valign-wrapper"  style="padding: 0;  margin: 0;">
                        <div class="col s12"><textarea type="text" id="{{ $item->id . 'viewinpdet' }}" onkeyup="changequantity({{ $item->id }})" placeholder="Damage - Details"
                                class="browser-default inp"></textarea></div>
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
            Send
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
            var detval = $(`#${id}viewinpdet`).val();
            console.log(detval)
            if (qval < 1 || qval == null) {
                $(`#${id}list`).hide();
                $(`#${id}listinp`).val('');
                $(`#${id}listinpdet`).val('');
            } else {
                $(`#${id}list`).show();
                $(`#${id}listinp`).val(qval);
                $(`#${id}listinpdet`).val(detval);
            }
        }

        function changequantity2(id) {
            var qval = $(`#${id}listinp`).val();
            var detval = $(`#${id}listinpdet`).val();
            console.log(detval)
            if (qval < 1 || qval == null) {
                if ($(`#${id}listinp`).is(":focus")) {
                    // console.log('focus')
                    // $(`#${id}list`).hide();
                    $(`#${id}listinp`).val('');
                    $(`#${id}listinpdet`).val('');
                    $(`#${id}viewinp`).val(qval);
                    $(`#${id}viewinpdet`).val(detval);
                } else {
                    // console.log('notfocus')
                    $(`#${id}list`).hide();
                    $(`#${id}listinp`).val('');
                    $(`#${id}listinpdet`).val('');
                    $(`#${id}viewinp`).val(qval);
                    $(`#${id}viewinpdet`).val(detval);
                }
            } else {
                $(`#${id}list`).show();
                $(`#${id}listinp`).val(qval);
                $(`#${id}viewinp`).val(qval);
                $(`#${id}listinpdet`).val(detval);
                $(`#${id}viewinpdet`).val(detval);
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
        $('.materialboxed').on('click', function() {
            history.pushState(null, document.title, location.href);
        })
        window.onpopstate = function() {
            var prodimg = $('.materialboxed');
            var proddet = $('#details');
            if (proddet.hasClass('open') || prodimg.hasClass('active')) {
                $('#details').modal('close');
                $('.active').materialbox('close');
                // $('.prod-container').css()
            } else {
                history.back();
            }
        };
        // $('#rform').submit(function(){

        // })
    </script>
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
    <script>
        // $(function() {
        //     let ip_address = 'socket.startuplair.com';
        //     // let socket_port = '3000';
        //     let socket = io(ip_address);

        //     let chatInput = $('#chatInput');

        //     var name = `{{ $user->name }}`

        //     var message = "New Order From " + name
        //     $('#rform').on('submit', function() {
        //         socket.emit('sendnotifToServer', message);
        //         $('.sub-btn').text('Order Sent').attr('disabled', 'true');
        //         window.history.pushState(null, document.title, '/home');
        //     })
        // })
    </script>
@endsection
