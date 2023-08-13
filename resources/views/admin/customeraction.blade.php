@extends('admin/layout')

@section('main')
@php
    $a = 0;
@endphp
    <div>
        <div class="mp-card" style="margin-top: 10px">
            <div>
                <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
                    type='search' id='search' />
                <span class="field-icon" id="close-search"><span class="material-icons"
                        id="cs-icon">search</span></span>
            </div></div>
        <div class="mp-card" style="margin-top: 10px;">
            <table class="sortable">
                <thead>
                    <th>SN</th>
                    <th>DP</th>
                    <th>Name</th>
                    <th>Login</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$a = $a + 1}}</td>
                            <td>
                                @if ($item->profileimg == NULL)
                                    <img src="{{asset('user.jpg')}}" style="height: 50px; border-radius: 50%"  alt="">
                                @else
                                    <img src="{{asset($item->profileimg)}}" style="height: 50; border-radius: 50%"  alt="">
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td><a href="{{ url('directlogin/customer/' . $item->id) }}" class="btn-small amber textcol"><i
                                        class="material-symbols-outlined textcol">
                                        login
                                    </i></a></td>
                            <td><a href="{{ url('editcustomer/' . $item->id) }}" class="btn-small amber textcol"><i
                                        class="material-symbols-outlined textcol">
                                        edit
                                    </i></a></td>
                            <td><a href="{{ url('deletecustomer/' . $item->id) }}" class="btn-small red textcol"><i
                                        class="material-symbols-outlined textcol">
                                        delete
                                    </i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
         const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let table = document.getElementsByTagName('table');
            let tr = $('tr')
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

            for (var i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                // console.log(td);
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textvalue = td[j].textContent || td[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none"
                        }
                    }
                }
            }
        }
    </script>
@endsection
