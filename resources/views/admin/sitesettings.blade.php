@extends('admin/layout')

@section('main')
    @php
        $ad2 = DB::table('admins')->where('id', '2')->first();
    @endphp
    <div class="container">
        <h3 class="center">Current Status: @if ($ad2->disabled == 'on')
                Disabled
            @else
                Enabled
            @endif
        </h3>
        @if ($ad2->disabled == 'on')
            @if ($admin->email == 'adminharsh' || $admin->email == 'manoj')
                <div class="row z-depth-3" style="padding:20px; border-radius:10px;">
                    <div class="col s12">
                        <h4 class="center"><i class="material-icons green-text left" style="font-size: 3rem;">check</i>Enable
                            the site</h4>
                    </div>
                    <div class="col s12 center" style="margin-top: 100px;">
                        <a class="waves-effect waves-light green darken-1 btn-large modal-trigger" href="#modal2"><i
                                class="material-icons left">check</i>Enable</a>

                        <!-- Modal Structure -->
                        <div id="modal2" class="modal black-text">
                            <div class="modal-content">
                                <h4>Enable the site</h4>
                            </div>
                            <div class="modal-footer center">
                                <a href="#!" class="modal-close waves-effect waves-green btn-flat">cancel</a>
                                <a href="{{ url('enable') }}"
                                    class="modal-close waves-effect waves-green btn green darken-1"><i
                                        class="material-icons left">check</i>Enable</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="row z-depth-3" style="padding:20px; border-radius:10px;">
                <div class="col s12">
                    <h4 class="center"><i class="material-icons red-text left" style="font-size: 3rem;">warning</i>Disable
                        the site</h4>
                </div>
                <div class="col s12 center" style="margin-top: 100px;">
                    <a class="waves-effect waves-light red darken-1 btn-large modal-trigger" href="#modal1"><i
                            class="material-icons left">do_not_disturb</i>Disable</a>

                    <!-- Modal Structure -->
                    <div id="modal1" class="modal black-text">
                        <div class="modal-content">
                            <h4>Are you sure you wanna disabled the website?</h4>
                        </div>
                        <div class="modal-footer center">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">cancel</a>
                            <a href="{{ url('disable') }}" class="modal-close waves-effect waves-green btn red darken-1"><i
                                    class="material-icons left">do_not_disturb</i>Disable</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- {{$ad['1']->disabled}} --}}


    </div>
    <script>
        $(document).ready(function() {
            $('.modal').modal();
        });
    </script>
@endsection
