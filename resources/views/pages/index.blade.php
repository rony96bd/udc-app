@extends('pages.layouts.main')
@section('main-container')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <div class="d-sm-flex align-items-center justify-content-between mb-6"></div> --}}

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
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

            if ($user->is_admin == '0') {
                $payments_date = DB::table('payments')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'DESC')
                    ->first(['created_at']);


                    $last_pay_day = strtotime($payments_date->created_at ?? '2022-01-01');


                $now = time();
                $datediff = $now - $last_pay_day;

                $day_diff = round($datediff / (60 * 60 * 24));

                if ($balance < -500 && $day_diff > 7) {
                    $autofocus = '';
                } else {
                    $autofocus = 'autofocus = "on"';
                }

                if ($balance < -1000) {
                    $div_disable = 'display:none';
                } else {
                    $div_disable = '';
                }
            }

        @endphp

        <!-- Content Row -->
        <div class="row">
            <!-- ID Send Card -->
            @if ($user->is_admin == '0')
                <div class="col-xl-12 col-md-6 mb-4">

                    <div class="card border-left-primary shadow h-100 py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs text-center font-weight-bold text-primary text-uppercase mb-1">
                                        Send ID's
                                    </div>
                                    <div class="text-center h5 mb-0 font-weight-bold text-gray-800">
                                        <form action="{{ Route('addBr') }}" method="POST"
                                            class="d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                            @csrf
                                            <div class="input-group" style={{ $div_disable }}>
                                                <input name="brid" type="text" {{ $autofocus }}
                                                    class="form-control bg-light border-0 small" placeholder="Write ID"
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
            @endif
        </div>

        <!-- Page Heading -->

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 style="float: left" class="m-0 font-weight-bold text-primary">Results</h4>
                @if ($user->is_admin == '1')
                    <div style="float: right">
                        <span><button type="button" class="btn btn-success" id="exampleApprove">
                                Approve
                            </button></span>
                        <span>
                            <button type="button" class="btn btn-danger" id="exampleReject">
                                Reject
                            </button>
                        </span>
                        <span>
                            <button type="button" class="btn btn-danger" id="exampleDelete">
                                Delete
                            </button>
                        </span>
                    </div>
                @endif
            </div>
            <!-- Admin Table -->
            @if ($user->is_admin == '1')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>BR Applicatin ID</th>
                                    <th>Status</th>
                                    <th>ID Type</th>
                                    {{-- <th style="width: 56px;">Rate</th> --}}
                                    <th>Message</th>
                                    @if ($user->is_admin == '1')
                                        <th>Requested</th>
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brIds as $brId)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            {{ date('d-m-Y H:i:s', strtotime($brId->created_at)) }}</td>
                                        <td style="vertical-align: middle;">
                                            <span id="{{ $brId->brid }}">{{ $brId->brid }}</span>
                                            <button class="badge badge-counter btn btn-primary"
                                                data-desc-ref="{{ $brId->brid }}" type="button" value="Copy"
                                                id="btn" onclick="status(this)"><i
                                                    class="fas fa-copy fa-sm"></i></button>
                                        </td>
                                        @switch($brId->status)
                                            @case('Approved')
                                                @php $txtcol = 'rgb(9, 214, 9)' @endphp
                                            @break

                                            @case('Reject')
                                                @php $txtcol = 'red' @endphp
                                            @break

                                            @case('Pending')
                                                @php $txtcol = 'blue' @endphp
                                            @break

                                            @default
                                                @php $txtcol = 'black' @endphp
                                        @endswitch

                                        <form action="{{ Route('updBr') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $brId->id }}" />
                                            <td style="vertical-align: middle; color: {{ $txtcol }};" align="center">
                                                @if ($user->is_admin == '0')
                                                    {{ $brId->status }}
                                                @endif

                                                @if ($user->is_admin == '1')
                                                    <select style="color: {{ $txtcol }};" name="status"
                                                        onchange='if(this.value != 0) { this.form.submit(); }'
                                                        class="form-select center">
                                                        <option>{{ $brId->status }}</option>
                                                        <option style="color: rgb(9, 214, 9);">Approved</option>
                                                        <option style="color: red;">Reject</option>
                                                        <option style="color: blue;">Pending</option>
                                                    </select>
                                                @endif
                                            </td>

                                            <td style="vertical-align: middle;" align="center">
                                                @if ($user->is_admin == '0')
                                                    {{ $brId->id_type }}
                                                @endif
                                                @if ($user->is_admin == '1')
                                                    <select name="id_type" class="form-select center">
                                                        <option>{{ $brId->id_type }}</option>
                                                        <option>Regular</option>
                                                        <option>DoB Correction</option>
                                                    </select>
                                                @endif
                                            </td>

                                            {{-- <td style="vertical-align: middle; width: 56px;" align="center">
                                                @if ($user->is_admin == '0')
                                                    {{ $brId->rate }}
                                                @endif
                                                @if ($user->is_admin == '1')
                                                    <input class="form-control text-center" name="rate" type="text"
                                                        placeholder="{{ $brId->rate }}" value="{{ $brId->rate }}" />
                                                @endif
                                            </td> --}}

                                            <td style="vertical-align: middle;" align="center">
                                                @if ($user->is_admin == '0')
                                                    {{ $brId->message }}
                                                @endif
                                                @if ($user->is_admin == '1')
                                                    <textarea name="message" class="form-control" rows="1">{{ $brId->message }}</textarea>
                                                @endif
                                            </td>
                                            @if ($user->is_admin == '1')
                                                <td style="vertical-align: middle;">
                                                    {{-- <span>{{ $brId->name }}</span><br> --}}
                                                    <span>{{ $brId->email }}</span>
                                                </td>
                                                <td class="d-flex justify-content-between" style="vertical-align: middle;">
                                                    <span> <button type="submit" class="btn btn-primary btn-sm"><i
                                                                class="fas fa-save"></i></button> </span>
                                        </form>
                                        <form action="{{ route('deleteBr', $brId->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <span> <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i>
                                                </button></span>
                                        </form>
                                        </td>
                                @endif
                                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
    @endif
    <!-- User Table -->
    @if ($user->is_admin == '0')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="example-user">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>BR Applicatin ID</th>
                            <th>Status</th>
                            <th>ID Type</th>
                            <th style="width: 56px;">Rate</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brIds as $brId)
                            <tr>
                                <td style="vertical-align: middle;">
                                    {{ date('d-m-Y H:i:s', strtotime($brId->created_at)) }}</td>
                                <td style="vertical-align: middle;">
                                    <span id="{{ $brId->brid }}">{{ $brId->brid }}</span>
                                    <button class="badge badge-counter btn btn-primary"
                                        data-desc-ref="{{ $brId->brid }}" type="button" value="Copy"
                                        id="btn" onclick="status(this)"><i class="fas fa-copy fa-sm"></i></button>
                                </td>
                                @switch($brId->status)
                                    @case('Approved')
                                        @php $txtcol = 'rgb(9, 214, 9)' @endphp
                                    @break

                                    @case('Reject')
                                        @php $txtcol = 'red' @endphp
                                    @break

                                    @case('Pending')
                                        @php $txtcol = 'blue' @endphp
                                    @break

                                    @default
                                        @php $txtcol = 'black' @endphp
                                @endswitch
                                <td style="vertical-align: middle; color: {{ $txtcol }};" align="center">
                                    {{ $brId->status }}
                                </td>
                                <td style="vertical-align: middle;" align="center">
                                    {{ $brId->id_type }}
                                </td>
                                <td style="vertical-align: middle; width: 56px;" align="center">
                                    {{ $brId->rate }}
                                </td>
                                <td style="vertical-align: middle;" align="center">
                                    {{ $brId->message }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    </div>
    </div>
    <!-- /.container-fluid -->
    </div>
    <div id="myModal" class="modal fade">
        <div class="modal-dialog text-center">
            <div class="modal-content bg-danger text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Aleart</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>ব্যালেন্স ৫০০+ এবং ৮ দিনের মধ্যে কোন পেমেন্ট করেননি</p>
                    <h3>দয়া করে পেমেন্ট করুন</h3>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal2" class="modal fade">
        <div class="modal-dialog text-center">
            <div class="modal-content bg-danger text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Aleart</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>যাদের ব্যালেন্স ১০০০+ হয়ে গেছে। তাদের আইডি পাঠানো ডিসেবল করা হচ্ছে।</p>
                    <h3>দয়া করে পেমেন্ট করুন</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- End of Main Content -->
@endsection
