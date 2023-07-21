@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Add Customer</h6>
            </div>
            <form action="{{ route('addcustomer') }}" method="POST">
                @csrf
                <div class="row">


                    <div class="col s12 row">
                        <div class="col s6">
                            Name:
                        </div>
                        <div class="col s6">
                            <input type="text" name="name" value="{{ $name }}"
                                class="inp black-text browser-default" placeholder="Name" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Shop-Name:
                        </div>
                        <div class="col s6">
                            <input type="text" name="shopname" value="{{ $shopname }}"
                                class="inp black-text browser-default" placeholder="Shop-name" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        @error('userid')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                        <div class="col s6">
                            User ID:
                        </div>
                        <div class="col s6">
                            <input type="text" name="userid" value="{{ $userid }}"
                                class="inp black-text browser-default" placeholder="User Id" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        @error('contact')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                        <div class="col s6">
                            Contact:
                        </div>
                        <div class="col s6">
                            <input type="text" name="contact" value="{{ $contact }}"
                                class="inp black-text browser-default" placeholder="contact" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Address:
                        </div>
                        <div class="col s6">
                            <input type="text" name="address" value="{{ $address }}"
                                class="inp black-text browser-default" placeholder="Address" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        @error('uniqueid')
                            <div class="red-text">{{ $message }}</div>
                        @enderror
                        <div class="col s6">
                            Unique Id:
                        </div>
                        <div class="col s6">
                            <input type="text" name="uniqueid" value="{{ $uniqueid }}"
                                class="inp black-text browser-default" placeholder="Unique Id" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Referer:
                        </div>
                        <div class="col s6">
                            <input type="text" name="referer" value="{{ $refname }}"
                                class="inp black-text browser-default" placeholder="referer">
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6" style="margin-top:20px;">
                            Password:
                        </div>
                        <div class='input-field col s6'>
                            <input class='validate browser-default inp black-text' value="{{ $password }}"
                                placeholder="password" type='password' name='password' id='password' required />
                            <span toggle="#password" class="field-icon toggle-password"><span
                                    class="material-icons black-text">visibility</span></span>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Open Balance:
                        </div>
                        <div class="col s6">
                            <input type="text" name="openbalance" value="{{ $openbalance }}"
                                class="inp black-text browser-default" placeholder="openbalance">
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Open-balance Type:
                        </div>
                        <div class="col s6">
                            <select id="select1" name="obtype" class="browser-default selectinp black-text">
                                @if ($obtype != null)
                                    <option selected value="{{ $obtype }}">{{ $obtype }}</option>
                                    <option class="black-text" value="">Open Balance Type</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Open Balance Type</option>
                                @endif
                                <option class="black-text" value="debit">Debit</option>
                                <option class="black-text" value="credit">Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Type:
                        </div>
                        <div class="col s6">
                            <select id="select1" name="type" class="browser-default selectinp black-text" required>
                                @if ($type != null)
                                    <option selected value="{{ $type }}">{{ $type }}</option>
                                    <option class="black-text" value="">Customer Type</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Customer Type</option>
                                @endif
                                <option class="black-text" value="dealer">Dealer</option>
                                <option class="black-text" value="wholesaler">wholesaler</option>
                                <option class="black-text" value="retailer">Retailer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Website From:
                        </div>
                        <div class="col s6">
                            <select id="select1" name="from" class="browser-default selectinp black-text">
                                @if ($from != null)
                                    <option selected value="{{ $from }}">{{ $from }}</option>
                                    <option class="black-text" value="">Website From</option>
                                @else
                                    <option class="black-text" value="" selected disabled>Website From</option>
                                @endif
                                <option class="black-text" value="mpe">mpe</option>
                                <option class="black-text" value="dealer">Dealer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{ $id }}">

                <div class="fixed-action-btn">
                    <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                        style="border-radius: 10px;">
                        Submit
                        <i class="left material-icons">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection