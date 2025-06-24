<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view("vendor.chat-pannel.chat", [
            // You can pass any data needed for the chat view here
        ]);
    }
}
