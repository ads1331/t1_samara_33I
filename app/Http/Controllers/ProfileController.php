<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report; 
use App\Models\Task;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        $tasks = Task::where('parent', 0)->get(); 

        
        $reports = Report::where('user_id', $user->id)->get(); 

        return view('profile.index', compact('user', 'reports', 'tasks'));
    }
}

