@extends('admin/layout')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 20px;">
            <form id="filter_form" class="row">
                <div class="input-field col m3 s6">
                    <input type="text" name="product" id="product" placeholder="Product"
                        onchange="$('#filter_form').submit(); batchlist();" onkeyup="$('#filter_form').submit(); batchlist();"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class="input-field col m3 s6">
                    <select name="category" id="category" onchange="$('#filter_form').submit(); problemlist();"
                        class="browser-default selectinp black-text">
                        <option value="" selected>Select Category</option>
                        <option value="powerbank">Powerbank</option>
                        <option value="charger">Charger</option>
                        <option value="cable">Cable</option>
                        <option value="earphone">earphone</option>
                        <option value="btitem">btitem</option>
                        <option value="others">others</option>
                    </select>
                </div>
                <div class="input-field col m3 s6">
                    <input type="text" name="name" onchange="$('#filter_form').submit()"
                        onkeyup="$('#filter_form').submit()" id="customer" placeholder="Customer"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class="input-field col m3 s6">
                    <input type="text" name="batch" onchange="$('#filter_form').submit()"
                        onkeyup="$('#filter_form').submit()" id="batch" placeholder="Batch"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class="col s12">
                </div>
                <div class="input-field col m3 s6">
                    <input type="text" name="problem" onchange="$('#filter_form').submit()"
                        onkeyup="$('#filter_form').submit()" id="problem" placeholder="Problem"
                        class="autocomplete browser-default inp black-text" autocomplete="off">
                </div>
                <div class="input-field col m3 s6">
                    <select class="browser-default selectinp black-text" onchange="$('#filter_form').submit()"
                        name="solution">
                        <option class="black-text" value="" selected>Select Solution</option>
                        <option value="repaired(same product)">repaired</option>
                        <option value="repaired(fixed new parts)">repaired(fixed new parts)
                        </option>
                        <option value="Replaced with new item">Replaced with new item</option>
                        <option value="Replaced with new other item">Replaced with new other item</option>
                        <option value="Return in same condition(Warranty Void)">Return in same
                            condition(Warranty
                            Void)</option>
                        <option value="Return in same condition(No problem)">Return in same condition(No
                            problem)
                        </option>
                    </select>
                </div>
            </form>
        </div>
        <div class="mp-card" style="margin:-top: 10px;">
            <table class="sortable">
                <thead>
                    <th>Date</th>
                    <th>name</th>
                    <th>product</th>
                    <th>batch</th>
                    <th>problem</th>
                    <th>solution</th>
                    <th>quantity</th>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <th id="total_qty"></th>
                    </tr>
                </thead>
                <tbody id="data-body">

                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/finditem',
                success: function(response) {

                    var custarray = response;
                    var datacust = {};
                    for (var i = 0; i < custarray.length; i++) {

                        datacust[custarray[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#product').autocomplete({
                        data: datacust,
                    });
                }
            })
        })
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/findcustomer',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#customer').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })

        function batchlist() {
            a = $('#product').val();
            $.ajax({
                type: 'get',
                url: '/getbatch/' + a,
                success: function(response) {
                    var custarray2 = response;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].batch] = null;
                    }
                    // console.log(datacust2)
                    $('input#batch').autocomplete({
                        data: datacust2,
                    });
                }
            })
        }

        function problemlist() {
            a = $('#category').val();
            $.ajax({
                type: 'get',
                url: '/getproblem/' + a,
                success: function(response) {
                    var custarray2 = response;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].problem] = null;
                    }
                    // console.log(datacust2)
                    $('input#problem').autocomplete({
                        data: datacust2,
                    });
                }
            })
        }
        $('#filter_form').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#filter_form")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/getdata/damage/analytics",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    // console.log(response.length)
                    $('#data-body').text("");
                    let qty = 0
                    for(let i=0; i<response.length; i++){
                        $("#data-body").append(`
                            <tr>
                                <td>${response[i].date}
                                <td>${response[i].name}</td> 
                                <td>${response[i].item}</td> 
                                <td>${response[i].batch}</td> 
                                <td>${response[i].problem}</td> 
                                <td>${response[i].solution}</td> 
                                <td>${response[i].grpqty}</td> 
                            </tr>
                        `)
                        if(response[i].grpqty == null){
                            qty = qty
                        }
                        else{
                            qty = qty + parseInt(response[i].grpqty)
                        }
                    }
                    $('#total_qty').text(qty);
                }
            })
        })
    </script>
@endsection
