@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="center">
                <h5>Part</h5>
            </div>
            <form action="{{ route('addpart') }}" method="POST" enctype="multipart/form-data" class="row">
                @csrf
                <div class="input-field col s12 m6">
                    <input placeholder="Part" type="text" class="validate demo-input inp browser-default black-text"
                        value="{{ $name }}" name="name" required>
                </div>
                <div class="input-field col s12 m6">
                    <select id="MySelct" name="product[]" searchname="myselectsearch" searchable="Select Product" multiple
                        required>
                        {{-- @if ($prod != null)
                        <option value="{{$prod}}" selected >{{$prod}}</option>
                        <option value="" disabled>Select Product</option>
                    @else
                        <option value="" selected disabled>Select Product</option>
                    @endif --}}
                        <option value="" disabled>Select Product</option>
                        @if ($prod != '')
                            @foreach (explode('|', $prod) as $item)
                                <option value="{{ $item }}" selected>{{ $item }}</option>
                            @endforeach
                        @endif

                        @foreach ($product as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-field col s12 m12">
                    <div class="file-field input-field">
                        <div class="btn amber darken-1 black-text">
                            <span>File</span>
                            <input id="image" type="file" name="image" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path" placeholder="Upload a photo" type="text">
                            @if ($image != '')
                                <img width="100px" src="{{ asset($image) }}" />
                            @endif
                        </div>
                    </div>
                </div>
                <input type="hidden" name="oldimage" value="{{ $image }}">
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="col s12 center" style="margin-top: 20px;">
                    <button class="btn amber darken-2">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
