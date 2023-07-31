@extends('admin/layout')

@section('main')
    <div>
        <div class="center">
            {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <th></th>
                    <th>Name</th>
                    <th>Order id</th>
                    <th><label>
                        <input type="checkbox" id="selectall" onchange="toggleall()"/>
                        <span style="font-size: 10px;">Select All</span>
                      </label></th>
                </thead>
                <form action="{{route('bulkprint')}}" method="POST">
                    @csrf
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>
                                <div id="{{ $item->orderid . 'order' }}" class="{{$item->mainstatus}}"
                                    style="height: 35px; width:10px;"></div>
                            <td>{{$item->name}}</td>
                            <td>{{$item->orderid}}</td>
                            <td><label>
                                <input type="checkbox" class="bprint" name="orderid[]" value="{{$item->orderid}}" />
                                <span></span>
                              </label></td>
                        </tr>
                    @endforeach
                </tbody>
                <div class="fixed-action-btn">
                    <button class="btn btn-large red">
                        Print Orders
                      <i class="left material-icons">send</i>
                    </button>
                </div>
            </form>
            </table>
        </div>
        <div class="center">
            {{ $data->appends(\Request::except('page'))->links('vendor.pagination.materializecss') }}
        </div>
    </div>
    <script>
        function toggleall(){
            if($('#selectall').is(':checked')){
                $('.bprint').attr('checked', 'true')
            }
            else{
                $('.bprint').removeAttr('checked')
            }
        }
    </script>
@endsection