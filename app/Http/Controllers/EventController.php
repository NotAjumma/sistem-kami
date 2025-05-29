<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function showBySlug($slug)
    {
        $event = Event::with('category')->where('slug', $slug)->firstOrFail();

        return view('event.index', compact('event'));

    }


}