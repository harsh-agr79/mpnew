@extends('customer/layout')

@section('main')
    <div class="row" style="padding: 0; margin: 0;">
        <div class="col l6 m12 s12" style="padding: 0; margin: 0;">
            <div class="mp-caro-cont">
                @for ($i = 0; $i < count($data); $i++)
                    <div class="mp-caro-item valign-wrapper @if ($i != 0) hide @endif"
                        style="background: url('{{ asset('/storage/media/' . $data[$i]->image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; ">
                        <div style="width: 100vw;">
                            <div class="btn-floating left"
                                style="margin: 5px; background: rgba(0, 0, 0, 0.219); border-radius: 50%" onclick="prev()">
                                <i class="material-icons white-text center">arrow_back</i>
                            </div>
                            <div class="btn-floating right"
                                style="margin: 5px; background: rgba(0, 0, 0, 0.219); border-radius: 50%" onclick="next()">
                                <i class="material-icons white-text center">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="scroll-text">
                <section class="news-message">
                    @foreach ($data2 as $item)
                        <p>{{ $item->message }}</p>
                    @endforeach
                </section>
                <section class="news-message bg-content">
                    @foreach ($data2 as $item)
                        <p>{{ $item->message }}</p>
                    @endforeach
                </section>
            </div>
        </div>
        <div class="col l6 m12 s12 row center" style="margin-top: 5px;">
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/createorder') }}" class="home-btn spc">Create A New Order<i
                        class="material-icons">add</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/oldorders') }}" class="home-btn">Previous Orders<i
                        class="material-icons">shopping_basket</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/savedorders') }}" class="home-btn">Saved Orders<i class="material-icons">save</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/analytics') }}" class="home-btn">Analytics<i class="material-icons">equalizer</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/summary') }}" class="home-btn">Summary <i
                        class="material-icons">multiline_chart</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/statement') }}" class="home-btn">Statement <i class="material-icons">web</i></a>
            </div>
        </div>
    </div>
@endsection
