<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{

    /**
     * This method returns info about user if he is authenticated.
     * It is called automatically on App.vue layout page for Vue.js.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo()
    {
        $user = Auth::user();
        if (! empty($user)) {
            $user->loggedIn = Auth::check();
            $isLogged = true;
        } else {
            $isLogged = false;
        }
        $responseData = [
            'success' => true,
            'user' => $user,
            'isLogged' => $isLogged,
        ];

        return response()->json($responseData, 200);
    }
}
