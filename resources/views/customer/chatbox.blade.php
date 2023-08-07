@extends('customer/layout')

@section('main')
    <div style="margin: 0; padding: 0;">
        <div class="row user-chat-header valign-wrapper" style="margin: 0">
            <div class="col s3">
                <div style="background: {{$chan->color}}; height: 40px; width: 40px; border-radius: 50%;">           
                </div>
            </div>
            <div class="col s9">
                <h5 style="font-size: 20px;">{{$chan->name}}</h5>
            </div>
        </div>
        <div class="row user-chat-box" style="margin: 0; padding: 0;">
            <div class="col s12">

            </div>
        </div>
        <div class="user-chat-messageinp center" style="padding: 0; margin: 0;">
            <div style="margin:0; padding: 0;">
                <a class="btn-flat">
                    <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                        image
                    </i>
                </a>
            </div>
            <div style="margin:0; padding: 0; width: 70vw;">
                <input type="text" class="browser-default msginp" id="msgval" name="message"
                    placeholder="Type Message...">
            </div>
            <div style="margin:0; padding: 0;">
                <button class="btn-flat">
                    <i class="material-symbols-outlined textcol" style="font-size: 30px;">
                        send
                    </i>
                </button>
            </div>
        </div>
    </div>
@endsection