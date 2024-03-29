@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="center">
                <h5>Problem</h5>
            </div>
            <form action="{{ route('addproblem') }}" method="POST" class="row">
                @csrf
                <div class="col s12 m6">
                    <input placeholder="Problem" type="text" class="validate demo-input inp browser-default black-text"
                        value="{{ $problem }}" name="problem" required>
                </div>
                <div class="col s12 m6">
                    <select id="select1" name="category" class="browser-default selectinp black-text">
                        @if ($category != null)
                            <option selected value="{{ $category_id }}">{{ $category }}</option>
                            <option class="black-text" value="">Select Category</option>
                        @else
                            <option class="black-text" value="" selected disabled>Select Category</option>
                        @endif
                        @foreach ($categories as $cats)
                            <option value="{{ $cats->id }}">{{ $cats->category }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="col s12 center" style="margin-top: 20px;">
                    <button class="btn amber darken-2">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
