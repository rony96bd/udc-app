@extends('pages.layouts.main')
@section('main-container')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                           data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
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
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                        </div>
                    </li>

                    <!-- Nav Item - Messages -->
                    <li class="nav-item dropdown no-arrow mx-1">
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
                            <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <span
                                class="mr-2 d-none d-lg-inline text-gray-600 small">Hi, {{ Auth::user()->name }}</span>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault();
                                                                                                                                                                                                                                                                                        this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-6"></div>

                <!-- Content Row -->
                <div class="row">
                    <!-- ID Send Card -->
                    <div class="col-xl-12 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs text-center font-weight-bold text-primary text-uppercase mb-1">
                                            Send ID's
                                        </div>
                                        <div class="text-center h5 mb-0 font-weight-bold text-gray-800">
                                            <form action="{{ Route('addBr') }}" method="POST"
                                                  class="d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                                @csrf
                                                <div class="input-group">
                                                    <input name="brid" type="text"
                                                           class="form-control bg-light border-0 small"
                                                           placeholder="Write ID"
                                                           aria-label="Send ID" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="submit">
                                                            <i class="fas fa-paper-plane fa-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Heading -->

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">ID's Result</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>BR Applicatin ID</th>
                                    <th>Status</th>
                                    <th>ID Type</th>
                                    <th width="12px">Rate</th>
                                    <th>Message</th>
                                    @if (Auth::user()->is_admin == '1')
                                        <th>action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($brIds as $brId)
                                    <tr>
                                        <form action="{{ Route('updBr') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="data-id" value="{{ $brId->id }}"/>
                                            <td>{{ date('d-m-Y', strtotime($brId->created_at)) }}</td>
                                            <td>{{ $brId->brid }}</td>

                                            @if (Auth::user()->is_admin == '0')
                                                <td>{{ $brId->status }}</td>
                                            @endif

                                            @if (Auth::user()->is_admin == '1')
                                                @php
                                                    if ($brId->status == 'Approved') {
                                                        $txtcol = 'rgb(9, 214, 9)';
                                                    } elseif ($brId->status == 'Reject') {
                                                        $txtcol = 'red';
                                                    } elseif ($brId->status == 'Pending') {
                                                        $txtcol = 'blue';
                                                    } else {
                                                        $txtcol = 'Black';
                                                    }
                                                @endphp
                                                <td align="center" style="color: {{ $txtcol }};">
                                                    {{ $brId->status }}<br/>
                                                    <select name="status" class="form-select center">
                                                        <option>Approved</option>
                                                        <option>Reject</option>
                                                        <option>Pending</option>
                                                    </select>
                                                </td>
                                            @endif

                                            @if (Auth::user()->is_admin == '0')
                                                <td>{{ $brId->id_type }}</td>
                                            @endif

                                            @if (Auth::user()->is_admin == '1')
                                                <td align="center">
                                                    {{ $brId->id_type }}<br/>
                                                    <select name="id_type" class="form-select center">
                                                        <option>Regular</option>
                                                        <option>DoB Correction</option>
                                                    </select>
                                                </td>
                                            @endif

                                            @if (Auth::user()->is_admin == '0')
                                                <td>{{ $brId->rate }}</td>
                                            @endif

                                            @if (Auth::user()->is_admin == '1')
                                                <td align="center">
                                                    <input class="form-control" name="rate" type="text"
                                                           placeholder="{{ $brId->rate }}">
                                                </td>
                                            @endif

                                            @if (Auth::user()->is_admin == '0')
                                                <td>{{ $brId->messages }}</td>
                                            @endif

                                            @if (Auth::user()->is_admin == '1')
                                                <td>
                                                    <textarea name="message" class="form-control" rows="1"
                                                              placeholder="{{ $brId->messages }}"></textarea>
                                                </td>
                                            @endif

                                            @if (Auth::user()->is_admin == '1')
                                                <td>
                                                    <button type="submit" value="Save">Save</button>
                                                </td>
                                            @endif
                                        </form>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
@endsection
