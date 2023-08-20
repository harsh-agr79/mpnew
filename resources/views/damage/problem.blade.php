@extends('admin/layout')

@section('main')

    <div>
        <div class="right">
            <a href="{{url('/addproblem')}}" class="btn amber">Add Problem</a>
        </div>
        <div class="center">
            <h5>Problem List</h5>
        </div>
        <div class="mp-card" style="margin-top: 20px;">
            <table class="sortable">
                <thead>
                    <th>Problem</th>
                    <th>Category</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$item->problem}}</td>
                            <td>{{$item->category}}</td>
                            <td><a href="{{url('/editproblem/'.$item->id)}}" class="btn btn-small amber"><i class="material-icons">edit</i></a></td>
                            <td><a href="{{url('/deleteproblem/'.$item->id)}}" class="btn btn-small red"><i class="material-icons">delete</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection