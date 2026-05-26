<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Konser;
use App\Models\Ticket;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $artists = Artist::all();
        $konser = Konser::all();
        $tickets = Ticket::all();
        $users = User::all();

        return view('admin', compact('artists', 'konser', 'tickets', 'users'));
    }
}
