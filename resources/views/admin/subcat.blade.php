@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <div class="center">
                <h3>Subcategory</h3>
            </div>
            <table>
                <thead>
                    <th>Subcategory</th>
                    <th>Parent Category</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$item->subcategory}}</td>
                            <td>{{$item->parent}}</td>
                            <td><a href="{{url('addsubcategory/'.$item->id)}}" class="btn-small amber white-text"><i class="material-icons">edit</i></a></td>
                            <td><a href="{{url('deletesubcategory/'. $item->id)}}" class="btn-small red white-text"><i class="material-icons">delete</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection