@extends('admin/layout')

@section('main')
<form action="">
    <div class="mp-card" style="margin-top: 5vh;">
        <div>
            <h5 class="center">Add Staff</h5>
        </div>
        
            <div class="row">
                <div class="col s12 row">
                    <div class="col s6">
                        Name:
                    </div>
                    <div class="col s6">
                        <input type="text" name="name" class="inp black-text browser-default" placeholder="Name">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        User ID:
                    </div>
                    <div class="col s6">
                        <input type="text" name="userid" class="inp black-text browser-default" placeholder="User Id">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Contact:
                    </div>
                    <div class="col s6">
                        <input type="text" name="contact" class="inp black-text browser-default" placeholder="contact">
                    </div>
                </div>
                <div class="col s12 row">
                    <div class="col s6">
                        Password:
                    </div>
                    <div class='input-field col s6'>
                        <input class='validate browser-default inp black-text' placeholder="password" type='password' name='password'
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
                        <select id="select1" class="browser-default selectinp black-text">
                            <option class="black-text" value="" selected disabled>Staff Type</option>
                            <option class="black-text" value="staff">Staff</option>
                            <option class="black-text" value="marketer">Marketer</option>
                        </select>
                    </div>
                </div>
            </div>
    </div>
    <div class="mp-card" style="margin-top: 5vh;">

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
