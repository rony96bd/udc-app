@extends('pages.layouts.main')
@section('main-container')
                <!-- Begin Page Content -->
            <div class="container-fluid">

                @if($user->is_admin ==1)
                    <!-- DataTales For Users -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Rate</th>
                                        <th>Account Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $users = App\Models\User::all();
                                    @endphp
                                    @foreach ($users as $usr)
                                        @php
                                            $total_payable = App\Models\Brid::where('user_id', $usr->id)
                                                ->where('status', 'Approved')
                                                ->sum('rate');
                                            $paid = App\Models\Payment::where('user_id', $usr->id)
                                                ->where('status', 'Approved')
                                                ->sum('taka');
                                            $balance = $total_payable - $paid;
                                        @endphp
                                        <tr>
                                            <td>{{ $usr->id }}</td>
                                            <td>{{ $usr->name }}</td>
                                            <td>{{ $usr->email }}</td>
                                            <td>{{ $usr->rate }}</td>
                                            <td>{{ $balance }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DataTales For Payment -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Paid By</th>
                                    <th>Taka</th>
                                    <th>Transaction Id</th>
                                    <th>Status</th>
                                    @if ($user->is_admin == '1')
                                        <th>Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    if ($user->is_admin == '1') {
                                        $payments = App\Models\Payment::join('users', 'users.id', '=', 'payments.user_id')->get(['payments.*', 'users.name']);
                                    } else {
                                        $payments = App\Models\Payment::join('users', 'users.id', '=', 'payments.user_id')
                                            ->where('payments.user_id', $user->id)
                                            ->get(['payments.*', 'users.name']);
                                    }
                                @endphp
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at }}</td>
                                        <td>{{ $payment->name }}</td>
                                        <td>{{ $payment->taka }}</td>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>{{ $payment->status }}</td>
                                        @if ($user->is_admin == '1')
                                            <td>
                                                <a href="#">Edit</a>
                                            </td>
                                        @endif
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
