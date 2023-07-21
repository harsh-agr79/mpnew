@extends('admin/layout')

@section('main')
    <div>
        <div>
            <div class="row">
                <div class='input-field col l6 m6 s12'>
                    <input class='validate browser-default inp search black-text' onkeyup="searchFun()" autocomplete="off"
                        type='search' name='search' id='search' />
                    <span class="field-icon" id="close-search"><span class="material-icons"
                            id="cs-icon">search</span></span>
                </div>
                <div class="input-field col s12 m6 l6">
                    <select multiple onchange="fieldsfilter()" id="fields">
                      <option value="" disabled>Select Fields</option>
                      <option value="contact">Contact</option>
                      <option value="userid">User Id</option>
                      <option value="referer">Referer</option>
                      <option value="uniqueid">Unique Id</option>
                    </select>
                  </div>
            </div>
        </div>
        <div class="mp-card"  style="overflow-x: scroll;">
            <table class="sortable">
                <thead>
                    <th>SN</th>
                    <th>Name</th>
                    <th>shop</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th class="contact" style="display: none;">Contact</th>
                    <th class="userid" style="display: none;">User id</th>
                    <th class="referer" style="display: none;">referer</th>
                    <th class="uniqueid" style="display: none;">Unique Id</th>
                </thead>
                <tbody>
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($data as $item)
                        <tr  oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td>{{$a = $a + 1}}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->shopname }}</td>
                            <td>{{ $item->address }}</td>
                            <td>{{ $item->type }}</td>
                            <td class="contact" style="display: none;">{{$item->contact}}</td>
                            <td class="userid" style="display: none;">{{$item->user_id}}</td>
                            <td class="referer" style="display: none;">{{$item->refname}}</td>
                            <td class="uniqueid" style="display: none;">{{$item->cusuni_id}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink">
                <li class="border-top">Delete</li>
            </a>
        </ul>
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
    <script>
        function fieldsfilter(){
            $('.contact').hide();
            $('.userid').hide();
            $('.referer').hide();
            $('.uniqueid').hide();
            var clsnames = '';
            const vals = $('#fields').val();
            vals.forEach(e => {
                $(`.${e}`).show();
            });
        }
    </script>
    <script>
           function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
            var perms = @json($perms);
            var admintype = `{{ $admin->type }}`;
            if (admintype == "admin") {
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', '/editcustomer/' + id);
                $('#rmdeletelink').attr('href', '/deletecustomer/' + id);
            }
        }

        $(document).bind("click", function(event) {
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'none';
        });

        function mouseX(evt) {
            if (evt.pageX) {
                return evt.pageX;
            } else if (evt.clientX) {
                return evt.clientX + (document.documentElement.scrollLeft ?
                    document.documentElement.scrollLeft :
                    document.body.scrollLeft);
            } else {
                return null;
            }
        }

        // Set Top Style Proparty
        function mouseY(evt) {
            if (evt.pageY) {
                return evt.pageY;
            } else if (evt.clientY) {
                return evt.clientY + (document.documentElement.scrollTop ?
                    document.documentElement.scrollTop :
                    document.body.scrollTop);
            } else {
                return null;
            }
        }
    </script>
@endsection
