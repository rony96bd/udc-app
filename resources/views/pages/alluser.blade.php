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
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
@endsection
