<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordValidation;
use App\Http\Requests\ProfileUpdateValidation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * This method is used for updating information of authenticated user.
     * ProfileUpdateValidation custom request validates POST request data.
     *
     * @param ProfileUpdateValidation $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateValidation $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);
        $responseData = [
            'success' => true,
            'message' => 'Profile info is saved successfully.',
        ];

        return response()->json($responseData, 200);
    }

    /**
     * Method for changing password of authenticated user.
     * ChangePasswordValidation validates POST request data.
     *
     * @param ChangePasswordValidation $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordValidation $request, User $user)
    {
        $validated = $request->validated();
        $user->update(['password' => Hash::make($validated['password'])]);
        $responseData = [
            'success' => true,
            'message' => 'Password is changed successfully.',
        ];

        return response()->json($responseData, 200);
    }
}
