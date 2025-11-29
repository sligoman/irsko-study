<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sligoman\AiblogApiWeb\Models\AiblogPost;

class BlogController extends Controller
{
    /**
     * Show paginated list of blog posts from the aiblog package.
     */
    public function index(Request $request)
    {

        $posts = AiblogPost::with('type')->whereHas('type', function ($query) {
            $query->where('name', 'blog');
        });

        return view('blog.index', ['posts' => $posts]);
    }

    /**
     * Show a single blog post by slug.
     */
    public function show(Request $request, $slug)
    {

        $post = AiblogPost::where('slug', $slug)->firstOrFail();

        return view('blog.show', ['post' => $post]);
    }
}
