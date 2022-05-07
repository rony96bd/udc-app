<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brid;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth()->user();

        $total_payable = Brid::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->sum('rate');
        $paid = Payment::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->sum('taka');

        $balance = $total_payable - $paid;

        if ($user->is_admin == 1) {
            $brIds = Brid::join('users', 'users.id', '=', 'brids.user_id')
            ->get(['brids.*', 'users.name']);
        } else {
            $brIds = Brid::where('user_id', $user->id)->get();
        }

        return view('pages.index', compact(['brIds', 'user', 'balance']));
    }

    function addData(Request $req)
    {
        $user = Auth()->user();
        $br = Brid::create([
            'brid' => $req->brid,
            'status' => "Pending",
            'id_type' => "General",
            'rate' => $user->rate,
            'user_id' => $user->id,
        ]);
        return Redirect::route('dashboard');
    }

    function updateData(Request $req)
    {
        $brids = Brid::findOrFail($req->id);
        $brids->status = $req->status;
        $brids->id_type = $req->id_type;
        $brids->rate = $req->rate;
        $brids->message = $req->message;
        $brids->save();
        return Redirect::route('dashboard');
        // return redirect()->back();
    }
}
