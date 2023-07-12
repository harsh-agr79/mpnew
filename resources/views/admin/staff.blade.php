@extends('admin/layout')

@section('main')
<div>
    
    <div class="mp-card" style="margin-top: 5vh;">
        <div>
            <h5 class="center">Staff</h5>
        </div>
        <table>
            @foreach ($data as $item)
                <tr>
                    <td>{{$item->email}}</td>
                    <td><a href="{{url('/addstaff/'.$item->id)}}" class="amber lighten-2 black-text btn-small"><i class="material-icons">edit</i></a></td>
                    <td><a href="{{url('/deletestaff/'.$item->id)}}" class="red lighten-2 black-text btn-small"><i class="material-icons">delete</i></a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection