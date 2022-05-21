<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Brid;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PayController extends Controller
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

        return view('pages.payinfo', compact(['brIds', 'user', 'balance']));
    }

    public function addPayment(Request $reqp)
    {
        $user = Auth()->user();
        $pay = Payment::create([
            'user_id' => $user->id,
            'taka' => $reqp->taka,
            'transaction_id' => $reqp->trid,
            'status' => "Pending",
        ]);
        return Redirect::route('payinfo');
    }

    public function paymentShow()
    {
        return view('pages.add-payment');
    }
    function updatePayment (Request $reqp)
    {
        $pay = Payment::findOrFail($reqp->id);
        $pay->status = $reqp->status;
        $pay->save();

        return Redirect::route('payinfo');
    }
}
