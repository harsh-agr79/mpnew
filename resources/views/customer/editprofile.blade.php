@extends('customer/layout')

@section('main')
    <div class="mp-container">
        <form action="{{route('editpr')}}" id="edpr" method="POST" enctype="multipart/form-data">
            @csrf
        <div class="row mp-card" style="margin-top: 10px;">
            <div class="col s12 center">
                <div class="center">
                    @if ($user->profileimg == '0' || $user->profileimg == '')
                    <img src="{{ asset('user.jpg') }}" class="edpr_img" alt="">
                    @else
                    <img src="{{ asset($user->profileimg) }}" class="edpr_img" alt="">
                    @endif
                    <div class="file-field center col s12 input-field">
                        <div class="btn amber">
                          <span>Change Profile pic</span>
                          <input type="file" name="dp" onchange="$('#edpr').submit()">
                        </div>
                      </div>
                </div>
            </div>
            <input type="hidden" name="olddp" value="{{$user->profileimg}}">
            <div class="col s12 row" style="margin-top: 20px;">
                <div class="col s6">Address:</div>
                <div class="col s6"><input type="text" class="browser-default inp black-text" name="address"
                        placeholder="Address" value="{{ $user->address }}"></div>
            </div>
            <div class="col s12 row">
                <div class="col s6">Shopname:</div>
                <div class="col s6"><input type="text" class="browser-default inp black-text" name="shopname"
                        placeholder="Shopname" value="{{ $user->shopname }}"></div>
            </div>
            <div class="col s12 row">
                <div class="col s6">Contact 1:</div>
                <div class="col s6"><input type="text" class="browser-default inp black-text" name="contact1"
                        placeholder="contact 1" value="{{ $user->contact }}"></div>
            </div>
            <div class="col s12 row">
                <div class="col s6">Contact 2:</div>
                <div class="col s6"><input type="text" class="browser-default inp black-text" name="contact2"
                        placeholder="contact 2" value="{{ $user->contact2 }}"></div>
            </div>
            <div class="col s12 row">
                <div class="col s6">Tax Type:</div>
                <div class="col s6"><select name="taxtype" class="browser-default selectinp black-text">
                    @if ($user->taxtype != NULL)
                        <option value="{{$user->taxtype}}">{{$user->taxtype}}</option>
                    @else
                    <option value="" selected disabled>Select PAN/VAT</option>
                    @endif
                    <option value="PAN">PAN</option>
                    <option value="PAN">VAT</option>
                </select></div>
            </div>
            <div class="col s12 row">
                <div class="col s6">PAN/VAT No. </div>
                <div class="col s6"><input type="text" class="browser-default inp black-text" name="taxnum"
                        placeholder="PAN/VAT Number" value="{{ $user->taxnum }}"></div>
            </div>
            <div class="col s12 row">
                <div class="col s6">DOB:</div>
                <div class="col s6"><input type="date" class="browser-default inp black-text" name="dob"
                        placeholder="DOB" value="{{ $user->DOB }}"></div>
            </div>
        </div>
        <div class="fixed-action-btn">
            <button class="btn btn-large red modal-trigger" style="border-radius: 10px;">
                Update
                <i class="left material-icons">send</i>
            </button>
        </div>
    </form>
    </div>
@endsection
