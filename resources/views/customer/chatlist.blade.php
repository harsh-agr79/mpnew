@extends('customer/layout')

@section('main')
    <div>
        <div class="center">
            <h5>Channel's List</h5>
        </div>
        <div class="mp-container">
            @foreach ($chatList as $item)
                @php
                    $chan = DB::table('channels')->where('shortname', $item->channel)->first();
                @endphp
                <a href="{{url('user/chatbox/'.$chan->shortname)}}" class="mp-card row valign-wrapper textcol" style="margin:5px; padding: 0;">
                    <div class="col s3 center">
                        <div style="background: {{$chan->color}}; height: 60px; width: 60px; border-radius: 50%;">
                            
                        </div>
                    </div>
                    <div class="col s9">
                        <div> <h5 style="font-size: 20px;"> {{$chan->name}} </h5></div>
                        <div style="margin-top: 0;">
                            <p style="font-size: 15px;">
                                {{$item->message}}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
            @foreach($noChatList as $item)
            <a href="{{url('user/chatbox/'.$chan->shortname)}}" class="mp-card row valign-wrapper textcol" style="margin:5px; padding: 10px;">
                <div class="col s3 center">
                    <div style="background: {{$item->color}}; height: 60px; width: 60px; border-radius: 50%;">
                        
                    </div>
                </div>
                <div class="col s9">
                    <div> <h5 style="font-size: 20px;"> {{$item->name}} </h5></div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endsection