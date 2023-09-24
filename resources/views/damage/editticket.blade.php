@extends('admin/layout')

@section('main')
    <div>
        <div class="center mp-card" style="font-size: 15px; margin-top: 10px;">
            <div>Name: {{ $data[0]->name }}</div>
            <div>Date: {{ $data[0]->date }}</div>
            <div>invoiceid: {{ $data[0]->invoiceid }}</div>
        </div>
        <form enctype="multipart/form-data" method="POST" action="{{ route('admin/edittkt') }}">
            @csrf
            <input type="hidden" name="invoice" value="{{ $data[0]->invoiceid }}">
            <input type="hidden" name="date" value="{{ $data[0]->date }}">
            <input type="hidden" name="name" value="{{ $data[0]->name }}">
            <input type="hidden" name="cusid" value="{{ $data[0]->cusuni_id }}">
            @php
                $s = 0;
            @endphp
            @foreach ($data as $item)
                <div class="mp-card" style="margin-top: 10px;">
                    <div class="row">
                        <div class="col s3">
                            {{ $item->item }}
                        </div>
                        <div class="col s3">
                            Quantity: <span id="{{ $item->produni_id }}mnqty">{{ $item->quantity }}</span>
                        </div>
                        <div class="col s3">
                            Customer remarks: {{ $item->cusremarks }}
                        </div>
                        <div class="col s3">
                            <select class="browser-default selectinp black-text" id="{{$item->produni_id}}statsel" onchange="changeprodstat('{{$item->produni_id}}')">
                                @if ($item->instatus != NULL)
                                    <option value="{{$item->instatus}}" selected>{{$item->instatus}}</option>
                                    <option value="pending">pending</option>
                                @else
                                    <option value="pending" selected>pending</option>
                                @endif
                                <option value="in progress">in progress</option>
                                <option value="partial completed">partial completed</option>
                                <option value="completed">completed</option>
                            </select>
                        </div>
                        <br>
                        <div class="row mp-card z-depth-1 detail-card" id="{{ $item->produni_id }}"
                            style="margin-top: 15px; display: none;">
                            <input type="hidden" name="prod[]" value="{{ $item->item }}">
                            <input type="hidden" name="prodid[]" value="{{ $item->produni_id }}">
                            <input type="hidden" name="category[]" value="{{ $item->category }}">
                            <input type="hidden" name="quantity[]" value="{{ $item->quantity }}">
                            <input type="hidden" name="cusremarks[]" value="{{ $item->cusremarks }}">
                            <input type="hidden" name="stat[]" class="{{$item->produni_id}}statinp" value="{{$item->instatus}}">
                            <input type="hidden" class="{{$item->produni_id}}check" name="check[]" value="dup">
                            <div class="col s3">
                                <input type="text" class="inp browser-default {{ $item->produni_id }}qty"
                                    onkeyup="apndfunc('{{ $item->produni_id }}')" name="dqty[]" placeholder="quantity">
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text" name="condition[]">
                                    @if ($item->condition != null)
                                        <option selected value="{{ $item->condition }}">{{ $item->condition }}</option>
                                        <option class="black-text" value="">Select Condition</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Condition
                                        </option>
                                    @endif
                                    <option value="New">New</option>
                                    <option value="Old">Old</option>
                                </select>
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text" name="warranty[]">
                                  
                                        <option class="black-text" value="" selected>Select warranty</option>
                                   <option value="Under warranty">Under warranty</option>
                                    <option value="warranty Expired">warranty Expired</option>
                                    <option value="Item not under warranty">Item not under warranty</option>
                                    <option value="Warranty Info missing(RCP)">Warranty Info missing(RCP)</option>
                                </select>
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text" name="warrantyproof[]">
                                    
                                        <option class="black-text" value="" selected>Select warranty proof</option>
                                
                                    <option value="warranty card">warranty card</option>
                                    <option value="purchase bill">purchase bill</option>
                                    <option value="Marked with Marker">Marked with Marker</option>
                                    <option value="Online purchase proof">Online purchase proof</option>
                                </select>
                            </div>
                            
                            <div class="col s3">
                                @php
                                    $batch = DB::table('batch')
                                        ->where('product', $item->item)
                                        ->get();
                                @endphp
                                <select class="browser-default selectinp black-text" name="batch[]">
                                    @if ($item->batch != null)
                                        <option selected value="{{ $item->batch }}">{{ $item->batch }}</option>
                                        <option class="black-text" value="">Select Batch</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Batch</option>
                                    @endif
                                    @foreach ($batch as $item3)
                                        <option value="{{ $item3->batch }}">{{ $item3->batch }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s3">
                                @php
                                    $problems = DB::table('problem')
                                        ->where('category', $item->category)
                                        ->get();
                                @endphp
                                <select class="browser-default selectinp black-text" name="problem[]">
                                    @if ($item->problem != null)
                                        <option selected value="{{ $item->problem }}">{{ $item->problem }}</option>
                                        <option class="black-text" value="">Select Problem</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Problem</option>
                                    @endif
                                    @foreach ($problems as $item2)
                                        <option value="{{ $item2->problem }}">{{ $item2->problem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text"
                                    onchange="changedyn(this, '{{ $item->item }}', '{{ $item->produni_id }}')"
                                    name="solution[]">
                                    @if ($item->solution != null)
                                        <option selected value="{{ $item->solution }}">{{ $item->solution }}</option>
                                        <option class="black-text" value="">Select Solution</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Solution</option>
                                    @endif
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
                            <div class="col s3">
                                <div class="{{ $item->produni_id }}dyn input-field">
                                    <input type="hidden" class="pop" name="pop[]" value="">
                                </div>
                            </div>
                            <div class="col s6">
                                <textarea type="text" placeholder="remarks" name="adremarks[]" class="browser-default inp"></textarea>
                            </div>
                        </div>
                        @php
                            $prod = DB::table('damage')
                                ->where('invoiceid', $item->invoiceid)
                                ->where('item', $item->item)
                                ->get();
                            $a = 0;
                        @endphp
                        @foreach ($prod as $item2)
                        @php
                            $a = $a + $item2->grpqty;
                        @endphp
                            <div class="row mp-card z-depth-1 detail-card {{ $item2->produni_id }}sub" style="margin-top: 30px;">
                                <input type="hidden" name="prod[]" value="{{ $item2->item }}">
                                <input type="hidden" name="prodid[]" value="{{ $item2->produni_id }}">
                                <input type="hidden" name="category[]" value="{{ $item2->category }}">
                                <input type="hidden" name="quantity[]" value="{{ $item2->quantity }}">
                                <input type="hidden" name="cusremarks[]" value="{{ $item2->cusremarks }}">
                                <input type="hidden" name="stat[]" class="{{$item->produni_id}}statinp" value="{{$item2->instatus}}">
                                <input type="hidden" name="check[]" value="{{$item->produni_id}}">
                                <div class="col s3">
                                    <input type="text" class="inp browser-default {{ $item2->produni_id }}qty"
                                        onkeyup="apndfunc('{{ $item2->produni_id }}')" name="dqty[]"
                                        placeholder="quantity" value="{{ $item2->grpqty }}">
                                </div>
                                <div class="col s3">
                                    <select class="browser-default selectinp black-text" name="condition[]">
                                        @if ($item2->condition != null)
                                            <option selected value="{{ $item2->condition }}">{{ $item2->condition }}
                                            </option>
                                            <option class="black-text" value="">Select Condition</option>
                                        @else
                                            <option class="black-text" value="" selected>Select Condition
                                            </option>
                                        @endif
                                        <option value="New">New</option>
                                        <option value="Old">Old</option>
                                    </select>
                                </div>
                                <div class="col s3">
                                    <select class="browser-default selectinp black-text" name="warranty[]">
                                        @if ($item2->warranty != null)
                                            <option selected value="{{ $item2->warranty }}">{{ $item2->warranty }}
                                            </option>
                                            <option class="black-text" value="">Select warranty</option>
                                        @else
                                            <option class="black-text" value="" selected>Select warranty</option>
                                        @endif
                                        <option value="Under warranty">Under warranty</option>
                                        <option value="warranty Expired">warranty Expired</option>
                                        <option value="Item not under warranty">Item not under warranty</option>
                                        <option value="Warranty Info missing(RCP)">Warranty Info missing(RCP)</option>
                                    </select>
                                </div>
                                <div class="col s3">
                                    <select class="browser-default selectinp black-text" name="warrantyproof[]">
                                        @if ($item2->warrantyproof != null)
                                            <option selected value="{{ $item2->warrantyproof }}">{{ $item2->warrantyproof }}
                                            </option>
                                            <option class="black-text" value="">Select warranty proof</option>
                                        @else
                                            <option class="black-text" value="" selected>Select warranty proof</option>
                                        @endif
                                        <option value="warranty card">warranty card</option>
                                        <option value="purchase bill">purchase bill</option>
                                        <option value="Marked with Marker">Marked with Marker</option>
                                        <option value="Online purchase proof">Online purchase proof</option>
                                    </select>
                                </div>
                                
                                <div class="col s3">
                                    @php
                                        $batch = DB::table('batch')
                                            ->where('product', $item2->item)
                                            ->get();
                                    @endphp
                                    <select class="browser-default selectinp black-text" name="batch[]">
                                        @if ($item2->batch != null)
                                            <option selected value="{{ $item2->batch }}">{{ $item2->batch }}</option>
                                            <option class="black-text" value="">Select Batch</option>
                                        @else
                                            <option class="black-text" value="" selected>Select Batch</option>
                                        @endif
                                        @foreach ($batch as $item3)
                                            <option value="{{ $item3->batch }}">{{ $item3->batch }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col s3">
                                    @php
                                        $problems = DB::table('problem')
                                            ->where('category', $item2->category)
                                            ->get();
                                    @endphp
                                    <select class="browser-default selectinp black-text" name="problem[]">
                                        @if ($item2->problem != null)
                                            <option selected value="{{ $item2->problem }}">{{ $item2->problem }}</option>
                                            <option class="black-text" value="">Select Problem</option>
                                        @else
                                            <option class="black-text" value="" selected>Select Problem</option>
                                        @endif
                                        @foreach ($problems as $item3)
                                            <option value="{{ $item3->problem }}">{{ $item3->problem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col s3">
                                    <select class="browser-default selectinp black-text"
                                        onchange="changedyn(this, '{{ $item2->item }}', '{{ $item2->produni_id }}')"
                                        name="solution[]">
                                        @if ($item2->solution != null)
                                            <option selected value="{{ $item2->solution }}">{{ $item2->solution }}
                                            </option>
                                            <option class="black-text" value="">Select Solution</option>
                                        @else
                                            <option class="black-text" value="" selected>Select Solution
                                            </option>
                                        @endif
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
                               
                                <div class="col s3">
                                    <div class="{{ $item2->produni_id }}dyn input-field">
                                        @if ($item2->pop == null)
                                            <input type="hidden" class="pop" name="pop[{{$s}}][]">
                                        @else
                                            @if ($item2->solution == 'repaired(fixed new parts)')
                                               
                                                <select id="MySelct" class="{{$item2->produni_id}}dynpart pop"
                                                searchname="myselectsearch" name="pop[{{$s}}][]" searchable="Select Parts" multiple>
                                                    @if ($item2->pop != null)
                                                        @php
                                                            $pops = explode("|", $item2->pop)
                                                        @endphp
                                                        <option class="black-text" value="" disabled>Select Problem</option>
                                                        @foreach ($pops as $p)
                                                        <option selected value="{{ $p }}">
                                                            {{ $p }}</option>
                                                        @endforeach
                                                        
                                                    @else
                                                        <option class="black-text" value="" selected>Select Problem
                                                        </option>
                                                    @endif
                                                    @php
                                                    $parts = DB::table('parts')->get();
                                                    $res = [];
                                                    foreach ($parts as $pt) {
                                                        $items = explode('|', $pt->product);
                                                        foreach ($items as $i) {
                                                            if ($i == $item2->item && !in_array($pt->name, $pops)) {
                                                                $res[] = [
                                                                    'name' => $pt->name,
                                                                ];
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                    @for ($i = 0; $i < count($res); $i++)
                                                    <option value="{{ $res[$i]['name'] }}">{{ $res[$i]['name'] }}
                                                    </option>
                                                    @endfor
                                                </select>
                                            @elseif($item2->solution == 'Replaced with new other item')
                                            <select id="MySelct" class="{{$item2->produni_id}}dynpart pop"
                                            searchname="myselectsearch" name="pop[]" searchable="Select Product" required>
                                                @if ($item2->pop != null)
                                                    <option selected value="{{ $item2->pop }}">{{ $item2->pop }}
                                                    </option>
                                                    <option class="black-text" value="">Select Product</option>
                                                @else
                                                    <option class="black-text" value="" selected>Select Product
                                                    </option>
                                                @endif
                                               @php
                                                $product = DB::table('products')->get();
                                               @endphp
                                               @foreach($product as $prd)
                                                <option value="{{$prd->name}}">{{$prd->name}}</option>
                                               @endforeach
                                            </select>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @php
                                $s = $s + 1;
                                @endphp
                                <div class="col s6">
                                    <textarea type="text" placeholder="remarks" name="adremarks[]" value="{{ $item2->adremarks }}"
                                        class="browser-default inp">{{ $item2->adremarks }}</textarea>
                                </div>
                            </div>
                        @endforeach
                        @if ($a < $item->quantity && $a > 0)
                        <div class="row mp-card z-depth-1 detail-card {{ $item->produni_id }}sub"
                            style="margin-top: 15px;">
                            <input type="hidden" name="prod[]" value="{{ $item->item }}">
                            <input type="hidden" name="prodid[]" value="{{ $item->produni_id }}">
                            <input type="hidden" name="category[]" value="{{ $item->category }}">
                            <input type="hidden" name="quantity[]" value="{{ $item->quantity }}">
                            <input type="hidden" name="cusremarks[]" value="{{ $item->cusremarks }}">
                            <input type="hidden" name="stat[]" class="{{$item->produni_id}}statinp" value="{{$item->instatus}}">
                            <input type="hidden" name="check[]" value="{{$item->produni_id}}">
                            <div class="col s3">
                                <input type="text" class="inp browser-default {{ $item->produni_id }}qty"
                                    onkeyup="apndfunc('{{ $item->produni_id }}')" name="dqty[]" placeholder="quantity">
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text" name="condition[]">
                                    @if ($item->condition != null)
                                        <option selected value="{{ $item->condition }}">{{ $item->condition }}</option>
                                        <option class="black-text" value="">Select Condition</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Condition
                                        </option>
                                    @endif
                                    <option value="New">New</option>
                                    <option value="Old">Old</option>
                                </select>
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text" name="warranty[]">
                                  
                                        <option class="black-text" value="" selected>Select warranty</option>
                                   <option value="Under warranty">Under warranty</option>
                                    <option value="warranty Expired">warranty Expired</option>
                                    <option value="Item not under warranty">Item not under warranty</option>
                                    <option value="Warranty Info missing(RCP)">Warranty Info missing(RCP)</option>
                                </select>
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text" name="warrantyproof[]">
                                    
                                        <option class="black-text" value="" selected>Select warranty proof</option>
                                
                                    <option value="warranty card">warranty card</option>
                                    <option value="purchase bill">purchase bill</option>
                                    <option value="Marked with Marker">Marked with Marker</option>
                                    <option value="Online purchase proof">Online purchase proof</option>
                                </select>
                            </div>
                            
                            <div class="col s3">
                                @php
                                    $batch = DB::table('batch')
                                        ->where('product', $item->item)
                                        ->get();
                                @endphp
                                <select class="browser-default selectinp black-text" name="batch[]">
                                    @if ($item->batch != null)
                                        <option selected value="{{ $item->batch }}">{{ $item->batch }}</option>
                                        <option class="black-text" value="">Select Batch</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Batch</option>
                                    @endif
                                    @foreach ($batch as $item3)
                                        <option value="{{ $item3->batch }}">{{ $item3->batch }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s3">
                                @php
                                    $problems = DB::table('problem')
                                        ->where('category', $item->category)
                                        ->get();
                                @endphp
                                <select class="browser-default selectinp black-text" name="problem[]">
                                    @if ($item->problem != null)
                                        <option selected value="{{ $item->problem }}">{{ $item->problem }}</option>
                                        <option class="black-text" value="">Select Problem</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Problem</option>
                                    @endif
                                    @foreach ($problems as $item2)
                                        <option value="{{ $item2->problem }}">{{ $item2->problem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s3">
                                <select class="browser-default selectinp black-text"
                                    onchange="changedyn(this, '{{ $item->item }}', '{{ $item->produni_id }}')"
                                    name="solution[]">
                                    @if ($item->solution != null)
                                        <option selected value="{{ $item->solution }}">{{ $item->solution }}</option>
                                        <option class="black-text" value="">Select Solution</option>
                                    @else
                                        <option class="black-text" value="" selected>Select Solution</option>
                                    @endif
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
                            <div class="col s3">
                                <div class="{{ $item->produni_id }}dyn input-field">
                                    <input type="hidden" class="pop" name="pop[]" value="">
                                </div>
                            </div>
                            <div class="col s6">
                                <textarea type="text" placeholder="remarks" name="adremarks[]" class="browser-default inp"></textarea>
                            </div>
                        </div>
                        @endif
                        <div id="{{ $item->produni_id }}cont">
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="fixed-action-btn">
                <button class="btn btn-large red modal-trigger" style="border-radius: 10px;">
                    Submit
                    <i class="left material-icons">send</i>
                </button>
            </div>
        </form>

    </div>

    <script>
        changeindex()
        function apndfunc(prodid) {
            const mqty = $(`#${prodid}mnqty`).text();
            let qtys = $(`.${prodid}qty`);
            var a = 0;
            var b = 0;
            // console.log(mqty);
            for (let i = 1; i < qtys.length; i++) {
                if (qtys[i].value === '') {
                    b = b + 1
                    qtys[i].closest(`.${prodid}sub`).remove();
                } else {
                    a = a + parseInt(qtys[i].value);
                }
            }
            let qty = $(`.${prodid}qty`);
            if (b >= 2 || qty.length <= 1) {
               var cln = $(`#${prodid}`).clone().addClass(`detail-card`).addClass(`${prodid}cln`).addClass(`${prodid}sub`).show().appendTo(`#${prodid}cont`);
               cln.find(`.${prodid}check`).val(prodid)
            } else if (a < parseInt(mqty) && a !== 0) {
                var cln = $(`#${prodid}`).clone().addClass(`detail-card`).addClass(`${prodid}cln`).addClass(`${prodid}sub`).show().appendTo(`#${prodid}cont`);
                cln.find(`.${prodid}check`).val(prodid)
            } else if (a === 0) {
                $(`.${prodid}cln`).remove();
            }
            $('select').formSelect();
            changeindex()
        }

        function changedyn(sel, item, prodid) {
            if (sel.value === 'repaired(fixed new parts)'   ) {
                var a = $(sel).closest('.mp-card');
                $(a).find(`.${prodid}dyn`).html('');
                let qty = $('.detail-card');
                $.ajax({
                    type: 'get',
                    url: '/getparts/' + item,
                    success: function(response) {
                        var $sc = $(a).find(`.${prodid}dyn`);
                        $sc.empty();
                        $sc.append(`<select id="MySelct" class="${prodid}dynpart pop"
                                    searchname="myselectsearch" name="pop[${qty.length - 1}][]" searchable="Select Parts" multiple required>
                                    <option value="" disabled>Select Parts</option>
                                </select>`)
                        var scs = $(a).find(`.${prodid}dynpart`);
                        // scs.append($('<option></option>').attr('value', null).attr('disabled', 'true').attr(
                        //     'selected', 'false').text(
                        //     'select Parts'));
                        $.each(response, function(key, value) {
                            console.log(value.name)
                            scs.append($('<option></option>')
                                .attr("value", value.name).text(value.name))
                        })
                        $('select').formSelect();
                        changeindex()
                    }
                })
                // $(a).find(`.${prodid}dyn`).html('rep')
            } else if (sel.value === 'Replaced with new other item') {
                var a = $(sel).closest('.mp-card');
                $(a).find(`.${prodid}dyn`).html('');
                $.ajax({
                    type: 'get',
                    url: '/finditem',
                    success: function(response) {
                        var $sc = $(a).find(`.${prodid}dyn`);
                        $sc.empty();
                        $sc.append(`<select id="MySelct" class="${prodid}dynpart pop"
                                    searchname="myselectsearch" name="pop[]" searchable="Select Product" required>
                                    <option value="" disabled>Select Product</option>
                                </select>`)
                        var scs = $(a).find(`.${prodid}dynpart`);
                        scs.append($('<option></option>').attr('value', null).attr('disabled', 'true').attr(
                            'selected', 'false').text(
                            'select Products'));
                        $.each(response, function(key, value) {
                            console.log(value.name)
                            scs.append($('<option></option>')
                                .attr("value", value.name).text(value.name))
                        })
                        $('select').formSelect();
                        changeindex()
                    }
                })
            } else {
                var a = $(sel).closest('.mp-card');
                $(a).find(`.${prodid}dyn`).html('<input type="hidden" name="pop[]" class="pop" value="">');
                changeindex()
            }
            $('select').formSelect();
            changeindex()
        }

        function changeindex(){
            let qty = $('.detail-card');
            for (let i = 0; i < qty.length; i++) {
                var inp = $(qty[i]).find('.pop');
                $(inp).attr('name', `pop[${i}][]`)
            }
        }
        function changeprodstat(prod){
            var x = $(`#${prod}statsel`).val();
            $(`.${prod}statinp`).val(x);
        }
    </script>
@endsection
