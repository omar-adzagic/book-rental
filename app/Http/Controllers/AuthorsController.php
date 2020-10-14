<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Genre;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{

    /**
     * This method returns all authors sorted by name.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthors()
    {
        $authors = Author::orderBy('name')->get();
        $responseData = [
            'success' => true,
            'authors' => $authors,
        ];

        return response()->json($responseData, 200);
    }
}
