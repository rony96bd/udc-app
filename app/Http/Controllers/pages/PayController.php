<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Brid;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

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

        $total_payable = Brid::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->sum('rate');

        $paid = Payment::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->sum('taka');

        $balance = $total_payable - $paid;

        $payments_status = Payment::where('user_id', $user->id)
                    ->where('status', "Pending")
                    ->first();

        $transaction_dup_check = Payment::where('transaction_id', $reqp->trid)->first();

        $transaction_found = Transaction::where('transaction_id', $reqp->trid)->exists();

        $transaction_taka_found = Transaction::where('transaction_id', $reqp->trid)->where('taka', $reqp->taka)->exists();

        if ($payments_status) {
            return Redirect::back()->with('error', 'পূর্বের ব্যালেন্স পরিশোধ করুন');
        } elseif ($reqp->taka < $balance) {
            return Redirect::back()->with('error', 'ব্যালেন্স এর চেয়ে বেশি টাকা এড করতে হবে');
        } elseif ($transaction_dup_check) {
            return Redirect::back()->with('error', 'Duplicate Transaction ID Found');
        } elseif (!$transaction_found) {
            return Redirect::back()->with('error', 'ভুল Transaction ID (পেমেন্টের পর সার্ভারে Transaction ID আপডেটের জন্য কিছুক্ষণ অপেক্ষা করুন। তারপর Approver এ পেমেন্ট এড করুন।)');
        } elseif (!$transaction_taka_found) {
            return Redirect::back()->with('error', 'Transaction ID এর টাকার পরিমাণ মিল নেই');
        } else {
            
            DB::table('transactions')
            ->where('transaction_id', $reqp->trid)
            ->update([
                'status' => "Used",
                'mobile' => $reqp->mobile,
            ]);

            $pay = Payment::create([
                'user_id' => $user->id,
                'taka' => $reqp->taka,
                'transaction_id' => $reqp->trid,
                'mobile' => $reqp->mobile,
                'status' => "Approved",
            ]);
        }
        return Redirect::route('payinfo')->with('success', 'ব্যালেন্স এড হয়েছে।');
    }

    public function paymentShow()
    {
        $user = Auth()->user();
        return view('pages.add-payment', compact(['user']));
    }
    function updatePayment (Request $reqp)
    {
        $pay = Payment::findOrFail($reqp->id);
        $pay->status = $reqp->status;
        $pay->save();

        return Redirect::route('payinfo');
    }
}
