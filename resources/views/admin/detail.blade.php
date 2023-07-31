@extends('admin/layout')

@section('main')
    <style>
        h6{
            font-size:15px; 
        }
    </style>
    @php
        $total = 0;
        $cus = DB::table('customers')->where('name', $data[0]->name)->first();
        if(in_array('detailupdate', $perms) || $admin->type == 'admin'){
            $dis = '';
        }
        else{
            $dis = 'disabled';
        }
    @endphp
    <div>
       
        <div class="right">
            <a class="btn-flat dropdown-trigger" data-target="menu">
                <i class="material-icons">more_vert</i>
            </a>
            <ul id='menu' class='dropdown-content'>
                <li><a href="{{url('editorder/'.$data[0]->orderid)}}">Edit</a></li>
                <li><a href="{{url('deleteorder/'.$data[0]->orderid)}}">Delete</a></li>
              </ul>
        </div>
        <div class="right" style="margin-left: 10px;">
            <a onclick="printorder('{{$data[0]->orderid}}');" target="_blank" class="btn-small amber white-text">
            Image
            </a>
        </div>
        <div class="right">
            <a href ="{{url('printorder/'.$data[0]->orderid)}}" target="_blank" class="btn-small amber white-text">
            PDF
            </a>
        </div>
        <div style="font-size: 10px;">
            <h6>Customer: {{$data[0]->name}}</h6>
            <h6>Shop Name: {{$cus->shopname}}</h6>
            <h6>orderid: {{$data[0]->orderid}}</h6>
            <h6>Date: {{date('Y-m-d', strtotime($data[0]->created_at))}}</h6>
            <h6>Miti: {{getNepaliDate($data[0]->created_at)}}</h6>
          
            @if($cus->refname != NULL)
            <h6>Referer: {{$cus->refname}}</h6>
            @endif
        </div>
        <div class="hide">
            <form id="seenupdate">
                <input type="hidden" value="{{$data[0]->orderid}}" name="orderid">
                <input type="hidden" value="{{$admin->email}}" name="admin">
            </form>
        </div>


<form action="{{route('detailupdate')}}" method="POST">
    @csrf
        <div class="mp-card" style="overflow-x: scroll">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th class="center">Ordered Quantity</th>
                        <th class="center">Approved Quantity</th>
                        <th class="center">Price</th>
                        <th>
                            <label>Status</label><select id="select1" class="browser-default selectinp black-text" {{$dis}} style="width: 85px;">
                              <option class="black-text" value="" selected disabled>for all</option>
                              <option class="black-text" value="pending">Pending</option>
                              <option class="black-text" value="approved">approved</option>
                              <option class="black-text" value="rejected">rejected</option>
                          </select></th>
                        <th>total</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($data as $item)
                    <input type="hidden" value="{{$item->id}}" name="id[]">
                    <input type="hidden" value="{{$item->quantity}}" name="quantity[]">
                        <tr>
                            <td @if($item->stock == 'on') style="text-decoration: underline solid red 25%;" @endif>{{$item->item}}</td>
                            <td class="center">{{$item->quantity}}</td>
                            <td class="center">
                                <span class="amber lighten-2 black-text" style="padding: 10px;"  @if ($dis == '')
                                onclick="this.remove(); $('#{{$item->id}}ap').css('display', 'block');" @endif>{{$item->approvedquantity}}</span>
                                <input id="{{$item->id}}ap" type="text" class="inp browser-default black-text" style="display: none;" name="apquantity[]" value="{{$item->approvedquantity}}"></td>
                            </td>
                            <td class="center">
                                <span class="amber lighten-2 black-text center" style="padding: 10px;" @if ($dis == '') onclick="this.remove(); $('#{{$item->id}}').css('display', 'block');" @endif>{{$item->price}}</span>
                                <input id="{{$item->id}}" type="text" class="inp browser-default black-text" style="display: none;" name="price[]" value="{{$item->price}}"></td>
                            <td>
                                <select name="status[]" class="select2 browser-default selectinp black-text" {{$dis}} style="width: 85px;" required>
                                    @if ($item->status == 'pending')
                                      <option class="black-text" value="pending" class="" selected>{{$item->status}}</option>
                                      @else
                                      <option class="black-text" value="{{$item->status}}" class="" selected>{{$item->status}}</option>
                                      <option class="black-text" value="pending">Pending</option>
                                    @endif
                                    <option class="black-text" value="approved">approved</option>
                                    <option class="black-text" value="rejected">rejected</option>
                                  </select>
                            </td>
                            <td>
                                @if ($item->status == 'approved')
                                    {{$a = $item->approvedquantity * $item->price}}
                                    <span class="hide">{{$total = $total + $a}}</span>
                                @elseif($item->status == 'pending')
                                    {{$a = $item->quantity*$item->price}}
                                    <span class="hide">{{$total = $total + $a}}</span>
                                @else
                                    0
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 700">Total</td>
                        <td style="font-weight: 700">{{$total}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 700">Discount</td>
                        <td><input type="text" {{$dis}} name="discount" value="{{$data[0]->discount}}"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 700">Net Total</td>
                        <td style="font-weight: 700">{{$total - $total * 0.01 * $data[0]->discount}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mp-card" style="margin-top: 30px;">
            <h6>User Remarks: {{$data['0']->userremarks}}</h6>
        </div>
        <div class="bg-content mp-card" style="margin-top:30px;">
            <div class="input-field col s12">
              <textarea id="textarea1" name="remarks" {{$dis}} placeholder="Remarks" class="materialize-textarea">{{$data['0']->remarks}}</textarea>
            </div>
            <div class="input-field col s12">
              <input type="number" pattern="[0-9]*" {{$dis}} value="{{$data['0']->cartoons}}" inputmode="numeric" name="cartoons" placeholder="Number of Cartoons" class="validate">
            </div>
            <div class="input-field col s12">
              <textarea id="textarea2" name="transport" {{$dis}} placeholder="Transportation Details" class="materialize-textarea">{{$data['0']->transport}}</textarea>
            </div>
          </div>
          <input type="hidden" value="{{url()->previous()}}" name="previous">
          <input type="hidden" value="{{$data[0]->orderid}}" name="orderid">
          <div class="fixed-action-btn">
            <button class="btn btn-large red {{$dis}}" onclick="M.toast({html: 'Order being Updated, Please wait...'})">
                update order
              <i class="left material-icons">send</i>
            </button>
        </div>
    </div>
</form>
    <script>
        $("#select1").change(function() { //this occurs when select 1 changes
            $(".select2").val($(this).val());   
        });
        function printorder(id){
            window.open('/saveorder/'+id, '_blank', 'toolbar=0,location=0,menubar=0');
        }
    </script>
    @if($data[0]->seen == NULL)
    <script>
    $(function() {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/seenupdate",
                data: $('#seenupdate').serialize(),
                type: 'post',
                success: function(response) {
                console.log(response)
                }
            })
        })
       
    </script>
    @endif
@endsection