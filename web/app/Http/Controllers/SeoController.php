<?php

namespace App\Http\Controllers;

use App\Support\Launch;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Replaces the old static public/robots.txt — needs to be a real route so it can follow
     * App\Support\Launch::indexable() instead of always allowing everything.
     */
    public function robots(): Response
    {
        $body = Launch::indexable()
            ? "User-agent: *\nDisallow: /admin\n"
            : "User-agent: *\nDisallow: /\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
