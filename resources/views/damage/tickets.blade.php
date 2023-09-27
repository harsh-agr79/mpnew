@extends('admin/layout')

@section('main')
    <div>
        <div class="right" style="margin-bottom: 10px;">
            <a href="{{ url('admin/addticket') }}" class="btn amber">Add ticket</a>
        </div>
        <div class="center">
            <h5>Damage Tickets</h5>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <ul class="collapsible">
                @foreach ($data as $item)
                    <li>
                        <div class="collapsible-header row">
                            <div class="col s2">{{ $item->date }}</div>
                            <div class="col s3">{{ $item->name }}</div>
                            <div class="col s3"><span id="{{$item->invoiceid}}mainstat">{{ $item->mainstatus }}</span></div>
                            <div class="col s3">{{ $item->invoiceid }}</div>
                        </div>
                        <div class="collapsible-body"><span>
                                <div class="right">
                                    <a href="{{ url('/ticket/' . $item->invoiceid) }}" class="btn amber">View Details</a>
                                    <a href="{{ url('/editticket/' . $item->invoiceid) }}" class="btn amber">Edit
                                        Details</a>
                                </div>
                                <div>

                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}sendbycus"
                                                @if ($item->sendbycus != null) checked @endif
                                                @if ($item->recbycomp != null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'sendbycus')" />
                                            <span>Send By customer : <span id="{{ $item->invoiceid }}sendbycuslbl">
                                                    @if ($item->sendbycus != null)
                                                        {{ $item->sendbycus }}
                                                    @endif
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}recbycomp"
                                                @if ($item->recbycomp != null) checked @endif
                                                @if ($item->sendbycus == null) disabled @endif
                                                @if ($item->sendbackbycomp != null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'recbycomp')" />
                                            <span>Recieved By Company : <span id="{{ $item->invoiceid }}recbycomplbl">
                                                    @if ($item->recbycomp != null)
                                                        {{ $item->recbycomp }}
                                                    @endif
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}sendbackbycomp"
                                                @if ($item->sendbackbycomp != null) checked @endif
                                                @if ($item->recbycomp == null) disabled @endif
                                                @if ($item->recbycus != null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'sendbackbycomp')" />
                                            <span>Sent Back by comp : <span id="{{ $item->invoiceid }}sendbackbycomplbl">
                                                    @if ($item->sendbackbycomp != null)
                                                        {{ $item->sendbackbycomp }}
                                                    @endif
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{ $item->invoiceid }}recbycus"
                                                @if ($item->recbycus != null) checked @endif
                                                @if ($item->sendbackbycomp == null) disabled @endif
                                                onclick="updatemap('{{ $item->invoiceid }}', 'recbycus')" />
                                            <span>Recieved By Customer : <span id="{{ $item->invoiceid }}recbycuslbl">
                                                    @if ($item->recbycus != null)
                                                        {{ $item->recbycus }}
                                                    @endif
                                                </span></span>
                                        </label>
                                    </div>
                                </div>
                            </span></div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <script>
        function updatemap(invoice, stat) {
            $.ajax({
                type: 'get',
                url: '/updatemap/' + invoice + "/" + stat,
                success: function(response) {
                    $(`#${response.invoiceid+response.stat}lbl`).text(response.date)
                    if(response.mainstat != null){
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
                    }
                    else{
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
    </script>
@endsection
