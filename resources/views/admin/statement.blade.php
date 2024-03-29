@php
    if ($admin->type == 'marketer') {
        $type = 'marketer';
        $url = '/marketer';
    } else {
        $type = 'admin';
        $url = '';
    }
@endphp

@extends($type . '/layout')


@section('main')
    <div>
        <div class="center">
            <h5>Statements</h5>
        </div>
        <div class="mp-card switch row" style="margin: 20px;">
            <div class='input-field col s12 m4'>
                <input class='validate browser-default inp search black-text' onkeyup="searchFun()" autocomplete="off"
                    type='search' name='search' id='search' />
                <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
            </div>
            <div class="col s12 m8" style="margin-top: 20px;">
                <label>
                    Name
                    <input onchange="tog()" type="checkbox">
                    <span class="lever"></span>
                    Shop Name
                </label>
                <label style="margin-left: 30px;">
                    <input onchange="bal()" checked type="checkbox" />
                    <span> Balance</span>
                </label>
                <label>
                    <label>
                        <input onchange="tdy()" type="checkbox" />
                        <span>30days</span>
                    </label>
                    <label>
                        <input type="checkbox" onchange="fdy()" />
                        <span>45days</span>
                    </label>
                    <label>
                        <input type="checkbox" onchange="sdy()" />
                        <span>60days</span>
                    </label>
                    <label>
                        <input type="checkbox" onchange="ndy()" />
                        <span>90days</span>
                    </label>
            </div>
        </div>
        <div class="mp-card" style="overflow-x: scroll;">
            <table class="sortable">
                <thead>
                    <tr>
                        <th>|</th>
                        <th>SN</th>
                        <th class="name">Name</th>
                        <th class="shop" style="display: none;">Shop</th>
                        <th>Activity</th>
                        <th class="">type</th>
                        <th class="bal ">Balance</th>
                        <th class="tdy " style="display: none;">30 days</th>
                        <th class="fdy " style="display: none;">45 days</th>
                        <th class="sdy " style="display: none;">60 days</th>
                        <th class="ndy " style="display: none;">90 days</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($data as $item)
                        @php
                            $bal = explode('|', $item->balance);
                        @endphp
                        <tr ondblclick="openbs('{{ $item->name }}')">
                            <td  sorttable_customkey="{{$item->actcolor}}">
                                <div class="{{ $item->actcolor }}"
                                    style="height: 20px; width:5px;"></div>
                            </td>
                            <td>{{ $a = $a + 1 }}</td>
                            <td class="name">{{ $item->name }}</td>
                            <td class="shop" style="display: none;">{{ $item->shopname }}</td>
                            <td>{{$item->activity}}</td>
                            <td
                                class="black-text  @if ($item->type == 'dealer') purple lighten-5 @elseif($item->type == 'wholesaler') lime lighten-5 @elseif($item->type == 'retailer') light-blue lighten-5 @else @endif">
                                {{ $item->type }}</td>
                            <td class="{{ $bal[0] }} lighten-5 bal black-text ">{{ $bal[1] }}</td>
                            <td class="@if ($item->thirdays > 0) red lighten-5 @else green lighten-5 @endif tdy black-text "
                                style="display: none;">{{ $item->thirdays }}</td>
                            <td class="@if ($item->fourdays > 0) red lighten-5 @else green lighten-5 @endif fdy black-text "
                                style="display: none;">{{ $item->fourdays }}</td>
                            <td class="@if ($item->sixdays > 0) red lighten-5 @else green lighten-5 @endif sdy black-text "
                                style="display: none;">{{ $item->sixdays }}</td>
                            <td class="@if ($item->nindays > 0) red lighten-5 @else green lighten-5 @endif ndy black-text "
                                style="display: none;">{{ $item->nindays }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td id="totalrows"></td>
                        <td>Total</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script>
        function tog() {
            var name = document.getElementsByClassName('name');
            var shop = document.getElementsByClassName('shop');
            $(name).toggle();
            $(shop).toggle();
        }

        function bal() {
            $('.bal').toggle();
        }

        function tdy() {
            $('.tdy').toggle();
        }

        function fdy() {
            $('.fdy').toggle();
        }

        function sdy() {
            $('.sdy').toggle();
        }

        function ndy() {
            $('.ndy').toggle();
        }
    </script>
    <script>
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let table = document.getElementsByTagName('table');
            let tr = $('tbody tr')
            clsBtn.addEventListener("click", function() {
                a.value = '';
                a.focus();
                var filter = '';
                for (var i = 0; i < tr.length; i++) {
                    tr[i].style.display = "";
                }
                $('#cs-icon').text('search')
            });
            if (filter === '') {
                $('#cs-icon').text('search')
            } else {
                $('#cs-icon').text('close')
            }
            let sum = 0;
            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                // console.log(td);
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textvalue = td[j].textContent || td[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            sum = sum + 1;
                            break;
                        } else {
                            tr[i].style.display = "none"
                        }
                    }
                }
            }
            $('#totalrows').text(sum);
        }

        function openbs(name) {
            var type = `{{ $admin->type }}`;
            // console.log(type);
            if (type === 'marketer') {
                window.open('/marketer/balancesheet/' + name, "_self");
            } else {
                window.open('/balancesheet/' + name, "_self");
            }
        }
    </script>
@endsection
