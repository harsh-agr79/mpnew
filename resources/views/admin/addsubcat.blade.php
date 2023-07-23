@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="center">
                <h5>Subcategory</h5>
            </div>
            <form action="{{ route('addsub') }}" method="POST" class="row">
                @csrf
                <div class="col s12 m6">
                    <input placeholder="Sub-category" value="{{ $subcategory }}" type="text"
                        class="validate demo-input inp browser-default black-text" name="subcategory">
                </div>
                <input type="hidden" name="subcategory_old" value="{{ $subcategory }}">
                <div class="col s12 m6">
                    <select name="category" class="browser-default selectinp" required>
                        @if ($parent > 0)
                            <option value="{{ $parent }}" selected>{{ $parent }}</option>
                        @else
                            <option value="" disabled selected>Choose your option</option>
                        @endif
                        <option value="powerbank">powerbank</option>
                        <option value="charger">charger</option>
                        <option value="cable">cable</option>
                        <option value="btitem">Bluetooth Item</option>
                        <option value="earphone">earphones</option>
                        <option value="others">others</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="col s12 center" style="margin-top: 20px;">
                    <button class="btn amber darken-2">ADD</button>
                </div>
            </form>
        </div>
    </div>
@endsection
