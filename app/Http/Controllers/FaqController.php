<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sligoman\AiblogApiWeb\Models\AiblogPost;
use Sligoman\AiblogApiWeb\Models\AiblogPostType;

class FaqController extends Controller
{
    /**
     * Display the FAQ page.
     * If you want to manage FAQs via config or database later, load them here and pass to the view.
     */
    public function index(Request $request)
    {

        $faqs = AiblogPost::whereHas('type', function ($query) {
            $query->where('name', 'faq');
        })->orderBy('created_at', 'desc')->get()->map(function ($post) {
            return [
                'question' => $post->title,
                'answer' => $post->content,
            ];
        });        

        return view('pages.faq', ['items' => $faqs]);
    }
}
