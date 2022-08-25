<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $users = User::all();
        return view('pages/dashboard', [
            "title" => "dashboard",
            // "users" => $users
        ]);
    }
}
