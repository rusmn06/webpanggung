<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Make sure to import the Controller class

class AdminController extends Controller // Explicitly extend the Controller class
{
    public function dashboard()
    {
        return view('pages.dashboard');
    }

}

