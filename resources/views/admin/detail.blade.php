@extends('admin/layout')

@section('main')
    <div>
        <div>
            <h6>Customer: {{$data[0]->name}}</h6>
            <h6>orderid: {{$data[0]->orderid}}</h6>
            <h6>Date: {{date('Y-m-d', strtotime($data[0]->created_at))}}</h6>
            <h6>Miti: {{getNepaliDate($data[0]->created_at)}}</h6>
            @php
                $cus = DB::table('customers')->where('name', $data[0]->name)->first();
            @endphp
            @if($cus->refname != NULL)
            <h6>Referer: {{$cus->refname}}</h6>
            @endif
        </div>


        <div class="mp-card" style="overflow-x: scroll">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th class="center">Ordered Quantity</th>
                        <th class="center">Approved Quantity</th>
                        <th class="center">Price</th>
                        <th>
                            <label>Status</label><select id="select1" class="browser-default selectinp black-text" style="width: 100px;">
                              <option value="" selected disabled>for all</option>
                              <option value="pending">Pending</option>
                              <option value="approved">approved</option>
                              <option value="rejected">rejected</option>
                          </select></th>
                        <th>total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$item->item}}</td>
                            <td class="center">{{$item->quantity}}</td>
                            <td class="center">
                                <span class="amber lighten-2 black-text" style="padding: 10px;" onclick="this.remove(); $('#{{$item->id}}ap').css('display', 'block');">{{$item->approvedquantity}}</span>
                                <input id="{{$item->id}}ap" type="text" class="inp browser-default black-text" style="display: none;" name="apquantity[]" value="{{$item->approvedquantity}}"></td>
                            </td>
                            <td class="center">
                                <span class="amber lighten-2 black-text center" style="padding: 10px;" onclick="this.remove(); $('#{{$item->id}}').css('display', 'block');">{{$item->price}}</span>
                                <input id="{{$item->id}}" type="text" class="inp browser-default black-text" style="display: none;" name="price[]" value="{{$item->price}}"></td>
                            <td>
                                <select name="status[]" class="select2 browser-default selectinp black-text" style="width: 100px;" form="update" required>
                                    @if ($item->status == 'pending')
                                      <option value="pending" class="" selected>{{$item->status}}</option>
                                      @else
                                      <option value="{{$item->status}}" class="" selected>{{$item->status}}</option>
                                      <option value="pending">Pending</option>
                                    @endif
                                    <option value="approved">approved</option>
                                    <option value="rejected">rejected</option>
                                  </select>
                            </td>
                            <td>
                                @if ($item->status == 'approved')
                                    {{$item->approvedquantity * $item->price}}
                                @elseif($item->status == 'pending')
                                    {{$item->quantity*item->price}}
                                @else
                                    0
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mp-card">
            <h6>User Remarks: {{$data['0']->userremarks}}</h6>
        </div>
        <div class="bg-content mp-card" style="margin-top:30px;">
            <div class="input-field col s12">
              <textarea id="textarea1" name="remarks" placeholder="Remarks" class="materialize-textarea">{{$data['0']->remarks}}</textarea>
            </div>
            <div class="input-field col s12">
              <input type="number" pattern="[0-9]*" value="{{$data['0']->cartoons}}" inputmode="numeric" name="cartoons" placeholder="Number of Cartoons" class="validate">
            </div>
            <div class="input-field col s12">
              <textarea id="textarea2" name="transport" placeholder="Transportation Details" class="materialize-textarea">{{$data['0']->transport}}</textarea>
            </div>
          </div>
          <div class="fixed-action-btn">
            <button class="btn btn-large red" onclick="M.toast({html: 'Order being Placed, Please wait...'})">
                update order
              <i class="left material-icons">send</i>
            </button>
        </div>
    </div>

    <script>
        $("#select1").change(function() { //this occurs when select 1 changes
    $(".select2").val($(this).val());   
  });
    </script>
@endsection