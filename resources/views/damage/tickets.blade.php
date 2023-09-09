@extends('admin/layout')

@section('main')
    <div>
        <div class="center">
            <h5>Damage Tickets</h5>
        </div>
        <div class="mp-card">
            <ul class="collapsible">
                @foreach ($data as $item)
                    <li>
                        <div class="collapsible-header row">
                            <div class="col s2">{{ $item->date }}</div>
                            <div class="col s3">{{ $item->name }}</div>
                            <div class="col s3">Status</div>
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
                                            <input type="checkbox" />
                                            <span>Picked Up</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Recieved By Us</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Sent By Us</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Recieved By Customer</span>
                                        </label>
                                    </div>
                                </div>
                            </span></div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
