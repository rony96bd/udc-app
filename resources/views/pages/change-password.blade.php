@extends('pages.layouts.main')
@section('main-container')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales For Payment -->

            <!-- DataTales For Payment -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <br>
                    <form action="{{ route('change-password') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label for="old_password" class="col-4 col-form-label">Old Password</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                    <input id="old_password" name="old_password" placeholder="Type your old password..." type="password" class="form-control @error('old_password') is-valid @enderror" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password" class="col-4 col-form-label">New password</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                    <input id="new_password" name="new_password" placeholder="Type your new password..." type="password" class="form-control" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label" for="confirm_new_password">Confirm New Pasword</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                    <input id="confirm_new_password" name="confirm_new_password" placeholder="Confirm your new password..." type="password" class="form-control" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-4 col-8">
                                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    </div>
    <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
@endsection
