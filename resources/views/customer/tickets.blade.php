@extends('customer/layout')

@section('main')
    <div>
        <div class="center">
            <h5>Damage tickets</h5>
        </div>
        <div class="mp-card mp-container">

            <ul class="collapsible">
                @foreach ($data as $item)
                    <li>
                        <div class="collapsible-header row">
                            <div class="col s1">
                                <div style="height: 20px; width: 10px;" class="{{ tktcolor($item->invoiceid) }}"></div>
                            </div>
                            <div class="col s3">
                                {{ $item->date }}
                            </div>
                            <div class="col s3">
                                {{ $item->invoiceid }}
                            </div>
                            <div class="col s3">
                                {{ $item->mainstatus }}
                            </div>
                            <div class="col s2">
                                Details
                            </div>
                        </div>
                        <div class="collapsible-body"><span>
                            <div class="right">
                                <div class="right">
                                    <a href="{{url('/user/ticket/'.$item->invoiceid)}}" class="btn amber">View Details</a>
                                    <a href="{{url('/user/editticket/'.$item->invoiceid)}}" class="btn amber editbtn{{$item->invoiceid}}" 
                                        @if($item->sendbycus != NULL)
                                        disabled
                                        @endif
                                        >Edit</a>
                                    <a href="{{url('/user/deleteticket/'.$item->invoiceid)}}" class="btn red editbtn{{$item->invoiceid}}" 
                                            @if($item->sendbycus != NULL)
                                            disabled
                                            @endif
                                            >Delete</a>
                                </div>
                            </div>
                                <div>
                                    <label>
                                        <input type="checkbox"
                                        @if ($item->sendbycus != NULL)
                                                checked
                                            @endif
                                        @if($item->recbycomp != NULL)
                                            disabled
                                        @endif
                                        onclick="statuschange('{{$item->invoiceid}}', 'sendbycus')"/>
                                        <span>Sent By Me: <span id="{{$item->invoiceid}}sendbycuslbl">{{$item->sendbycus}}</span></span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" @if ($item->recbycomp != NULL)
                                        checked
                                    @endif disabled />
                                        <span>Recieved By the Company : {{$item->recbycomp}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" @if ($item->sendbackbycomp != NULL)
                                        checked
                                    @endif disabled />
                                        <span>Sent By The Company : {{$item->sendbackbycomp}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" @if ($item->recbycus != NULL)
                                        checked
                                    @endif 
                                    @if($item->sendbackbycomp == NULL)
                                    disabled
                                    @endif
                                    onclick="statuschange('{{$item->invoiceid}}', 'recbycus')"/>
                                        <span>Recieved By Me <span id="{{$item->invoiceid}}recbycuslbl">{{$item->recbycus}}</span></span>
                                    </label>
                                </div>
                            </span></div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

    <script>
        function statuschange(invoice, stat)
        {
            $.ajax({
                    type: 'get',
                    url: '/user/updatestat/'+invoice+"/"+stat,
                    success: function(response) {
                       $(`#${response.invoiceid+response.stat}lbl`).text(response.date)
                       if(response.date == null){
                        $(`.editbtn${response.invoiceid}`).removeAttr('disabled')
                       }
                       else{
                        $(`.editbtn${response.invoiceid}`).attr('disabled', true)
                       }
                    // console.log(response);
                    }
                })
        }
    </script>
@endsection
