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
    <link rel="stylesheet" href="{{asset('/assets/'.$admin->mode.".css")}}">
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
                    </ul><a href="#!" data-target="sidenav-left" class="sidenav-trigger left"><i
                            class="material-icons textcol">menu</i></a>
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
                                            class="black-text">Bulk Print<i class="material-icons black-text">print</i></a>
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
                                                class="material-icons textcol">show_chart</i></a></li>
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
                </ul>
            </li>
        </ul>



    </header>
    @if($admin->type != 'marketer')
                <div id="reload-btn" style="display: none;">
                   <a href="{{url('/')}}" class="btn white black-text" style="border-radius: 20px; position: fixed; top: 10%; left: 50%;
                   transform: translateX(-50%);"><i class="material-icons left">arrow_upward</i>New Orders</a> 
                </div>
        @endif
    <main>
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
        //       if (!window.Notification) {
        //     console.log('Browser does not support notifications.');
        // } else {
        //     // check if permission is already granted
        //     if (Notification.permission === 'granted') {
        //         // show notification here
        //     } else {
        //         // request permission from user
        //         Notification.requestPermission().then(function(p) {
        //            if(p === 'granted') {
        //                // show notification here
        //            } else {
        //                console.log('User blocked notifications.');
        //            }
        //         }).catch(function(err) {
        //             console.error(err);
        //         });
        //     }
        // }
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
                    vibrate: [200, 100, 200]
                };
                swRegistration.showNotification("My Power: New Order", options);
            }
    </script>
    @endif

    <!-- Initialization script -->
    <script src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/dashboard.min.js?v=481680883062710906"></script>
</body>

</html>
