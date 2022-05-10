<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Brid;
use App\Models\Payment;

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
}
