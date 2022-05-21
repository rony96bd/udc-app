@extends('pages.layouts.main')
@section('main-container')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales For Payment -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 style="float: left" class="m-0 font-weight-bold text-primary">Payments</h4>
                <a href="{{ URL::to('/add-payment') }}">
                    <button type="button" class="btn btn-success float-right"><i class="fas fa-plus-circle pt-1"></i>
                        Add Payment
                    </button>
                </a>
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
                                $payments = App\Models\Payment::join('users', 'users.id', '=', 'payments.user_id')->get(['payments.*', 'users.name', 'users.email']);
                            } else {
                                $payments = App\Models\Payment::join('users', 'users.id', '=', 'payments.user_id')
                                    ->where('payments.user_id', $user->id)
                                    ->get(['payments.*', 'users.name', 'users.email']);
                            }
                        @endphp
                        @foreach ($payments as $payment)
                            @switch($payment->status)
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
                            <tr>
                                <td>{{ $payment->created_at }}</td>
                                <td>{{ $payment->name }} ({{ $payment->email }})</td>
                                <td>{{ $payment->taka }}</td>
                                <td>{{ $payment->transaction_id }}</td>
                                <form action="{{ Route('update-payment') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $payment->id }}"/>
                                    <td style="color: {{ $txtcol }};" align="center">
                                        @if ($user->is_admin == '0')
                                        {{ $payment->status }}
                                        @endif
                                        @if ($user->is_admin == '1')
                                        <select style="color: {{ $txtcol }}" name="status" class="form-select center">
                                            <option>{{ $payment->status }}</option>
                                            <option style="color: rgb(9, 214, 9);">Approved</option>
                                            <option style="color: red;">Reject</option>
                                            <option style="color: blue;">Pending</option>
                                        </select>
                                        @endif
                                    </td>
                                    @if ($user->is_admin == '1')
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-sm"><i
                                                    class="fas fa-save"></i></button>
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
