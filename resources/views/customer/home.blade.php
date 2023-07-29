@extends('customer/layout')

@section('main')
    <div class="row" style="padding: 0; margin: 0;">
        <div class="col l6 m12 s12 amber" style="height: 35vh; padding: 0; margin: 0;">
            <h4 class="center">Carousel</h4>
        </div>
        <div class="col l6 m12 s12 row center" style="margin-top: 20px;">
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{url('user/createorder')}}" class="home-btn spc">Create A New Order<i class="material-icons">add</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="" class="home-btn">Previous Orders<i class="material-icons">shopping_basket</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="" class="home-btn">Saved Orders<i class="material-icons">save</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{url('user/analytics')}}" class="home-btn">Analytics<i class="material-icons">equalizer</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{url('user/summary')}}" class="home-btn">Summary <i class="material-icons">multiline_chart</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{url('user/statement')}}" class="home-btn">Statement <i class="material-icons">web</i></a>
            </div>
        </div>
    </div>
@endsection