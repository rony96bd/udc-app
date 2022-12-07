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
                                <th>User Name</th>
                                <th>Approved IDs</th>
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
                                    $balance = $paid - $total_payable;

                                    $approved = App\Models\Brid::where('user_id', $usr->id)
                                    ->where('status', 'Approved')
                                    ->count();
                                @endphp
                                <tr>
                                    <td>{{ $usr->id }}</td>
                                    <td>{{ $usr->name }}</td>
                                    <td>{{ $usr->email }}</td>
                                    <td>{{ $approved }}</td>
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

            @php
                $user = Auth()->user();

                $total_payable = App\Models\Brid::where('user_id', $user->id)
                    ->where('status', 'Approved')
                    ->sum('rate');
                $paid = App\Models\Payment::where('user_id', $user->id)
                    ->where('status', 'Approved')
                    ->sum('taka');

                $balance2 = $paid - $total_payable;
            @endphp

                        @if($user->is_admin ==0)
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

                                <tr>
                                    <td>{{ auth()->user()->id }}</td>
                                    <td>{{ auth()->user()->name }}</td>
                                    <td>{{ auth()->user()->email }}</td>
                                    <td>{{ auth()->user()->rate }}</td>
                                    <td>{{ $balance2 }}</td>
                                </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        @endif

    <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
@endsection
