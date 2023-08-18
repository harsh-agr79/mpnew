@extends('admin/layout')

@section('main')

    <div>
        <div class="right">
            <a href="{{url('/addbatch')}}" class="btn amber">Add Batch</a>
        </div>
        <div class="center">
            <h5>Batch List</h5>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <table>
                <thead>
                    <th>Batch</th>
                    <th>Product</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$item->batch}}</td>
                            <td>{{$item->product}}</td>
                            <td><a href="{{url('/editbatch/'.$item->id)}}" class="btn btn-small amber"><i class="material-icons">edit</i></a></td>
                            <td><a href="{{url('/deletebatch/'.$item->id)}}" class="btn btn-small red"><i class="material-icons">delete</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection