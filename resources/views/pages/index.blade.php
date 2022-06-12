@extends('pages.layouts.main')
@section('main-container')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <div class="d-sm-flex align-items-center justify-content-between mb-6"></div> --}}

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <!-- Content Row -->
        <div class="row">
            <!-- ID Send Card -->
            @if ($user->is_admin == '0')
                <div class="col-xl-12 col-md-6 mb-4">

                    <div class="card border-left-primary shadow h-100 py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs text-center font-weight-bold text-primary text-uppercase mb-1">
                                        Send ID's
                                    </div>
                                    <div class="text-center h5 mb-0 font-weight-bold text-gray-800">
                                        <form action="{{ Route('addBr') }}" method="POST"
                                            class="d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                            @csrf
                                            <div class="input-group">
                                                <input name="brid" type="text" autofocus="on"
                                                    class="form-control bg-light border-0 small" placeholder="Write ID"
                                                    aria-label="Send ID" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-paper-plane fa-sm"></i>
                                                    </button>
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

        <!-- Page Heading -->

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 style="float: left" class="m-0 font-weight-bold text-primary">Results</h4>
                @if ($user->is_admin == '1')
                    <div style="float: right">
                        <span><button type="button" class="btn btn-success" data-toggle="modal" id="exampleApprove">
                                Approve
                            </button></span>
                        <span>
                            <button type="button" class="btn btn-danger" data-toggle="modal" id="exampleReject">
                                Reject
                            </button>
                        </span>
                        <span>
                            <button type="button" class="btn btn-danger" data-toggle="modal" id="exampleDelete">
                                Delete
                            </button>
                        </span>
                    </div>
                @endif
            </div>
            <!-- Admin Table -->
            @if ($user->is_admin == '1')
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>BR Applicatin ID</th>
                                <th>Status</th>
                                <th>ID Type</th>
                                <th style="width: 56px;">Rate</th>
                                <th>Message</th>
                                @if ($user->is_admin == '1')
                                    <th>Requested</th>
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brIds as $brId)
                                <tr>
                                    <td style="vertical-align: middle;">
                                        {{ date('d-m-Y H:i:s', strtotime($brId->created_at)) }}</td>
                                    <td style="vertical-align: middle;">
                                        <span id="{{ $brId->brid }}">{{ $brId->brid }}</span>
                                        <button class="badge badge-counter btn btn-primary"
                                            data-desc-ref="{{ $brId->brid }}" type="button" value="Copy" id="btn"
                                            onclick="status(this)"><i class="fas fa-copy fa-sm"></i></button>
                                    </td>
                                    @switch($brId->status)
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

                                    <form action="{{ Route('updBr') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $brId->id }}" />
                                        <td style="vertical-align: middle; color: {{ $txtcol }};" align="center">
                                            @if ($user->is_admin == '0')
                                                {{ $brId->status }}
                                            @endif

                                            @if ($user->is_admin == '1')
                                                <select style="color: {{ $txtcol }};" name="status"
                                                    onchange='if(this.value != 0) { this.form.submit(); }'
                                                    class="form-select center">
                                                    <option>{{ $brId->status }}</option>
                                                    <option style="color: rgb(9, 214, 9);">Approved</option>
                                                    <option style="color: red;">Reject</option>
                                                    <option style="color: blue;">Pending</option>
                                                </select>
                                            @endif
                                        </td>

                                        <td style="vertical-align: middle;" align="center">
                                            @if ($user->is_admin == '0')
                                                {{ $brId->id_type }}
                                            @endif
                                            @if ($user->is_admin == '1')
                                                <select name="id_type" class="form-select center">
                                                    <option>{{ $brId->id_type }}</option>
                                                    <option>Regular</option>
                                                    <option>DoB Correction</option>
                                                </select>
                                            @endif
                                        </td>

                                        <td style="vertical-align: middle; width: 56px;" align="center">
                                            @if ($user->is_admin == '0')
                                                {{ $brId->rate }}
                                            @endif
                                            @if ($user->is_admin == '1')
                                                <input class="form-control text-center" name="rate" type="text"
                                                    placeholder="{{ $brId->rate }}" value="{{ $brId->rate }}" />
                                            @endif
                                        </td>

                                        <td style="vertical-align: middle;" align="center">
                                            @if ($user->is_admin == '0')
                                                {{ $brId->message }}
                                            @endif
                                            @if ($user->is_admin == '1')
                                                <textarea name="message" class="form-control" rows="1">{{ $brId->message }}</textarea>
                                            @endif
                                        </td>
                                        @if ($user->is_admin == '1')
                                            <td style="vertical-align: middle;">
                                                {{-- <span>{{ $brId->name }}</span><br> --}}
                                                <span>{{ $brId->email }}</span>
                                            </td>
                                            <td class="d-flex justify-content-between" style="vertical-align: middle;">
                                                <span> <button type="submit" class="btn btn-primary btn-sm"><i
                                                            class="fas fa-save"></i></button> </span>
                                    </form>
                                    <form action="{{ route('deleteBr', $brId->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <span> <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash"></i>
                                            </button></span>
                                    </form>
                                    </td>
                            @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            <!-- User Table -->
            @if ($user->is_admin == '0')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="example-user">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>BR Applicatin ID</th>
                                    <th>Status</th>
                                    <th>ID Type</th>
                                    <th style="width: 56px;">Rate</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brIds as $brId)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            {{ date('d-m-Y H:i:s', strtotime($brId->created_at)) }}</td>
                                        <td style="vertical-align: middle;">
                                            <span id="{{ $brId->brid }}">{{ $brId->brid }}</span>
                                            <button class="badge badge-counter btn btn-primary"
                                                data-desc-ref="{{ $brId->brid }}" type="button" value="Copy" id="btn"
                                                onclick="status(this)"><i class="fas fa-copy fa-sm"></i></button>
                                        </td>
                                        @switch($brId->status)
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
                                        <td style="vertical-align: middle; color: {{ $txtcol }};" align="center">
                                                {{ $brId->status }}
                                        </td>
                                        <td style="vertical-align: middle;" align="center">
                                                {{ $brId->id_type }}
                                        </td>
                                        <td style="vertical-align: middle; width: 56px;" align="center">
                                                {{ $brId->rate }}
                                        </td>
                                        <td style="vertical-align: middle;" align="center">
                                                {{ $brId->message }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- /.container-fluid -->
    </div>

    <!-- End of Main Content -->
@endsection
