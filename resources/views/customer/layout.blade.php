<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="theme-color" content="#ffb300" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="apple-touch-icon" href="{{ asset('app1.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <title>Admin</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('/assets/light.png') }}">
    <link rel="icon" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('icons/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('icons/safari-pinned-tab.svg') }}" color="#ffb300">
    <link href="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/admin-materialize.min.css?v=8850535670742419153"
        rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Exo' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            font-family: 'Exo';
        }
    </style>
    @extends('style')
</head>

<body class="has-fixed-sidenav">
    <header>
        <div class="navbar-fixed">
            <nav class="navbar topnv">
                <div class="nav-wrapper"><a href="{{ url('/') }}" class="brand-logo grey-text text-darken-4"><img
                            src="{{ asset('assets/' . $user->mode . '.png') }}" height="50" alt=""></a>
                    <ul id="nav-mobile" class="right">
                        <li class="hide-on-med-and-down"><a href="#!" data-target="dropdown1"
                                class="dropdown-trigger"><i class="material-icons textcol">notifications</i></a>
                            <div id="dropdown1" class="dropdown-content notifications bgunder" tabindex="0">
                                <div class="notifications-title textcol" tabindex="0">notifications</div>
                                <div class="card bg-content" tabindex="0">
                                    <div class="card-content"><span class="card-title textcol">Joe Smith made a
                                            purchase</span>
                                        <p>Content</p>
                                    </div>
                                    <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                       
                        <li>
                            <a onclick="history.back()">
                                <i class="material-icons textcol">arrow_back</i>
                            </a>
                        </li>
                        <li><a href="#!" data-target="chat-dropdown" class="dropdown-trigger">
                            @if ($user->profileimg !== NULL)
                            <i class="valign-wrapper">
                                <img src="{{asset($user->profileimg)}}" class="nav-dp circle" alt="">
                            </i>
                            @else
                            <i class="material-icons textcol">face</i>
                            @endif
                            </a>
                            <div id="chat-dropdown" class="dropdown-content dropdown-tabbed" tabindex="0">
                                <div id="settings" class="col s12">
                                    <div class="settings-group">
                                        <a href="{{ url('/user/editprofile') }}" class="bg-content textcol">
                                            <div>Edit Profile
                                            </div>
                                        </a>
                                        <a href="{{ url('/user/changemode') }}" class="bg-content textcol">
                                            <div>Change Mode
                                            </div>
                                        </a>
                                        <a href="{{ url('/logout') }}" class="bg-content textcol">
                                            <div>Logout
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul><a href="#!" data-target="sidenav-left" class="sidenav-trigger left"><i
                            class="material-icons textcol">menu</i></a>
                </div>
            </nav>
        </div>
        <ul id="sidenav-left" class="sidenav sidenav-fixed bg" style="transform: translateX(-105%);">
            <li><a href="{{ url('/') }}" class="logo-container textcol">{{ $user->user_id }}<i
                        class="material-icons left textcol">spa</i></a></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li class="bold"><a href="{{ url('/home') }}" class="textcol">Home<i
                                class="material-icons textcol">home</i></a></li>
                    <li class="bold"><a href="{{ url('/user/createorder') }}" class="textcol">Create Order<i
                                class="material-icons textcol">add</i></a></li>
                    <li class="bold"><a href="{{ url('/user/oldorders') }}" class="textcol">Old Orders<i
                                class="material-icons textcol">shopping_basket</i></a></li>
                    <li class="bold"><a href="{{ url('/user/savedorders') }}" class="textcol">Saved Orders<i
                                class="material-icons textcol">save</i></a></li>
                    <li class="bold"><a href="{{ url('/user/analytics') }}" class="textcol">Analytics<i
                                class="material-icons textcol">equalizer</i></a></li>
                    <li class="bold"><a href="{{ url('/user/summary') }}" class="textcol">Summary<i
                                class="material-icons textcol">multiline_chart</i></a></li>
                    <li class="bold"><a href="{{ url('/user/statement') }}" class="textcol">Statement<i
                                class="material-icons textcol">web</i></a></li>
                </ul>
            </li>
        </ul>



    </header>
    <main>
        <div>
            @yield('main')
        </div>
    </main>

    <div id="flash" class="popup section bgunder"
        style="margin-bottom: -2em; display: block; height: 214px; transform: translateY(0px);">
        <div class="container pWrapper">
            <div class="row">
                <div class="col s12 m8 offset-m2">
                    <div class="card hoverable">
                        <div class="card-content flow-text">
                            {{-- <i class="close material-icons right" onclick="closeThis()" style="cursor: pointer;">close</i> --}}
                            <p id="install-message">
                                You can install this app for easy access.
                                <button id="install" class="btn amber darken-1 black-text"
                                    style="margin: .5em auto auto auto; display: block;">Install Mypower Order</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // document.addEventListener('contextmenu', event => event.preventDefault());

        let deferredPrompt;
        const addBtn = document.querySelector('#install');
        const card = document.querySelector('#flash');
        addBtn.style.display = 'none';
        card.style.display = 'none';

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            addBtn.style.display = 'block';
            card.style.display = 'block';

            addBtn.addEventListener('click', (e) => {
                // hide our user interface that shows our A2HS button
                addBtn.style.display = 'none';
                card.style.display = 'none';
                // Show the prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                    } else {
                        console.log('User dismissed the A2HS prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });
    </script>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('/assets/script.js') }}"></script>
    <script src="{{ asset('/assets/sorttable.js') }}"></script>
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"
        integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>

    <!-- Initialization script -->
    <script src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/dashboard.min.js?v=481680883062710906"></script>
</body>

</html>
