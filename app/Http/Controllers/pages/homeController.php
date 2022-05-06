<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brid;

class homeController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }

    function addData(Request $req)
    {
        $input = $req->all();
        Brid::create($input);
        return view('pages.index');
    }
}
