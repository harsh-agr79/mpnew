@extends('admin/layout')

@section('main')
<div>
    <div class="mp-card" style="margin-top: 20px;">
        <div class="center">
            <h5>Batch</h5>
        </div>
        <form action="{{ route('addbatch') }}" method="POST" class="row">
            @csrf
            <div class="input-field col s12 m6">
                <input placeholder="Batch" type="text"
                    class="validate demo-input inp browser-default black-text" value="{{$batch}}" name="batch" required>
            </div>
            <div class="input-field col s12 m6">
                <select id="MySelct" name="product" searchname="myselectsearch" searchable="Select Product" required>
                    @if ($prod != NULL)
                        <option value="{{$prod}}" selected >{{$prod}}</option>
                        <option value="" disabled>Select Product</option>
                    @else
                        <option value="" selected disabled>Select Product</option>
                    @endif
                    
                    @foreach ($product as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col s12 center" style="margin-top: 20px;">
                <button class="btn amber darken-2">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection