@extends('admin/layout')

@section('main')

    <div>
        <div class="right">
            <a href="{{url('/addpart')}}" class="btn amber">Add Part</a>
        </div>
        <div class="center">
            <h5>Parts List</h5>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <table class="sortable">
                <thead>
                    <th></th>
                    <th>name</th>
                    <th>Products</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>
                                <img src="{{asset($item->image)}}" height="40" class="materialboxed" alt="">
                            </td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->product}}</td>
                            <td><a href="{{url('/editpart/'.$item->id)}}" class="btn btn-small amber"><i class="material-icons">edit</i></a></td>
                            <td><a href="{{url('/deletepart/'.$item->id)}}" class="btn btn-small red"><i class="material-icons">delete</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection