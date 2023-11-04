@extends('admin/layout')

@section('main')
    <div>
        <div class="right" style="margin-bottom: 10px;">
            <a href="{{ url('admin/addticket') }}" class="btn amber">Add ticket</a>
        </div>
        <div class="center">
            <h5>Damage Tickets</h5>
        </div>
        <div class="row">
            <div class='input-field col s12' style="margin:0; padding: 5px;">
                <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                    autocomplete="off" type='search' id='search' />
                <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <ul class="collapsible dmg-container">
                @foreach ($data as $item)
                    <li class="dmg-inv">
                        <div class="collapsible-header row">
                            <div class="col s1">
                                <div style="height: 20px; width: 15px;" class="{{ tktcolor($item->invoiceid) }} center dmg-inf">
                                @php
                                    $no = "stat1";
                                    if($item->sendbycus != NULL)
                                    {
                                        $no = "stat1";
                                    }
                                    if ($item->recbycomp != NULL) {
                                        $no = "stat2";
                                    }
                                    if($item->sendbackbycomp != NULL){
                                        $no = "stat3";
                                    }
                                    if ($item->recbycus != NULL) {
                                        $no = "stat4";
                                    }
                                @endphp
                                {{$no}}
                                </div>
                            </div>
                            <div class="col s2 dmg-inf">{{ $item->date }}</div>
                            <div class="col s3 dmg-inf">{{ $item->name }}</div>
                            <div class="col s3 dmg-inf"><span id="{{ $item->invoiceid }}mainstat">{{ $item->mainstatus }}</span>
                            </div>
                            <div class="col s3 dmg-inf">{{ $item->invoiceid }}</div>
                        </div>
                        <div class="collapsible-body"><span>
                                <div class="right">
                                    <a href="{{ url('/ticket/' . $item->invoiceid) }}" class="btn amber">View Details</a>
                                    <a href="{{ url('/editticket/' . $item->invoiceid) }}" class="btn amber">Edit
                                        Details</a>
                                    <a href="{{ url('/deleteticket/' . $item->invoiceid) }}" class="btn red">Delete
                                    </a>
                                </div>
                                <div>

                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}sendbycus"
                                                @if ($item->sendbycus != null) checked @endif
                                                @if ($item->recbycomp != null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'sendbycus')" />
                                            <span>Send By customer
                                            </span>
                                        </label>
                                        : <span id="{{ $item->invoiceid }}sendbycuslbl"
                                            onclick="changedate('{{ $item->invoiceid }}', 'sendbycus', '{{ $item->sendbycus }}')">
                                            @if ($item->sendbycus != null)
                                                {{ $item->sendbycus }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}recbycomp"
                                                @if ($item->recbycomp != null) checked @endif
                                                @if ($item->sendbycus == null) disabled @endif
                                                @if ($item->sendbackbycomp != null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'recbycomp')" />
                                            <span>Recieved By Company
                                            </span>
                                        </label>
                                        : <span id="{{ $item->invoiceid }}recbycomplbl"
                                            onclick="changedate('{{ $item->invoiceid }}', 'recbycomp', '{{ $item->recbycomp }}')">
                                            @if ($item->recbycomp != null)
                                                {{ $item->recbycomp }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}sendbackbycomp"
                                                @if ($item->sendbackbycomp != null) checked @endif
                                                @if ($item->recbycomp == null) disabled @endif
                                                @if ($item->recbycus != null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'sendbackbycomp')" />
                                            <span>Sent Back by comp
                                            </span>
                                        </label>
                                        : <span id="{{ $item->invoiceid }}sendbackbycomplbl"
                                            onclick="changedate('{{ $item->invoiceid }}', 'sendbackbycomp', '{{ $item->sendbackbycomp }}')">
                                            @if ($item->sendbackbycomp != null)
                                                {{ $item->sendbackbycomp }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}recbycus"
                                                @if ($item->recbycus != null) checked @endif
                                                @if ($item->sendbackbycomp == null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'recbycus')" />
                                            <span>Recieved By Customer </span>
                                        </label>
                                        : <span id="{{ $item->invoiceid }}recbycuslbl"
                                            onclick="changedate('{{ $item->invoiceid }}', 'recbycus', '{{ $item->recbycus }}')">
                                            @if ($item->recbycus != null)
                                                {{ $item->recbycus }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </span></div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div id="datechange" class="modal">
        <div class="modal-content">
            <div class="center">
                Change Date for <span id="modstat"></span>
            </div>
            <form class="center" id="dcform">
                <input type="hidden" id="dateinv" name="inv">
                <input type="hidden" id="datestat" name="stat">
                <input type="datetime-local" datetime="YYYY-MM-DD hh:mm:ss" step="any" name="date" id="datedate"
                    class="browser-default inp">
                <button class="amber btn" style="margin-top: 20px;">Submit</button>
            </form>
        </div>
    </div>
    <script>
        function updatemap(invoice, stat) {
            $.ajax({
                type: 'get',
                url: '/updatemap/' + invoice + "/" + stat,
                success: function(response) {
                    $(`#${response.invoiceid+response.stat}lbl`).text(response.date)
                    if (response.mainstat != null) {
                        $(`#${response.invoiceid}mainstat`).text(response.mainstat)
                    }
                    if (response.date != null) {
                        if (response.stat == 'sendbycus') {
                            // $(`#${response.invoiceid}sendbycus`)
                            $(`#${response.invoiceid}recbycomp`).removeAttr('disabled')
                            $(`#${response.invoiceid}sendbackbycomp`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        } else if (response.stat == 'recbycomp') {
                            $(`#${response.invoiceid}sendbycus`).attr('disabled', true)
                            // $(`#${response.invoiceid}recbycomp`).removeAttr('disabled')
                            $(`#${response.invoiceid}sendbackbycomp`).removeAttr('disabled')
                            $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        } else if (response.stat == 'sendbackbycomp') {
                            $(`#${response.invoiceid}sendbycus`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycomp`).attr('disabled', true)
                            // $(`#${response.invoiceid}sendbackbycomp`).removeAttr('disabled')
                            $(`#${response.invoiceid}recbycus`).removeAttr('disabled')
                        } else if (response.stat == 'recbycus') {
                            $(`#${response.invoiceid}sendbycus`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycomp`).attr('disabled', true)
                            $(`#${response.invoiceid}sendbackbycomp`).attr('disabled', true)
                            // $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        }
                    } else {
                        if (response.stat == 'sendbycus') {
                            // $(`#${response.invoiceid}sendbycus`)
                            $(`#${response.invoiceid}recbycomp`).attr('disabled', true)
                            $(`#${response.invoiceid}sendbackbycomp`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        } else if (response.stat == 'recbycomp') {
                            $(`#${response.invoiceid}sendbycus`).removeAttr('disabled')
                            // $(`#${response.invoiceid}recbycomp`).removeAttr('disabled')
                            $(`#${response.invoiceid}sendbackbycomp`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        } else if (response.stat == 'sendbackbycomp') {
                            $(`#${response.invoiceid}sendbycus`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycomp`).removeAttr('disabled')
                            // $(`#${response.invoiceid}sendbackbycomp`).removeAttr('disabled')
                            $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        } else if (response.stat == 'recbycus') {
                            $(`#${response.invoiceid}sendbycus`).attr('disabled', true)
                            $(`#${response.invoiceid}recbycomp`).attr('disabled', true)
                            $(`#${response.invoiceid}sendbackbycomp`).removeAttr('disabled')
                            // $(`#${response.invoiceid}recbycus`).attr('disabled', true)
                        }
                    }

                }
            })
        }

        function changedate(invoice, stat, date) {
            $('#datechange').modal('open');
            $('#dateinv').val(invoice);
            $('#datestat').val(stat);
            $('#modstat').text(stat);
            $('#datedate').val(date)
        }
        $('#dcform').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData($("#dcform")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/ticket/changedate",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    console.log(response)
                    $(`#${response.inv+response.stat}lbl`).text(response.date);
                    $('#datechange').modal('close');
                }
            })
        })
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let cont = document.getElementsByClassName('dmg-container');
            let prod = $(".dmg-inv")

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
            // console.log(prod);

            for (var i = 0; i < prod.length; i++) {
                let span = prod[i].getElementsByClassName('dmg-inf');
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
            // toggleinactive();
        }
    </script>
@endsection
