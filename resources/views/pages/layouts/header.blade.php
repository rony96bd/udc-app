<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Approver</title>

    <link rel="icon" href="{{ url('src/thumbs-up-solid.svg') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->

    {{-- <link href="{{ url('src/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('src/css/v-ticker.css') }}" rel="stylesheet">
    <link href="{{ url('src/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ url('src/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .selected {
            background-color: #e8e8e8;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ URL::to('/') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Approver</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            @php
                $menustatus = Route::currentRouteName();
            @endphp

            <!-- Nav Item - Dashboard -->
            <li class="nav-item @php if($menustatus=='dashboard'){echo 'active'; }  @endphp">
                <a class="nav-link" href="{{ URL::to('/') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>HomePage</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item @php if($menustatus=='allUser'){echo 'active'; }  @endphp">
                <a class="nav-link" href="{{ URL::to('/alluser') }}">
                    <i class="fas fa-user"></i>
                    <span>Users</span>
                </a>

            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item @php if($menustatus=='payinfo'){echo 'active'; }  @endphp">
                <a class="nav-link" href="{{ URL::to('/payinfo') }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payment Info</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"><i
                        class="fa-solid fa-angle-left"></i></button>
            </div>

            <!-- Sidebar Message -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            @php
                $user = Auth()->user();

                $total_payable = App\Models\Brid::where('user_id', $user->id)
                    ->where('status', 'Approved')
                    ->sum('rate');
                $paid = App\Models\Payment::where('user_id', $user->id)
                    ->where('status', 'Approved')
                    ->sum('taka');

                $balance = $paid - $total_payable;

                $total_payable_admin = App\Models\Brid::where('status', 'Approved')->sum('rate');
                $paid_admin = App\Models\Payment::where('status', 'Approved')->sum('taka');

                $balance_admin = $total_payable_admin - $paid_admin;

                if ($balance < -500) {
                    $badge_color = 'danger';
                } else {
                    $badge_color = 'success';
                }

            @endphp

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div class="hwrap">
                        <div class="hmove">
                            <div class="hitem"><i class="fa-solid fa-angles-right"></i> ১০০০+ ব্যালেন্স হয়ে গেলেই পেমেন্ট না করা পর্যন্ত আর আইডি পাঠানো যাবে না।</div>
                            <div class="hitem"><i class="fa-solid fa-angles-right"></i> প্রতি মাসের শেষে সবার ব্যালেন্স ০ করতে হবে, যদি ৫০০ টাকার কমও হয়।</div>
                            <div class="hitem"><i class="fa-solid fa-angles-right"></i> অনেক সময় অফিসের গুরুত্বপূর্ন কাজ থাকার কারণে সঙ্গে সঙ্গে অনুমোদন দেয়া সম্ভব হয় না। আমার Status এবং নোটিশের জন্য ওয়াটসএ্যাপ গ্রুপে জয়েন করুন: <a href="https://chat.whatsapp.com/BVlFrtfBFGD633KxKDmx9R">https://chat.whatsapp.com/BVlFrtfBFGD633KxKDmx9R</a></div>
                        </div>
                    </div>
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Balance Show -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-money-bill fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-{{ $badge_color }}">
                                    @if ($user->is_admin == '1')
                                        {{ $balance_admin }}
                                    @else
                                        {{ $balance }}
                                    @endif
                                </span>
                            </a>
                        </li>
                        <!-- Nav Item - Alerts -->
                        {{-- <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">Updated notification here...!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All
                                    Alerts</a>
                            </div>
                        </li> --}}

                        <!-- Nav Item - Messages -->
                        {{-- <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message From Admin
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div>
                                        <div class="text-truncate">Admin Message here...</div>
                                        <div class="small text-gray-500">Message Time:</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More
                                    Messages</a>
                            </div>
                        </li> --}}

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                <i class="fas fa-user blue" style="color: dodgerblue;"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fa-solid fa-sack-dollar fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Balance:
                                    @if ($user->is_admin == '1')
                                        {{ $balance_admin }}
                                    @else
                                        {{ $balance }}
                                    @endif
                                </a>
                                <a class="dropdown-item" href="{{ route('change-password') }}">
                                    <i class="fa-solid fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                        <!-- Logout Modal-->
                        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                        <button class="close" type="button" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">Select "Logout" below if you are ready to end your current
                                        session.
                                    </div>
                                    <div class="modal-footer">

                                        <button class="btn btn-secondary" type="button"
                                            data-dismiss="modal">Cancel</button>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="btn btn-primary" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                this.closest('form').submit();">Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </nav>
                <!-- End of Topbar -->
