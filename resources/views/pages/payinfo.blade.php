@extends('pages.layouts.main')
@section('main-container')
                <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- DataTales For Payment -->
                @if($user->is_admin ==1)
                    <!-- DataTales For Payment -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Payments</h6>
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
                                        <th>Action</th>
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
                                            <td>
                                                <a href="#">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
@endsection
