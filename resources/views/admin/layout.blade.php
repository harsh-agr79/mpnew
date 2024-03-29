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
    <link rel="stylesheet" href="{{ asset('/assets/' . $admin->mode . '.css') }}">
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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
                            src="{{ asset('assets/' . $admin->mode . '.png') }}" height="50" alt=""></a>
                    @if ($admin->type == 'staff')
                        @php
                            $channels = DB::table('channels')->get();
                            $perms = DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray();
                            $chn = [];
                            foreach ($channels as $item) {
                                if (in_array($item->shortname, $perms)) {
                                    array_push($chn, $item->shortname);
                                }
                            }
                            $chat = DB::table('chat')->whereIn('channel', $chn)->orderBy('created_at', 'DESC')->first();
                        @endphp
                    @endif

                    <ul id="nav-mobile" class="right">
                        <li class="hide-on-med-and-down">
                            @if ($admin->type == 'staff' && $chat != null)
                                <a href="{{ url('/chats/' . $chat->sid . '/' . $chat->channel) }}"><i
                                        class="material-icons textcol">
                                        chat
                                    </i></a>
                            @else
                                <a href="{{ url('/chats/3/general') }}"><i class="material-icons textcol">
                                        chat
                                    </i></a>
                            @endif

                            <div class="red white-text center valign-wrapper"
                                style="position: absolute; top:15px; margin-left: 30px; z-index:1; height: 15px; padding: 5px 3px; border-radius:50%; font-size: 10px;">
                                <span class="center" id="msgcnt">{{ $msgcnt }}</span>
                            </div>
                        </li>
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

                        <li><a href="#!" data-target="chat-dropdown" class="dropdown-trigger"><i
                                    class="material-icons textcol">settings</i></a>
                            <div id="chat-dropdown" class="dropdown-content dropdown-tabbed" tabindex="0">
                                <div id="settings" class="col s12">
                                    <div class="settings-group">
                                        <a href="{{ url('/admin/changemode') }}" class="bg-content textcol">
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
                        <li>
                            <a onclick="history.back()">
                                <i class="material-icons textcol">arrow_back</i>
                            </a>
                        </li>
                    </ul>
                    <ul id="nav-mobile" class="left hide-on-large-only">
                        <li>
                            <a href="#!" data-target="sidenav-left" class="sidenav-trigger left"><i
                                    class="material-icons textcol">menu</i></a>
                        </li>

                        <li>
                            <a href="{{ url('admin/m/chatlist') }}" class="left"><i
                                    class="material-icons textcol">
                                    chat
                                </i></a>
                            <div class="red white-text center valign-wrapper"
                                style="position: absolute; top:15px; margin-left: 30px; z-index:1; height: 15px; padding: 5px 3px; border-radius:50%; font-size: 10px;">
                                <span class="center" id="msgcnt2">{{ $msgcnt }}</span>
                        </li>
                    </ul>

                </div>
            </nav>
        </div>
        <ul id="sidenav-left" class="sidenav sidenav-fixed bg" style="transform: translateX(-105%);">
            <li><a href="{{ url('/') }}" class="logo-container textcol">{{ $admin->email }}<i
                        class="material-icons left textcol">spa</i></a></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li class="bold"><a href="{{ url('/dashboard') }}" class="textcol">Dashboard<i
                                class="material-icons textcol">web</i></a></li>
                    @if (
                        $admin->type == 'admin' ||
                            in_array('orders', $perms) ||
                            in_array('pendingorders', $perms) ||
                            in_array('deliveredorders', $perms) ||
                            in_array('rejectedorders', $perms) ||
                            in_array('approvedorders', $perms) ||
                            in_array('chalan', $perms) ||
                            in_array('createorder', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Orders<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('orders', $perms))
                                        <li><a href="{{ url('/orders') }}" class="textcol">View Orders<i
                                                    class="material-icons textcol">visibility</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('approvedorders', $perms))
                                        <li class="amber darken-1"><a href="{{ url('/approvedorders') }}"
                                                class="textcol">Approved Orders<i
                                                    class="material-icons textcol">check</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('pendingorders', $perms))
                                        <li class="blue"><a href="{{ url('/pendingorders') }}"
                                                class="textcol">Pending
                                                Orders<i class="material-icons textcol">warning</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('rejectedorders', $perms))
                                        <li class="red"><a href="{{ url('/rejectedorders') }}"
                                                class="textcol">Rejected Orders<i
                                                    class="material-icons textcol">clear</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('deliveredorders', $perms))
                                        <li class="green"><a href="{{ url('/deliveredorders') }}"
                                                class="textcol">Delivered Orders<i
                                                    class="material-icons textcol">local_shipping</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('chalan', $perms))
                                        <li class="deep-purple"><a href="{{ url('/chalan') }}"
                                                class="textcol">Chalan<i class="material-icons textcol">check</i></a>
                                        </li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('bulkprint', $perms))
                                        <li class="cyan ligthen-3"><a href="{{ url('/bulkprintorders') }}"
                                                class="black-text">Bulk Print<i
                                                    class="material-icons black-text">print</i></a>
                                        </li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('createorder', $perms))
                                        <li class="amber lighten-4"><a href="{{ url('/createorder') }}"
                                                class="black-text">Create Order<i
                                                    class="material-icons black-text">add</i></a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('mainanalytics', $perms) || in_array('detailedreport', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Analytics<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('mainanalytics', $perms))
                                        <li><a href="{{ url('/mainanalytics') }}" class="textcol">Main Analytics<i
                                                    class="material-icons textcol">pie_chart</i></a></li>
                                        <li><a href="{{ url('/sortanalytics') }}" class="textcol">Sort Analytics<i
                                                    class="material-icons textcol">multiline_chart</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('detailedreport', $perms))
                                        <li><a href="{{ url('/detailedreport') }}" class="textcol">Detailed Report<i
                                                    class="material-icons textcol">show_chart</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('productreport', $perms))
                                        <li><a href="{{ url('/productreport') }}" class="textcol">Product Report<i
                                                    class="material-symbols-outlined textcol">
                                                    query_stats
                                                </i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('statement', $perms) || in_array('refererstatement', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Statements<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('statement', $perms))
                                        <li><a href="{{ url('/statement') }}" class="textcol">Statement<i
                                                    class="material-icons textcol">account_balance</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('refererstatement', $perms))
                                        <li><a href="{{ url('/refererstatement') }}" class="textcol">Referer
                                                Statement<i class="material-icons textcol">account_box</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('payments', $perms) || in_array('addpayment', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Payments<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('payments', $perms))
                                        <li><a href="{{ url('/payments') }}" class="textcol">View Payments<i
                                                    class="material-icons textcol">attach_money</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('addpayment', $perms))
                                        <li><a href="{{ url('/addpayment') }}" class="textcol">Add Payment<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('slr', $perms) || in_array('createslr', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Sales Return<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('slr', $perms))
                                        <li><a href="{{ url('/slr') }}" class="textcol">View Sales Return<i
                                                    class="material-icons textcol">autorenew</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('createslr', $perms))
                                        <li><a href="{{ url('/createslr') }}" class="textcol">Add Sales Return<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('expenses', $perms) || in_array('addexpense', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Expenses<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('expenses', $perms))
                                        <li><a href="{{ url('/expenses') }}" class="textcol">View Expenses<i
                                                    class="material-icons textcol">credit_card</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('addexpense', $perms))
                                        <li><a href="{{ url('/addexpense') }}" class="textcol">Add Expense<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('customers', $perms) || in_array('addcustomer', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Customers<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('customers', $perms))
                                        <li><a href="{{ url('/customers') }}" class="textcol">View Customers<i
                                                    class="material-icons textcol">people</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin')
                                        <li><a href="{{ url('/customeractions') }}" class="textcol">Customer
                                                Actions<i class="material-symbols-outlined textcol">
                                                    manage_accounts
                                                </i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('addcustomer', $perms))
                                        <li><a href="{{ url('/addcustomer') }}" class="textcol">Add Customer<i
                                                    class="material-icons textcol">person_add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('products', $perms) || in_array('addproduct', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Products<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('products', $perms))
                                        <li><a href="{{ url('/products') }}" class="textcol">View Products<i
                                                    class="material-icons textcol">local_mall</i></a></li>
                                    @endif
                                    {{-- @if ($admin->type == 'admin' || in_array('category', $perms))
                                        <li><a href="{{ url('/category') }}" class="textcol">Category<i
                                                    class="material-icons textcol">category</i></a></li>
                                    @endif --}}
                                    @if ($admin->type == 'admin' || in_array('addcustomer', $perms))
                                        <li><a href="{{ url('/addproduct') }}" class="textcol">Add Product<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('subcategory', $perms) || in_array('addsubcategory', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Subcategory<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    @if ($admin->type == 'admin' || in_array('subcategory', $perms))
                                        <li><a href="{{ url('/subcategory') }}" class="textcol">View Subcategory<i
                                                    class="material-icons textcol">format_list_bulleted</i></a></li>
                                    @endif
                                    @if ($admin->type == 'admin' || in_array('addsubcategory', $perms))
                                        <li><a href="{{ url('/addsubcategory') }}" class="textcol">Add Subcategory<i
                                                    class="material-icons textcol">add</i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('batch', $perms))
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Damage<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="{{ url('/batch') }}" class="textcol">Batch<i
                                                class="material-icons textcol">
                                                batch_prediction
                                            </i></a></li>
                                    <li><a href="{{ url('/problem') }}" class="textcol">Problem<i
                                                class="material-icons material-symbols-outlined textcol">
                                                destruction
                                            </i></a></li>
                                    <li><a href="{{ url('/part') }}" class="textcol">Parts<i
                                                class="material-icons material-symbols-outlined textcol">
                                                widgets
                                            </i></a></li>
                                    <li><a href="{{ url('/tickets') }}" class="textcol">Tickets<i
                                                class="material-icons material-symbols-outlined textcol">
                                                heart_broken
                                            </i></a></li>
                                    <li><a href="{{ url('/damage/analytics') }}" class="textcol">Analytics<i
                                                class="material-icons material-symbols-outlined textcol">
                                                chart_data
                                            </i></a></li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin')
                        <li class="bold"><a class="collapsible-header textcol" tabindex="0">Staff<i
                                    class="material-icons chevron textcol">chevron_left</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="{{ url('/staff') }}" class="textcol">View Staff<i
                                                class="material-icons textcol">face</i></a></li>
                                    <li><a href="{{ url('/addstaff') }}" class="textcol">Add Staff<i
                                                class="material-icons textcol">person_add</i></a></li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if ($admin->type == 'admin' || in_array('frontsettings', $perms))
                        <li class="bold"><a href="{{ url('/frontsettings') }}" class="textcol">Front Settings<i
                                    class="material-icons textcol">settings</i></a></li>
                    @endif
                    @if ($admin->type == 'admin')
                        <li class="bold"><a href="{{ url('/trash') }}" class="textcol">Recycle Bin<i
                                    class="material-icons textcol">delete</i></a></li>
                    @endif
                    @if ($admin->type == 'admin')
                        <li class="bold"><a href="{{ url('/sitesettings') }}" class="textcol">Settings<i
                                    class="material-icons textcol">settings</i></a></li>
                    @endif
                </ul>
            </li>
        </ul>



    </header>
    @if ($admin->type != 'marketer')
        <div id="reload-btn" style="display: none;">
            <a href="{{ url('/') }}" class="btn white black-text"
                style="border-radius: 20px; position: fixed; top: 10%; left: 50%;
                   transform: translateX(-50%);"><i
                    class="material-icons left">arrow_upward</i>New Orders</a>
        </div>
    @endif
    <main>
        <div>
            @yield('nmmain')
        </div>
        <div class="mp-container">
            @yield('main')
        </div>
        <div style="margin-top: 50px;">

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
    @if ($admin->type != 'marketer')
        <script>
            if (!window.Notification) {
                console.log('Browser does not support notifications.');
            } else {
                // check if permission is already granted
                if (Notification.permission === 'granted') {
                    // show notification here
                } else {
                    // request permission from user
                    Notification.requestPermission().then(function(p) {
                        if (p === 'granted') {
                            // show notification here
                        } else {
                            console.log('User blocked notifications.');
                        }
                    }).catch(function(err) {
                        console.error(err);
                    });
                }
            }
            let swRegistration = null;
            initializeApp()
            askPermission()

            function initializeApp() {
                if ("serviceWorker" in navigator && "PushManager" in window) {
                    console.log("Service Worker and Push is supported");

                    //Register the service worker
                    navigator.serviceWorker
                        .register("/sw.js")
                        .then(swReg => {
                            console.log("Service Worker is registered", swReg);

                            swRegistration = swReg;
                        })
                        .catch(error => {
                            console.error("Service Worker Error", error);
                        });
                } else {
                    console.warn("Push messaging is not supported");
                    notificationButton.textContent = "Push Not Supported";
                }
            }

            function askPermission() {
                return new Promise(function(resolve, reject) {
                    const permissionResult = Notification.requestPermission(function(result) {
                        resolve(result);
                    });

                    if (permissionResult) {
                        permissionResult.then(resolve, reject);
                    }
                }).then(function(permissionResult) {
                    if (permissionResult !== 'granted') {
                        throw new Error("We weren't granted permission.");
                    }
                });
            }
            $(function() {
                let ip_address = 'socket.startuplair.com';
                // let socket_port = '3000';
                let socket = io(ip_address);

                socket.on('sendnotifToClient', (message) => {
                    // const title = "My power: New Order";
                    // const options = {
                    //     body: message,
                    //     icon: '/assets/logoyellow.png'
                    // };
                    // new Notification(title, options);
                    notification(message);
                    $('#reload-btn').show();
                });
            })

            function notification(message) {
                const options = {
                    body: message,
                    icon: "/assets/logoyellow.png",
                    badge: "/assets/logoyellow.png",
                    vibrate: [500, 110, 500, 110, 450, 110, 200, 110, 170, 40, 450, 110, 200, 110, 170,
                        40, 500,
                    ]
                };
                swRegistration.showNotification("My Power: New Order", options);
            }
            $(function() {
                let ip_address = 'socket.startuplair.com';
                // let socket_port = '3000';
                let socket = io(ip_address);
                let type = ['admin', 'staff', 'marketer']

                socket.on("sendMsgToClient", (message) => {
                    console.log(message);
                    notificationmsg(message);
                    updatemsgcnt();
                })
            });

            function notificationmsg(message) {
                if (message[0].sendtype == 'user') {
                    const options = {
                        body: message[0].message,
                        icon: "/" + message[0].profileimg,
                        badge: "/assets/logoyellow.png",
                        sound: '/notification.wav',
                    };
                    swRegistration.showNotification(message[0].sentname + " : " + message[0].channel, options);
                }
            }

            function updatemsgcnt() {
                $.ajax({
                    url: "/admin/msgcnt",
                    type: 'get',
                    success: function(response) {
                        $('#msgcnt').text(response)
                        $('#msgcnt2').text(response)
                    }
                })
            }
        </script>
    @endif

    <!-- Initialization script -->
    <script src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/dashboard.min.js?v=481680883062710906"></script>
    <script>
        (function($) {
            'use strict';

            let _defaults = {
                classes: '',
                dropdownOptions: {}
            };

            /**
             * @class
             *
             */
            class FormSelect extends Component {
                /**
                 * Construct FormSelect instance
                 * @constructor
                 * @param {Element} el
                 * @param {Object} options
                 */
                constructor(el, options) {
                    super(FormSelect, el, options);

                    // Don't init if browser default version
                    if (this.$el.hasClass('browser-default')) {
                        return;
                    }

                    this.el.M_FormSelect = this;

                    /**
                     * Options for the select
                     * @member FormSelect#options
                     */
                    this.options = $.extend({}, FormSelect.defaults, options);

                    this.isMultiple = this.$el.prop('multiple');

                    // Setup
                    this.el.tabIndex = -1;
                    this._keysSelected = {};
                    this._valueDict = {}; // Maps key to original and generated option element.
                    this._setupDropdown();

                    this._setupEventHandlers();
                }

                static get defaults() {
                    return _defaults;
                }

                static init(els, options) {
                    return super.init(this, els, options);
                }

                /**
                 * Get Instance
                 */
                static getInstance(el) {
                    let domElem = !!el.jquery ? el[0] : el;
                    return domElem.M_FormSelect;
                }

                /**
                 * Teardown component
                 */
                destroy() {
                    this._removeEventHandlers();
                    this._removeDropdown();
                    this.el.M_FormSelect = undefined;
                }

                /**
                 * Setup Event Handlers
                 */
                _setupEventHandlers() {
                    this._handleSelectChangeBound = this._handleSelectChange.bind(this);
                    this._handleOptionClickBound = this._handleOptionClick.bind(this);
                    this._handleInputClickBound = this._handleInputClick.bind(this);

                    $(this.dropdownOptions)
                        .find('li:not(.optgroup)')
                        .each((el) => {
                            el.addEventListener('click', this._handleOptionClickBound);
                        });
                    this.el.addEventListener('change', this._handleSelectChangeBound);
                    this.input.addEventListener('click', this._handleInputClickBound);
                }

                /**
                 * Remove Event Handlers
                 */
                _removeEventHandlers() {
                    $(this.dropdownOptions)
                        .find('li:not(.optgroup)')
                        .each((el) => {
                            el.removeEventListener('click', this._handleOptionClickBound);
                        });
                    this.el.removeEventListener('change', this._handleSelectChangeBound);
                    this.input.removeEventListener('click', this._handleInputClickBound);
                }

                /**
                 * Handle Select Change
                 * @param {Event} e
                 */
                _handleSelectChange(e) {
                    this._setValueToInput();
                }

                /**
                 * Handle Option Click
                 * @param {Event} e
                 */
                _handleOptionClick(e) {
                    e.preventDefault();
                    let optionEl = $(e.target).closest('li')[0];
                    this._selectOption(optionEl);
                    e.stopPropagation();
                }

                _selectOption(optionEl) {
                    let key = optionEl.id;
                    if (!$(optionEl).hasClass('disabled') && !$(optionEl).hasClass('optgroup') && key.length) {
                        let selected = true;

                        if (this.isMultiple) {
                            // Deselect placeholder option if still selected.
                            let placeholderOption = $(this.dropdownOptions).find('li.disabled.selected');
                            if (placeholderOption.length) {
                                placeholderOption.removeClass('selected');
                                placeholderOption.find('input[type="checkbox"]').prop('checked', false);
                                this._toggleEntryFromArray(placeholderOption[0].id);
                            }
                            selected = this._toggleEntryFromArray(key);
                        } else {
                            $(this.dropdownOptions)
                                .find('li')
                                .removeClass('selected');
                            $(optionEl).toggleClass('selected', selected);
                            this._keysSelected = {};
                            this._keysSelected[optionEl.id] = true;
                        }

                        // Set selected on original select option
                        // Only trigger if selected state changed
                        let prevSelected = $(this._valueDict[key].el).prop('selected');
                        if (prevSelected !== selected) {
                            $(this._valueDict[key].el).prop('selected', selected);
                            this.$el.trigger('change');
                        }
                    }

                    if (!this.isMultiple) {
                        this.dropdown.close();
                    }
                }

                /**
                 * Handle Input Click
                 */
                _handleInputClick() {
                    if (this.dropdown && this.dropdown.isOpen) {
                        this._setValueToInput();
                        this._setSelectedStates();
                    }
                }

                /**
                 * Setup dropdown
                 */
                _setupDropdown() {
                    this.wrapper = document.createElement('div');
                    $(this.wrapper).addClass('select-wrapper ' + this.options.classes);
                    this.$el.before($(this.wrapper));
                    // Move actual select element into overflow hidden wrapper
                    let $hideSelect = $('<div class="hide-select"></div>');
                    $(this.wrapper).append($hideSelect);
                    $hideSelect[0].appendChild(this.el);

                    if (this.el.disabled) {
                        this.wrapper.classList.add('disabled');
                    }

                    // Create dropdown
                    this.$selectOptions = this.$el.children('option, optgroup');
                    this.dropdownOptions = document.createElement('ul');
                    this.dropdownOptions.id = `select-options-${M.guid()}`;
                    $(this.dropdownOptions).addClass(
                        'dropdown-content select-dropdown ' + (this.isMultiple ? 'multiple-select-dropdown' :
                            '')
                    );


                    //Added to search
                    this.searchable = this.el.getAttribute('searchable') ? true : false;
                    if (this.searchable) {
                        this.options.dropdownOptions.autoFocus = false;

                        var placeholder = this.el.getAttribute('searchable');
                        var searchinput = this.el.getAttribute('serachname');
                        var element = $(
                            '<div class="input-field col m6"><input type="text" class="dropDownsearch" id="' +
                            searchinput + '" style="margin: 5px 0px 16px 15px; width: 96%;"> <label for="' +
                            searchinput + '">' + placeholder + '</label></div>');
                        $(this.dropdownOptions).append(element);
                        element.children().first().on('keyup', function(event) {
                            applySeachInList(this.value);
                        });

                        var applySeachInList = (s) => {
                            var searchVlaue = s.toLowerCase();
                            $(this.dropdownOptions).find('li').each((item) => {
                                var current = $(item);
                                var liValue = current.text().toLowerCase();

                                if (liValue.indexOf(searchVlaue) === -1) {
                                    current.css({
                                        display: 'none'
                                    });
                                } else {
                                    current.css({
                                        display: 'block'
                                    });
                                }
                            });
                            this.dropdown.recalculateDimensions();
                        };
                    }
                    // End searchable
                    // Create dropdown structure.
                    if (this.$selectOptions.length) {
                        this.$selectOptions.each((el) => {
                            if ($(el).is('option')) {
                                // Direct descendant option.
                                let optionEl;
                                if (this.isMultiple) {
                                    optionEl = this._appendOptionWithIcon(this.$el, el, 'multiple');
                                } else {
                                    optionEl = this._appendOptionWithIcon(this.$el, el);
                                }

                                this._addOptionToValueDict(el, optionEl);
                            } else if ($(el).is('optgroup')) {
                                // Optgroup.
                                let selectOptions = $(el).children('option');
                                $(this.dropdownOptions).append(
                                    $('<li class="optgroup"><span>' + el.getAttribute('label') +
                                        '</span></li>')[0]
                                );

                                selectOptions.each((el) => {
                                    let optionEl = this._appendOptionWithIcon(this.$el, el,
                                        'optgroup-option');
                                    this._addOptionToValueDict(el, optionEl);
                                });
                            }
                        });
                    }

                    $(this.wrapper).append(this.dropdownOptions);

                    // Add input dropdown
                    this.input = document.createElement('input');
                    $(this.input).addClass('select-dropdown dropdown-trigger');
                    this.input.setAttribute('type', 'text');
                    this.input.setAttribute('readonly', 'true');
                    this.input.setAttribute('data-target', this.dropdownOptions.id);
                    if (this.el.disabled) {
                        $(this.input).prop('disabled', 'true');
                    }

                    $(this.wrapper).prepend(this.input);
                    this._setValueToInput();

                    // Add caret
                    let dropdownIcon = $(
                        '<svg class="caret" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'
                    );
                    $(this.wrapper).prepend(dropdownIcon[0]);

                    // Initialize dropdown
                    if (!this.el.disabled) {
                        let dropdownOptions = $.extend({}, this.options.dropdownOptions);
                        let userOnOpenEnd = dropdownOptions.onOpenEnd;

                        // Add callback for centering selected option when dropdown content is scrollable
                        dropdownOptions.onOpenEnd = (el) => {
                            let selectedOption = $(this.dropdownOptions)
                                .find('.selected')
                                .first();

                            if (selectedOption.length) {
                                // Focus selected option in dropdown
                                M.keyDown = true;
                                this.dropdown.focusedIndex = selectedOption.index();
                                this.dropdown._focusFocusedItem();
                                M.keyDown = false;

                                // Handle scrolling to selected option
                                if (this.dropdown.isScrollable) {
                                    let scrollOffset =
                                        selectedOption[0].getBoundingClientRect().top -
                                        this.dropdownOptions.getBoundingClientRect()
                                        .top; // scroll to selected option
                                    scrollOffset -= this.dropdownOptions.clientHeight / 2; // center in dropdown
                                    this.dropdownOptions.scrollTop = scrollOffset;
                                }
                            }

                            // Handle user declared onOpenEnd if needed
                            if (userOnOpenEnd && typeof userOnOpenEnd === 'function') {
                                userOnOpenEnd.call(this.dropdown, this.el);
                            }
                        };

                        // Prevent dropdown from closeing too early
                        dropdownOptions.closeOnClick = false;

                        this.dropdown = M.Dropdown.init(this.input, dropdownOptions);
                    }

                    // Add initial selections
                    this._setSelectedStates();
                }

                /**
                 * Add option to value dict
                 * @param {Element} el  original option element
                 * @param {Element} optionEl  generated option element
                 */
                _addOptionToValueDict(el, optionEl) {
                    let index = Object.keys(this._valueDict).length;
                    let key = this.dropdownOptions.id + index;
                    let obj = {};
                    optionEl.id = key;

                    obj.el = el;
                    obj.optionEl = optionEl;
                    this._valueDict[key] = obj;
                }

                /**
                 * Remove dropdown
                 */
                _removeDropdown() {
                    $(this.wrapper)
                        .find('.caret')
                        .remove();
                    $(this.input).remove();
                    $(this.dropdownOptions).remove();
                    $(this.wrapper).before(this.$el);
                    $(this.wrapper).remove();
                }

                /**
                 * Setup dropdown
                 * @param {Element} select  select element
                 * @param {Element} option  option element from select
                 * @param {String} type
                 * @return {Element}  option element added
                 */
                _appendOptionWithIcon(select, option, type) {
                    // Add disabled attr if disabled
                    let disabledClass = option.disabled ? 'disabled ' : '';
                    let optgroupClass = type === 'optgroup-option' ? 'optgroup-option ' : '';
                    let multipleCheckbox = this.isMultiple ?
                        `<label><input type="checkbox"${disabledClass}"/><span>${option.innerHTML}</span></label>` :
                        option.innerHTML;
                    let liEl = $('<li></li>');
                    let spanEl = $('<span></span>');
                    spanEl.html(multipleCheckbox);
                    liEl.addClass(`${disabledClass} ${optgroupClass}`);
                    liEl.append(spanEl);

                    // add icons
                    let iconUrl = option.getAttribute('data-icon');
                    if (!!iconUrl) {
                        let imgEl = $(`<img alt="" src="${iconUrl}">`);
                        liEl.prepend(imgEl);
                    }

                    // Check for multiple type.
                    $(this.dropdownOptions).append(liEl[0]);
                    return liEl[0];
                }

                /**
                 * Toggle entry from option
                 * @param {String} key  Option key
                 * @return {Boolean}  if entry was added or removed
                 */
                _toggleEntryFromArray(key) {
                    let notAdded = !this._keysSelected.hasOwnProperty(key);
                    let $optionLi = $(this._valueDict[key].optionEl);

                    if (notAdded) {
                        this._keysSelected[key] = true;
                    } else {
                        delete this._keysSelected[key];
                    }

                    $optionLi.toggleClass('selected', notAdded);

                    // Set checkbox checked value
                    $optionLi.find('input[type="checkbox"]').prop('checked', notAdded);

                    // use notAdded instead of true (to detect if the option is selected or not)
                    $optionLi.prop('selected', notAdded);

                    return notAdded;
                }

                /**
                 * Set text value to input
                 */
                _setValueToInput() {
                    let values = [];
                    let options = this.$el.find('option');

                    options.each((el) => {
                        if ($(el).prop('selected')) {
                            let text = $(el).text();
                            values.push(text);
                        }
                    });

                    if (!values.length) {
                        let firstDisabled = this.$el.find('option:disabled').eq(0);
                        if (firstDisabled.length && firstDisabled[0].value === '') {
                            values.push(firstDisabled.text());
                        }
                    }

                    this.input.value = values.join(', ');
                }

                /**
                 * Set selected state of dropdown to match actual select element
                 */
                _setSelectedStates() {
                    this._keysSelected = {};

                    for (let key in this._valueDict) {
                        let option = this._valueDict[key];
                        let optionIsSelected = $(option.el).prop('selected');
                        $(option.optionEl)
                            .find('input[type="checkbox"]')
                            .prop('checked', optionIsSelected);
                        if (optionIsSelected) {
                            this._activateOption($(this.dropdownOptions), $(option.optionEl));
                            this._keysSelected[key] = true;
                        } else {
                            $(option.optionEl).removeClass('selected');
                        }
                    }
                }

                /**
                 * Make option as selected and scroll to selected position
                 * @param {jQuery} collection  Select options jQuery element
                 * @param {Element} newOption  element of the new option
                 */
                _activateOption(collection, newOption) {
                    if (newOption) {
                        if (!this.isMultiple) {
                            collection.find('li.selected').removeClass('selected');
                        }
                        let option = $(newOption);
                        option.addClass('selected');
                    }
                }

                /**
                 * Get Selected Values
                 * @return {Array}  Array of selected values
                 */
                getSelectedValues() {
                    let selectedValues = [];
                    for (let key in this._keysSelected) {
                        selectedValues.push(this._valueDict[key].el.value);
                    }
                    return selectedValues;
                }
            }

            M.FormSelect = FormSelect;

            if (M.jQueryLoaded) {
                M.initializeJqueryWrapper(FormSelect, 'formSelect', 'M_FormSelect');
            }
        })(cash);
    </script>
</body>

</html>
