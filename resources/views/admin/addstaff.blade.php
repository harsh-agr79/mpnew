@extends('admin/layout')

@section('main')
<form action="{{route('addstaffprocess')}}" method="post">
    @csrf
    <input type="hidden" value="{{$id}}" name="id">
    <div class="mp-card" style="margin-top: 5vh;">
        <div>
            <h6 class="center">Add Staff</h6>
        </div>
        
            <div class="row">
                <div class="col s12 row">
                    <div class="col s6">
                        Name:
                    </div>
                    <div class="col s6">
                        <input type="text" value="{{$name}}" name="name" class="inp black-text browser-default" placeholder="Name">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        User ID:
                    </div>
                    <div class="col s6">
                        <input type="text" value="{{$userid}}" name="userid" class="inp black-text browser-default" placeholder="User Id">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Contact:
                    </div>
                    <div class="col s6">
                        <input type="text" value="{{$contact}}" name="contact" class="inp black-text browser-default" placeholder="contact">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Password:
                    </div>
                    <div class='input-field col s6'>
                        <input class='validate browser-default inp black-text' value="{{$password}}" placeholder="password" type='password' name='password'
                            id='password' required />
                        <span toggle="#password" class="field-icon toggle-password"><span
                                class="material-icons black-text">visibility</span></span>
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Type:
                    </div>
                    <div class="col s6">
                        <select id="select1" name="type" class="browser-default selectinp black-text">
                            @if ($type != NULL)
                                <option selected value="{{$type}}">{{$type}}</option>
                                <option class="black-text" value="">Staff Type</option>    
                            @else
                                <option class="black-text" value="" selected disabled>Staff Type</option>    
                            @endif
                            <option class="black-text" value="staff">Staff</option>
                            <option class="black-text" value="marketer">Marketer</option>
                        </select>
                    </div>
                </div>
            </div>
    </div>
    @if ($type == 'staff')
    <div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">DashBoard Settings</h6>
            </div>
            <div class="row">
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="totalamount" @if(in_array('totalamount', $permission)) checked @endif/>
                        <span>View Total Amount</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="firstorderview|seenupdate" @if(in_array('firstorderview', $permission)) checked @endif/>
                        <span>First Order View</span>
                      </label>
                </div>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">View Orders Settings</h6>
            </div>
            <div class="row">
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="orders" @if (in_array('orders', $permission))
                        checked
                    @endif/>
                        <span>View All Orders</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="pendingorders" @if (in_array('pendingorders', $permission))
                        checked
                    @endif/>
                        <span>View Pending Orders</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="approvedorders" @if (in_array('approvedorders', $permission))
                        checked
                    @endif/>
                        <span>View Approved Orders</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="rejectedorders" @if (in_array('rejectedorders', $permission))
                        checked
                    @endif/>
                        <span>View Rejected Orders</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="deliveredorders" @if (in_array('deliveredorders', $permission))
                        checked
                    @endif/>
                        <span>View Delivered Orders</span>
                      </label>
                </div>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Order Interaction Settings</h6>
            </div>
            <div class="row">
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="detail/{id}|appdetail/{id}" @if (in_array('detail/{id}', $permission))
                            checked
                        @endif/>
                        <span>View Invoice</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="chalan|chalandetail/{id}" @if (in_array('chalan', $permission))
                            checked
                        @endif/>
                        <span>View Chalan</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="updatecln" @if (in_array('updatecln', $permission))
                            checked
                        @endif/>
                        <span>Pack Order</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>Create Order</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>Edit Orders</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="detailupdate" @if (in_array('detailupdate', $permission))
                            checked
                        @endif/>
                        <span>Change Order Status/Approved Quantity/ Price/ Remarks</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                         <input type="checkbox" name="perm[]" value="updatedeliver" @if (in_array('updatedeliver', $permission))
                        checked
                    @endif/>
                        <span>Deliver / Undeliver</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>Print Orders</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>Bulk Print Orders</span>
                      </label>
                </div>
                
            </div>
        </div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Analytics Settings</h6>
            </div>
            <div class="row">
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="mainanalytics|sortanalytics" @if (in_array('mainanalytics', $permission))
                        checked
                    @endif/>
                        <span>View Analytics</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="detailedreport" @if(in_array('detailedreport', $permission))
                            checked
                        @endif/>
                        <span>View Detailed Report</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox"  name="perm[]" value="statement|balancesheet/{id}" @if(in_array('statement', $permission))
                        checked
                    @endif/>
                        <span>View Statements</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox"  name="perm[]" value="refererstatement|balancesheet/{id}" @if(in_array('refererstatement', $permission))
                        checked
                    @endif/>
                        <span>View Referer Statements</span>
                      </label>
                </div>
            </div>
        </div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">General Settings</h6>
            </div>
            <div class="row">
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="payments"  @if(in_array('payments', $permission))
                        checked
                    @endif/>
                        <span>View Payments</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="addpayment|editpayment/{id}|addpay|deletepayment/{id}"  @if(in_array('addpayment', $permission))
                        checked
                    @endif/>
                        <span>Add/Edit Payments</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>View Sales returns</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>Add/Edit Sales returns</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="expenses"  @if(in_array('expenses', $permission))
                        checked
                    @endif/>
                        <span>View Expenses</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="addexpense|editexpense/{id}|addexp|deleteexpense/{id}"  @if(in_array('addexpense', $permission))
                        checked
                    @endif/>
                        <span>Add/Edit Expenses</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="customers" @if(in_array('customers', $permission))
                        checked
                    @endif/>
                        <span>View Customers</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox"  name="perm[]" value="addcustomer|editcustomer/{id}|deletecustomer/{id}|addcus" @if(in_array('addcustomer', $permission))
                        checked
                    @endif/>
                        <span>Add/Edit Customers</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>View Products</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" />
                        <span>Add/Edit Products</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="subcategory" @if(in_array('subcategory', $permission))
                        checked
                    @endif/>
                        <span>View Subcategory</span>
                      </label>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="addsubcategory|addsubcategory/{id}|deletesubcategory/{id}|addsubcategory" @if(in_array('addsubcategory', $permission))
                        checked
                    @endif/>
                        <span>Add/Edit Subcategory</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
                <div class="col m6 s12">
                    <label>
                        <input type="checkbox" name="perm[]" value="frontsettings|frontimg|delete/frontimg/{id}/{id2}" @if(in_array('frontsettings', $permission))
                        checked
                    @endif/>
                        <span>Front Settings</span>
                      </label>
                </div>
                <div class="col s12">
                    <hr>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="fixed-action-btn">
        <button class="btn btn-large red" onclick="M.toast({html: 'Staff Being Updated, Please wait...'})" style="border-radius: 10px;">
            update Staff
          <i class="left material-icons">send</i>
        </button>
    </div>
</form>
    <script>
        var clicked = 0;

        $(".toggle-password").click(function(e) {
            e.preventDefault();

            $(this).toggleClass("toggle-password");
            if (clicked == 0) {
                $(this).html('<span class="material-icons">visibility_off</span >');
                clicked = 1;
            } else {
                $(this).html('<span class="material-icons">visibility</span >');
                clicked = 0;
            }

            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endsection
