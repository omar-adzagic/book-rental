<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    /**
     * This method returns all genres sorted by name.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenres()
    {
        $genres = Genre::orderBy('name')->get();
        $responseData = [
            'success' => true,
            'genres' => $genres,
        ];

        return response()->json($responseData, 200);
    }
}
