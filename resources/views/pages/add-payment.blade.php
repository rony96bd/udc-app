@extends('pages.layouts.main')
@section('main-container')
    <!-- Begin Page Content -->
    <div class="container">

        {{--                @if($user->is_admin ==0)--}}
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
            @endif
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
                                        <input type="number" name="taka" class="form-control" id="taka" placeholder="Enter Amount">
                                    </div>
                                    <div class="form-group">
                                        <label for="PaymentMethod">Payment Method</label>
                                        <select id="paymethod" name="paymethod" class="custom-select form-control">
                                            <option value="bkash">Bkash</option>
                                            <option value="nagad">Nagad</option>
                                            <option value="rocket">Rocket</option>
                                            <option value="cash">Cash</option>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Transaction">Transection ID</label>
                                        <input type="text" class="form-control" name="trid" id="trid" placeholder="Enter Transection ID">
                                    </div>
                                    <div class="form-group">
                                        <label for="Mobile">Mobile No.</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile No.">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div style="color: red; text-align: center;"><i class="fa-solid fa-angles-right"></i> পেমেন্ট করে অবশ্যই Transantion ID, টাকার পরিমাণ ও মোবাইল নম্বর সঠিক দিতে হবে। </div>
                </div>
            </div>
        </div>
        {{--                @endif--}}
    </div>
    <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
@endsection
