@extends('admin/layout')

@section('main')
    <div>
        <div>
            <div class="mp-card" style="margin-top: 5vh;">
                <div>
                    <h6 class="center">Add Product</h6>
                </div>
                <form action="{{route('addprod')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col s12 row">
                            <div class="col s6">
                               Product Name:
                            </div>
                            <div class="col s6">
                                <input type="text" name="name" value="{{$name}}" placeholder="Product Name"
                                    class="browser-default inp black-text" autocomplete="off" required>
                                    <input type="hidden" name="name1" value="{{$name}}">
                            </div>
                        </div>
                        <div class="col s12 row">
                            <div class="col s6">
                                Price:
                            </div>
                            <div class="col s6">
                                <input type="number" name="price" value="{{$price}}" class="inp black-text browser-default"
                                    placeholder="price" required>
                            </div>
                        </div>
                        <div class="col s12 row">
                            <div class="col s6">
                                Category:
                            </div>
                            <div class="col s6">
                                <select name="category" class="browser-default selectinp" value="{{$category}}" onchange="subcate()" id="category" required>
                                    @if ($category > 0)
                                        <option value="{{ $category }}" selected>{{ $category }}</option>
                                    @else
                                        <option value="" disabled selected>Choose your option</option>
                                    @endif
                                    <option value="powerbank">powerbank</option>
                                    <option value="charger">charger</option>
                                    <option value="cable">cable</option>
                                    <option value="btitem">Bluetooth Item</option>
                                    <option value="earphone">earphones</option>
                                    <option value="others">others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col s12 row">
                            <div class="col s6">
                                Tags:
                            </div>
                            <div class="input-field col s6">
                                <select multiple id="subcat" name="subcat[]">
                                  @if($subcat == NULL)
                                  <option value="" disabled>Select Subcategory</option>
                                  @php
                                      $sbs = DB::table('subcategory')->where('parent', $category)->get();
                                    @endphp
                                    @foreach ($sbs as $item)
                                        <option value="{{$item->subcategory}}">{{$item->subcategory}}</option>
                                    @endforeach
                                  @else
                                  <option value="" disabled>Select Subcategory</option>
                                    @foreach (explode('|', $subcat) as $item)
                                        <option value="{{$item}}" selected>{{$item}}</option>
                                    @endforeach
                                    @php
                                      $sbs = DB::table('subcategory')->where('parent', $category)->whereNotIn('subcategory', explode('|', $subcat))->get();
                                    @endphp
                                    @foreach ($sbs as $item)
                                        <option value="{{$item->subcategory}}">{{$item->subcategory}}</option>
                                    @endforeach
                                  @endif
                                </select>
                              </div>
                        </div>
                        <div class="col s12 row">
                            <div class="col s6">
                                Unique ID:
                            </div>
                            <div class="col s6">
                                <input type="text" name="uniqueid" value="{{$unique_id}}" placeholder="Unique Id"
                                    class="browser-default inp black-text" autocomplete="off" required>
                                <input type="hidden" name="uid_old" value="{{$unique_id}}">
                            </div>
                        </div>
                        <div class="col s12 row">
                            <div class="col s6">
                                Details:
                            </div>
                            <div class="col s6">
                                <textarea type="text" name="details" placeholder="Details"
                                    class="browser-default inp black-text" autocomplete="off" style="resize: vertical;" value="{{$details}}">{{$details}}</textarea>
                            </div>
                        </div>
                        <div class="col s6">
                            <label>
                                <input type="checkbox" name="stock" {{$stock_selected}}/>
                                <span>Out of Stock</span>
                            </label>
                        </div>
                        <div class="col s6">
                            <label>
                                <input type="checkbox" name="hide" {{$hide_selected}}/>
                                <span>Hide</span>
                            </label>
                        </div>
                        <div class="input-field col s12 m12">
                            <div class="file-field input-field">
                                <div class="btn amber darken-1 black-text">
                                    <span>File</span>
                                    <input id="image" type="file" name="img">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path" placeholder="Upload a photo" type="text">
                                    @if ($img != '')
                                        <img width="100px"
                                                src="{{ asset('storage/media/' . $img) }}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="input-field col s12 m12">
                            <div class="file-field input-field">
                                <div class="btn amber darken-1 black-text">
                                    <span>File</span>
                                    <input id="image" type="file" name="img2">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path" placeholder="Upload a photo" type="text">
                                    @if ($img2 != '')
                                       <img width="100px"
                                                src="{{ asset('storage/media/' . $img2) }}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ $id }}">
                    </div>
                    <div class="fixed-action-btn">
                        <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                            style="border-radius: 10px;">
                            Submit
                            <i class="left material-icons">send</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
         function subcate() {
            var value = $('#category').val()
            // console.log(value);
            $('#subcate2').val(null)
            $.ajax({
                type: 'get',
                url: '/getsubcat/' + value,
                success: function(response) {
                    // console.log(response[0].subcategory);
                    var $sc = $('#subcat');
                    $sc.empty();
                    $sc.append($('<option></option>').attr('value', null).attr('disabled', 'true').attr('selected', 'true').text(
                        'select sub-category'));
                    $.each(response, function(key, value) {
                        console.log(value.subcategory)
                        $sc.append($('<option></option>')
                            .attr("value", value.subcategory).text(value.subcategory))
                    })
                    $('select').formSelect();
                }
            })
        }
    </script>
@endsection