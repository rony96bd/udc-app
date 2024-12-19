@extends('pages.layouts.main')
@section('main-container')
    <!-- Begin Page Content -->
    <div class="container" style="height: 100%">
        <form method="POST">
            @csrf
            <div class="form-group row">
                <label for="text1" class="col-4 col-form-label">Select Date</label>
                <div class="col-8">
                <input id="date1" name="date1" type="date" class="form-control">
            </div>
            </div>
            <div class="form-group row">
                <label for="text" class="col-4 col-form-label">Select Date</label>
                <div class="col-8">
                    <input id="text2" name="date2" type="date" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-4 col-8">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
        <div class="container font-weight-bold" style="margin-bottom: 100px; text-align: center">
            Earning from {{$date_to->format('d-m-Y')}} to {{$date_from->format('d-m-Y')}} = {{$earn}}/-
        </div>
    </div>
    
    <!-- End of Main Content -->
@endsection
