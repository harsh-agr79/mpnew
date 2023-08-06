@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card container" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Add Customer</h6>
            </div>
            <form action="{{ route('addcustomer') }}" method="POST">
                @csrf
                <div class="row">


                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Name:
                        </div>
                        <div class="col s8">
                            <input type="text" name="name" value="{{ $name }}"
                                class="inp black-text browser-default" placeholder="Name" required>
                        </div>
                        <input type="hidden" name="name1" value="{{ $name }}">
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Shop-Name:
                        </div>
                        <div class="col s8">
                            <input type="text" name="shopname" value="{{ $shopname }}"
                                class="inp black-text browser-default" placeholder="Shop-name" required>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        @error('userid')
                        <div class="red-text">{{ $message }}</div>
                    @enderror
                        <div class="col s4 right-align">
                            User ID:
                        </div>
                        <div class="col s8">
                            <input type="text" name="userid" value="{{ $userid }}"
                                class="inp black-text browser-default" placeholder="User Id" required>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Password:
                        </div>
                        <div class='input-field col s8'>
                            <input class='validate browser-default inp black-text' placeholder="password" type='password'
                                name='passwordnew' id='password' @if ($id == '') required @endif />
                            <span toggle="#password" class="field-icon toggle-password"><span
                                    class="material-icons black-text">visibility</span></span>
                        </div>
                    </div>
                    <input type="hidden" name="passwordold" value="{{ $password }}">
                    <div class="col s12 row valign-wrapper">
                        @error('contact')
                        <div class="red-text">{{ $message }}</div>
                    @enderror
                        <div class="col s4 right-align">
                            Contact:
                        </div>
                        <div class="col s8">
                            <input type="text" name="contact" value="{{ $contact }}"
                                class="inp black-text browser-default" placeholder="contact" required>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        @error('contact')
                        <div class="red-text">{{ $message }}</div>
                    @enderror
                        <div class="col s4 right-align">
                            Contact 2:
                        </div>
                        <div class="col s8">
                            <input type="text" name="contact2" value="{{ $contact2 }}"
                                class="inp black-text browser-default" placeholder="contact 2" required>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Address:
                        </div>
                        <div class="col s8">
                            <input type="text" name="address" value="{{ $address }}"
                                class="inp black-text browser-default" placeholder="Address" required>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Area:
                        </div>
                        <div class="col s8">
                            <input type="text" name="area" value="{{ $area }}"
                                class="inp black-text browser-default" placeholder="Area" required>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            State:
                        </div>
                        <div class="col s8">
                            <select id="state" name="state" class="browser-default selectinp black-text" required>
                                @if ($state != null)
                                    <option selected value="{{ $state }}">{{ $state }}</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Select State</option>
                                @endif
                                <option value="Bagmati">Bagmati</option>
                                <option value="Karnali">Karnali</option>
                                <option value="Koshi">Koshi</option>
                                <option value="Gandaki">Gandaki</option>
                                <option value="Madhesh">Madhesh</option>
                                <option value="Lumbini">Lumbini</option>
                                <option value="Sudur Paschim">Sudur Paschim</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            District:
                        </div>
                        <div class="col s8">
                            <select id="district" name="district" class="browser-default selectinp black-text" required>
                                @if ($district != null)
                                    <option selected value="{{ $district }}">{{ $district }}</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Select District</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        @error('uniqueid')
                        <div class="red-text">{{ $message }}</div>
                    @enderror
                        <div class="col s4 right-align">
                            Unique Id:
                        </div>
                        <div class="col s8">
                            <input type="text" name="uniqueid" value="{{ $uniqueid }}"
                                class="inp black-text browser-default" placeholder="Unique Id" required>
                        </div>
                        <input type="hidden" name="uniold" value="{{ $uniqueid }}">
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Referer:
                        </div>
                        <div class="input-field col s8">
                            <input type="text" name="refname" value="{{ $refname }}" id="customer"
                                class="autocomplete inp black-text browser-default" placeholder="referer"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Tax Type:
                        </div>
                        <div class="col s8">
                            <select id="select1" name="taxtype" class="browser-default selectinp black-text">
                                @if ($taxtype != null)
                                    <option selected value="{{ $taxtype }}">{{ $taxtype }}</option>
                                    <option class="black-text" value="">PAN/VAT Type</option>
                                @else
                                    <option class="black-text" value="" selected disabled>PAN/VAT Type</option>
                                @endif
                                <option class="black-text" value="PAN">PAN</option>
                                <option class="black-text" value="VAT">VAT</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Tax Number:
                        </div>
                        <div class="col s8">
                            <input type="text" name="taxnum" value="{{ $taxnum }}"
                                class="inp black-text browser-default" placeholder="PAN/VAT no.">
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            DOB:
                        </div>
                        <div class="col s8">
                            <input type="date" name="DOB" value="{{ $DOB }}"
                                class="inp black-text browser-default" placeholder="DOB">
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Open Balance:
                        </div>
                        <div class="col s8">
                            <input type="text" name="openbalance" value="{{ $openbalance }}"
                                class="inp black-text browser-default" placeholder="openbalance">
                        </div>
                    </div>

                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Open-balance Type:
                        </div>
                        <div class="col s8">
                            <select id="select1" name="obtype" class="browser-default selectinp black-text">
                                @if ($obtype != null)
                                    <option selected value="{{ $obtype }}">{{ $obtype }}</option>
                                    <option class="black-text" value="">Open Balance Type</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Open Balance Type</option>
                                @endif
                                <option class="black-text" value="debit">Debit</option>
                                <option class="black-text" value="credit">Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Type:
                        </div>
                        <div class="col s8">
                            <select id="select1" name="type" class="browser-default selectinp black-text" required>
                                @if ($type != null)
                                    <option selected value="{{ $type }}">{{ $type }}</option>
                                    <option class="black-text" value="">Customer Type</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Customer Type</option>
                                @endif
                                <option class="black-text" value="dealer">Dealer</option>
                                <option class="black-text" value="wholesaler">wholesaler</option>
                                <option class="black-text" value="retailer">Retailer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row valign-wrapper">
                        <div class="col s4 right-align">
                            Website From:
                        </div>
                        <div class="col s8">
                            <select id="select1" name="from" class="browser-default selectinp black-text">
                                @if ($from != null)
                                    <option selected value="{{ $from }}">{{ $from }}</option>
                                    <option class="black-text" value="">Website From</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Website From</option>
                                @endif
                                <option class="black-text" value="mpe">mpe</option>
                                <option class="black-text" value="dealer">Dealer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{ $id }}">

                <div class="fixed-action-btn">
                    <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                        style="border-radius: 10px;">
                        Submit
                        <i class="left material-icons">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('#state').on('change', function() {
            var value = $('#state').val()
            // console.log(value);
            var bag = [
                "sindhuli",
                "ramechhap",
                "dolakha",
                "bhaktapur",
                "dhading",
                "kathmandu",
                "kavrepalanchok",
                "lalitpur",
                "nuwakot",
                "rasuwa",
                "sindhupalchok",
                "chitwan",
                "makwanpur"
            ]
            var gan = [
                "baglung",
                "gorkha",
                "kaski",
                "lamjung",
                "manang",
                "mustang",
                "myagdi",
                "nawalpur",
                "parbat",
                "syangja",
                "tanahun"
            ]
            var kar = [
                "western rukum ",
                "salyan",
                "dolpa",
                "humla",
                "jumla",
                "kalikot",
                "mugu",
                "surkhet",
                "dailekh",
                "jajarkot"
            ]
            var lum = [
                "kapilvastu",
                "rupandehi",
                "arghakhanchi",
                "gulmi",
                "palpa",
                "dang",
                "pyuthan",
                "rolpa",
                "eastern rukum ",
                "banke",
                "bardiya"
            ]
            var mad = [
                "sarlahi",
                "dhanusha",
                "bara",
                "rautahat",
                "saptari",
                "siraha",
                "mahottari",
                "parsa"
            ]
            var kos = [
                "bhojpur",
                "dhankuta",
                "ilam",
                "jhapa",
                "khotang",
                "morang",
                "okhaldhunga",
                "panchthar",
                "sankhuwasabha",
                "solukhumbu",
                "sunsari",
                "taplejung",
                "terhathum",
                "udayapur"
            ]
            var sud = [
                "achham",
                "baitadi",
                "bajhang",
                "bajura",
                "dadeldhura",
                "darchula",
                "doti",
                "kailali",
                "kanchanpur"
            ]

            if (value == 'Bagmati') {
                var dis = bag;
            }
            if (value == 'Gandaki') {
                var dis = gan;
            }
            if (value == 'Karnali') {
                var dis = kar
            }
            if (value == 'Madhesh') {
                var dis = mad
            }
            if (value == 'Koshi') {
                var dis = kos
            }
            if (value == 'Lumbini') {
                var dis = lum
            }
            if (value == 'Sudur Paschim') {
                var dis = sud
            }
            console.log(dis)
            var sc = $('#district');
            sc.empty();
            sc.append($('<option></option>').attr('value', null).attr('selected', 'true').text(
                'select District'));
            // dis.forEach(element => {
            //     $sc.append($('<option></option>')
            //                 .attr("value", value.subcategory).text(value.subcategory))
            // });
            for (let i = 0; i < dis.length; i++) {
                sc.append($('<option></option>')
                    .attr("value", dis[i]).text(dis[i]))
            }
        })
    </script>
    <script>
        var clicked = 0;

        $(".toggle-password").click(function(e) {
            e.preventDefault();

            $(this).toggleClass("toggle-password");
            if (clicked == 0) {
                $(this).html('<span class="material-icons">visibility_off</span >');
                clicked = 1;
            } else {
                $(this).html('<span class="material-icons">visibility</span >');
                clicked = 0;
            }

            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/getref',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    console.log(datacust2)
                    $('input#customer').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })
    </script>
@endsection
