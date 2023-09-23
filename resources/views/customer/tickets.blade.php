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
                            <div class="col s3">
                                {{ $item->date }}
                            </div>
                            <div class="col s3">
                                {{ $item->invoiceid }}
                            </div>
                            <div class="col s3">
                                {{ $item->mainstatus }}
                            </div>
                            <div class="col s3">
                                Details
                            </div>
                        </div>
                        <div class="collapsible-body"><span>
                            <div class="right">
                                <div class="right">
                                    <a href="{{url('/user/ticket/'.$item->invoiceid)}}" class="btn amber">View Details</a>
                                    <a href="{{url('/user/editticket/'.$item->invoiceid)}}" class="btn amber">Edit</a>
                                </div>
                            </div>
                                <div>
                                    <label>
                                        <input type="checkbox" onclick="statuschange('{{$item->invoiceid}}', 'sendbycus')"/>
                                        <span>Sent By Me: <span id="{{$item->invoiceid}}sendbycuslbl"></span></span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" disabled />
                                        <span>Recieved By the Company</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" disabled />
                                        <span>Sent By The Company</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" onclick="statuschange('{{$item->invoiceid}}', 'recbycus')"/>
                                        <span>Recieved By Me <span id="{{$item->invoiceid}}recbycuslbl"></span></span>
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
                    // console.log(response);
                    }
                })
        }
    </script>
@endsection
