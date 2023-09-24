@extends('admin/layout')

@section('main')
    <div>
        <div class="right" style="margin-bottom: 10px;">
            <a href="{{url('admin/addticket')}}" class="btn amber">Add ticket</a>
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
                            <div class="col s3">{{$item->mainstatus}}</div>
                            <div class="col s3">{{ $item->invoiceid }}</div>
                        </div>
                        <div class="collapsible-body"><span>
                            <div class="right">
                                <a href="{{url('/ticket/'.$item->invoiceid)}}" class="btn amber">View Details</a>
                                <a href="{{url('/editticket/'.$item->invoiceid)}}" class="btn amber">Edit Details</a>
                            </div>
                                <div>

                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{$item->invoiceid}}sendbycus" @if ($item->sendbycus != NULL)
                                                checked
                                            @endif onclick="updatemap('{{$item->invoiceid}}', 'sendbycus')"/>
                                            <span>Send By customer : <span id="{{$item->invoiceid}}sendbycuslbl">
                                                @if ($item->sendbycus != NULL)
                                                {{$item->sendbycus}}
                                            @endif</span></span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{$item->invoiceid}}recbycomp" @if ($item->recbycomp != NULL)
                                            checked
                                        @endif onclick="updatemap('{{$item->invoiceid}}', 'recbycomp')"/>
                                            <span>Recieved By Company : <span id="{{$item->invoiceid}}recbycomplbl">
                                                @if ($item->recbycomp != NULL)
                                                {{$item->recbycomp}}
                                            @endif</span></span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{$item->invoiceid}}sendbackbycomp" @if ($item->sendbackbycomp != NULL)
                                            checked
                                        @endif onclick="updatemap('{{$item->invoiceid}}', 'sendbackbycomp')"/>
                                            <span>Sent Back by comp : <span id="{{$item->invoiceid}}sendbackbycomplbl">
                                                @if ($item->sendbackbycomp != NULL)
                                                {{$item->sendbackbycomp}}
                                            @endif</span></span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" id="{{$item->invoiceid}}recbycus" @if ($item->recbycus != NULL)
                                            checked
                                        @endif onclick="updatemap('{{$item->invoiceid}}', 'recbycus')"/>
                                            <span>Recieved By Customer : <span id="{{$item->invoiceid}}recbycuslbl">
                                                @if ($item->recbycus != NULL)
                                                {{$item->recbycus}}
                                            @endif</span></span>
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
        function updatemap(invoice, stat){
            $.ajax({
                    type: 'get',
                    url: '/updatemap/'+invoice+"/"+stat,
                    success: function(response) {
                       $(`#${response.invoiceid+response.stat}lbl`).text(response.date)
                    }
                })
        }
    </script>
@endsection
