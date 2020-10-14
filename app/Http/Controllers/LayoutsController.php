<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LayoutsController extends Controller
{
    /**
     * Layout is shown with div element with id="app" where Vue.js is loaded.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('layouts.website');
    }

    /**
     * When user wants to get non existing book 404 page is shown, otherwise layout is shown with div element with id="app" where Vue.js is loaded.
     * In case if user wants to access route to view single book if it is marked as inactive and user is not an owner of the book, 403 page is shown.
     *
     * @param Book $book
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showBook(Book $book)
    {
        if (empty($book)) {
            abort(404);
        }
        if ($book->active == 0 && !Auth::user()->books->contains($book->id)) {
            abort(403);
        }

        return view('layouts.website');
    }
}
