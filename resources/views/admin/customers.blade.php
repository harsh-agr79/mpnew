@extends('admin/layout')

@section('main')
    <div>
        <div>
            <div class="row">
                <div class='input-field col l6 m6 s12'>
                    <input class='validate browser-default inp search black-text' onkeyup="searchFun()" autocomplete="off"
                        type='search' name='search' id='search' />
                    <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
                </div>
            </div>
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <th>Name</th>
                    <th>shop</th>
                    <th>Address</th>
                    <th>Type</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->shopname}}</td>
                            <td>{{$item->address}}</td>
                            <td>{{$item->type}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection