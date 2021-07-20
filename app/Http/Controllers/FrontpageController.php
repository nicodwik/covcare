<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class FrontpageController extends Controller
{
    public function register() 
    {
        $data = Event::where('isActive', 1)->with('schedules')->first();
        $data->schedules->map(function($item) {
           $item->date = \Carbon\Carbon::parse($item->date)->isoFormat('dddd, Do MMMM Y'); 
        });
        if($data) {
            return view('pages.register', compact('data'));
        } else {
            return view('pages.closed-register');
        }
    }
}
