@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <form action="{{route('addimg')}}" method="post" enctype="multipart/form-data" class="col s12 row">
                @csrf
                <div class="input-field col s12 m12">
                    <div class="file-field input-field">
                        <div class="btn amber darken-1 black-text">
                            <span>File</span>
                            <input id="image" type="file" name="img[]" multiple required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path" placeholder="Upload cover photo" type="text">
                        </div>
                    </div>
                </div>
                <div class="center col s12">
                    <button class="btn waves-effect waves-light amber darken-1 black-text"
                        onclick="M.toast({html: 'Adding Customer, Please wait...'})" type="submit" name="action">Submit
                        <i class="material-icons right black-text">send</i>
                    </button>
                </div>
            </form>
        </div>
        <div class="mp-card" style="margin-top: 50px">
            <table>
                <thead>
                    <th>Image</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td><img src="{{asset($item->image)}}" height="100" alt=""></td>
                            <td><a href="{{url('delete/frontimg/'.$item->image)}}" class="btn-large red white-text">Delete</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
