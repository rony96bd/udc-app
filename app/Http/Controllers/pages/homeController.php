<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth()->user();
        $brIds = Brid::where('user_id', $user->id)->get();
        return view('pages.index', compact(['brIds']));
    }

    function addData(Request $req)
    {
        $user = Auth()->user();
        $br = Brid::create([
            'brid' => $req->brid,
            'user_id' => $user->id,
        ]);
        return Redirect::route('dashboard');
    }

    function updateData(Request $req)
    {
        dd($req);
        $brids = Brid::find($req->id);
        $brids->status = $req->status;
        $brids->id_type = $req->id_type;
        $brids->rate = $req->rate;
        $brids->message = $req->message;
        $brids->save();
        return Redirect::route('dashboard');
        // return redirect()->back();
    }
}
