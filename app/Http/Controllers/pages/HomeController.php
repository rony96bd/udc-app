<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Listeners\LogListener;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Brid;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth()->user();

        if ($user->is_admin == 1) {
            $brIds = Brid::join('users', 'users.id', '=', 'brids.user_id')
                ->get(['brids.*', 'users.name', 'users.email']);
        } else {
            $brIds = Brid::where('user_id', $user->id)->get();
        }

        return view('pages.index', compact(['brIds', 'user']));
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

    function deleteData(Request $req)
    {
        $brids = Brid::findOrFail($req->id);
        $brids->delete();
        return Redirect::route('dashboard');
    }

    public function changePassword()
    {
        return view('pages.change-password');
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
}
