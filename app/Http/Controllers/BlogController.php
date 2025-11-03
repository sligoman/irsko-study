<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BlogController extends Controller
{
    /**
     * Show paginated list of blog posts from the aiblog package.
     */
    public function index(Request $request)
    {
        // Use the package model directly
        $modelClass = '\\Sligoman\\AiblogApiWeb\\Models\\AiblogPost';

        if (! class_exists($modelClass)) {
            abort(500, 'Blog model not found: ' . $modelClass);
        }

        $query = $modelClass::query();

        // If the package has scheduled_at or published flags, you can filter here.
        $posts = $query->orderBy('scheduled_at', 'desc')->orderBy('created_at', 'desc')->paginate(10);

        return view('blog.index', ['posts' => $posts]);
    }

    /**
     * Show a single blog post by slug.
     */
    public function show(Request $request, $slug)
    {
        $modelClass = '\\Sligoman\\AiblogApiWeb\\Models\\AiblogPost';

        if (! class_exists($modelClass)) {
            abort(500, 'Blog model not found: ' . $modelClass);
        }

        $post = $modelClass::where('slug', $slug)->firstOrFail();

        return view('blog.show', ['post' => $post]);
    }
}
