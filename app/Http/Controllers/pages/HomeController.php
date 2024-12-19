<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Listeners\LogListener;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Brid;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth()->user();

        if ($user->is_admin == 1) {
            $brIds = Brid::join('users', 'users.id', '=', 'brids.user_id')
                ->where('status', 'Pending')
                ->get(['brids.*', 'users.name', 'users.email']);
        } else {
            $brIds = Brid::where('user_id', $user->id )
            ->where('status', 'Pending')
            ->get();
        }

        return view('pages.index', compact(['brIds', 'user']));
    }
    public function alldata()
    {
        $user = Auth()->user();

        if ($user->is_admin == 1) {
            $brIds = Brid::join('users', 'users.id', '=', 'brids.user_id')
                ->get(['brids.*', 'users.name', 'users.email']);
        } else {
            $brIds = Brid::where('user_id', $user->id)->get();
        }

        return view('pages.alldata', compact(['brIds', 'user']));
    }

    function addData(Request $req)
    {
        $user = Auth()->user();
        $brid = Brid::where('brid', $req->brid)->first();

        if ($brid) {
            if ($brid->user_id == $user->id) {
                return Redirect::back()->with('message', 'Duplicate BRID Found.');
            } else {
                return Redirect::back()->with('message', 'This brid is already in use');
            }
        } else {
            Brid::firstOrCreate([
                'brid' => $req->brid,
                'status' => "Pending",
                'id_type' => "General",
                'rate' => $user->rate,
                'user_id' => $user->id,
            ]);
            return Redirect::back()->with('message', 'Brid added successfully');
        }
    }
    function addDataAdmin(Request $req)
    {
        $user_id = $req->user_id;
        $brid = Brid::where('brid', $req->brid)->first();
        $user_rate = User::where('users.id', $user_id)->value('users.rate');

        if ($brid) {
            if ($brid->user_id == $user_id) {
                return Redirect::back()->with('message', 'Duplicate BRID Found.');
            } else {
                return Redirect::back()->with('message', 'This brid is already in use');
            }
        } else {
            Brid::firstOrCreate([
                'brid' => $req->brid,
                'user_id' => $req->user_id,
                'status' => "Pending",
                'id_type' => "General",
                'rate' => $user_rate,
            ]);
            return Redirect::back()->with('message', 'Brid added successfully');
        }
    }

    function updateData(Request $req)
    {
        $brids = Brid::findOrFail($req->id);
        $brids_old = $brids;
        $brids->status = $req->status;
        $brids->id_type = $req->id_type;
        $brids->rate = $req->rate;
        $brids->message = $req->message;
        $brids->save();

        if ($brids) {
            $log = new Log();
            $log->user_id = Auth()->user()->id;
            $log->action = "Update";
            $log->status = $req->status;
            $log->old_data = json_encode($brids_old);
            $log->new_data = json_encode($brids);
            $log->ip_address = $req->ip();
            $log->user_agent = $req->header('User-Agent');
            event(new LogListener($log));
        }

        return Redirect::route('dashboard');
        // return redirect()->back();
    }

    function updateDataUdc(Request $req)
    {
        $brids = Brid::findOrFail($req->id);
        $brids->message = $req->message;
        $brids->rate = "20";
        $brids->save();

        return Redirect::route('dashboard');
        // return redirect()->back();
    }

    function deleteData(Request $req)
    {
        $brids = Brid::findOrFail($req->id);
        $brids_old = $brids;
        if ($brids) {
            $log = new Log();
            $log->user_id = Auth()->user()->id;
            $log->action = "Delete";
            $log->status = "Deleted";
            $log->old_data = json_encode($brids_old);
            $log->new_data = json_encode($brids);
            $log->ip_address = $req->ip();
            $log->user_agent = $req->header('User-Agent');
            event(new LogListener($log));
        }
        $brids->delete();
        return Redirect::route('dashboard');
    }

    public function changePassword()
    {
        $user = Auth()->user();

        return view('pages.change-password')->with('user', $user);
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
        ]);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->with("error", "Old password Does not match");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password Changed Successfully!");
    }

    /**
     * Approve all the ids
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function approveAll(Request $request): JsonResponse
    {
        //$brid = Brid::where('user_id', $user->id)->get();
        $brids = $request->ids;
        $ip_address = $request->ip();
        $user_agent = $request->header('User-Agent');
        //update array of ids
        foreach ($brids as $brid) {
            $brid = Brid::where('brid', $brid)->first();
            $brid_old = $brid;
            if ($brid) {
                $log = new Log();
                $log->user_id = Auth()->user()->id;
                $log->action = "Admin Approve";
                $log->status = "Approved";
                $log->old_data = json_encode($brid_old);
                $log->new_data = json_encode($brid);
                $log->ip_address = $ip_address;
                $log->user_agent = $user_agent;
                event(new LogListener($log));
            }
            $brid->status = "Approved";
            $brid->save();
        }
        return response()->json(['status' => 'success', 'message' => 'All ids approved successfully']);
    }

    /**
     * Reject all the ids
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function rejectAll(Request $request): JsonResponse
    {
        //$brid = Brid::where('user_id', $user->id)->get();
        $brids = $request->ids;
        $ip_address = $request->ip();
        $user_agent = $request->header('User-Agent');
        //update array of ids
        foreach ($brids as $brid) {
            $brid = Brid::where('brid', $brid)->first();
            $brid_old = $brid;
            if ($brid) {
                $log = new Log();
                $log->user_id = Auth()->user()->id;
                $log->action = "Admin Reject";
                $log->status = "Reject";
                $log->old_data = json_encode($brid_old);
                $log->new_data = json_encode($brid);
                $log->ip_address = $ip_address;
                $log->user_agent = $user_agent;
                event(new LogListener($log));
            }
            $brid->status = "Reject";
            $brid->save();
        }
        return response()->json(['status' => 'success', 'message' => 'All ids rejected successfully']);
    }

    /**
     * Delete all the ids
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request): JsonResponse
    {
        //$brid = Brid::where('user_id', $user->id)->get();
        $brids = $request->ids;
        $ip_address = $request->ip();
        $user_agent = $request->header('User-Agent');
        //update array of ids
        foreach ($brids as $brid) {
            $brid = Brid::where('brid', $brid)->first();
            $brid_old = $brid;
            if ($brid) {
                $log = new Log();
                $log->user_id = Auth()->user()->id;
                $log->action = "Admin Delete";
                $log->status = "Delete";
                $log->old_data = json_encode($brid_old);
                $log->new_data = json_encode($brid);
                $log->ip_address = $ip_address;
                $log->user_agent = $user_agent;
                event(new LogListener($log));
            }
            $brid->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'All ids deleted successfully']);
    }

    public function earn (Request $request){
        $date_to = new Carbon($request->date1);
        $date_from = new Carbon($request->date2);
        $user = Auth()->user();
        $earn = Payment::where('status', 'Approved')
        ->whereBetween('created_at', [$date_to->format('Y-m-d')." 00:00:00", $date_from->format('Y-m-d')." 23:59:59"])
        ->sum('taka');

        return view('pages.earn', compact(['user', 'earn', 'date_to', 'date_from']));
    }

    public function transaction_view (){
        $user = Auth()->user();
        $transactions = Transaction::get();
        return view('pages.transaction', compact(['user', 'transactions']));
    }

    public function transaction_add (Request $request){

        $user = Auth()->user();
        $transaction = Transaction::where('transaction_id', $request->transaction)->first();

        if ($transaction) {
                return Redirect::back()->with('danger', 'Duplicate Transaction ID Found.');
            } else {
            Transaction::firstOrCreate([
                'transaction_id' => $request->transaction,
                'taka' => $request->taka,
                'status' => "Pending",
                'mobile' => "",
            ]);
            return Redirect::back()->with('message', 'Transaction ID Added successfully');
        }
    }
}
