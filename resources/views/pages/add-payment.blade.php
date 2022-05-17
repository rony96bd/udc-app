@extends('pages.layouts.main')
@section('main-container')
    <!-- Begin Page Content -->
    <div class="container">
        {{--                @if($user->is_admin ==0)--}}
        <div class="d-flex justify-content-center">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Payment</h6>
                    </div>
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <form action="{{ Route('add-payment') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Amount (Taka)</label>
                                        <input type="text" name="taka" class="form-control" id="taka" placeholder="Enter Amount">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Transection ID/Mobile No.</label>
                                        <input type="text" class="form-control" name="trid" id="trid" placeholder="Enter Transection ID/Mobile No.">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--                @endif--}}
    </div>
    <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
@endsection
