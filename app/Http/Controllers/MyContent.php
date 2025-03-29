<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

class MyContent extends Controller
{
    public function index(Content $content)
    {
        // DB::listen(fn($query) => dump($query->sql));

        $contents = $content
            ->getValidContents()
            ->get()
            ->groupBy('type');
        // dd((string) $contents);
        return inertia('MyContent', compact('contents'));
    }

    public function single(Content $content)
    {
        return inertia('Player', compact('content'));
    }
}
