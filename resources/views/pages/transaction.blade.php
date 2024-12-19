@extends('pages.layouts.main')
@section('main-container')
<div class="container-fluid" style="height: 100%">
    <!-- Begin Page Content -->
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    @if (session()->has('danger'))
        <div class="alert alert-danger">
            {{ session()->get('danger') }}
        </div>
    @endif

    <div class="row">
        <!-- ID Send Card -->
        @if ($user->is_admin == '1')
            <div class="col-xl-12 col-md-12 mb-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-center font-weight-bold text-primary text-uppercase mb-1">
                                    Send Transaction ID's
                                </div>
                                <div class="text-center h5 mb-0 font-weight-bold text-gray-800">
                                    <form action="{{ Route('transaction_add') }}" method="POST" class="d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                        @csrf
                                            <div class="input-group">
                                                <input name="transaction" required type="text" autofocus class="form-control bg-light border-1 small" placeholder="Write Transaction ID" aria-label="Send TID" aria-describedby="basic-addon2">
                                                <input name="taka" required type="number" class="form-control bg-light border-1" aria-label="Taka" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane fa-sm"></i></button>
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

    <div class="container">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="transaction-table" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction ID's</th>
                        <th>Amount (Taka)</th>
                        <th>Mobile No.</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at }}</td>
                            <td>{{ $transaction->transaction_id }}</td>
                            <td>{{ $transaction->taka }}</td>
                            <td>
                                @php
                                    if ($transaction->mobile == null) {
                                        echo "Mobile No. Empty";
                                    } else {
                                        echo $transaction->mobile;
                                    }
                                @endphp
                            </td>
                            <td>{{ $transaction->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- End of Main Content -->
@endsection
