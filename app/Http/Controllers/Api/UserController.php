<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreatUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        try {
            $user = User::with(['photos', 'posts.photos'])->get();
            return ApiResponse::sendResponse(200, 'Users retrieved successfully', $user);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An error occurred while retrieving users', ['message' => $e->getMessage()]);
        }
    }

    public function create(CreateUserRequest $request)
    {
        try {
            $request['password'] = Hash::make($request->password);
            $user = User::create($request->all());
            return ApiResponse::sendResponse(201, 'User account created successfully', $user);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An unexpected error occurred', ['message' => $e->getMessage()]);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return ApiResponse::sendResponse(404, 'User not found', []);
            }

            $user->update($request->all());
            return ApiResponse::sendResponse(200, 'User account updated successfully', $user);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An unexpected error occurred', ['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return ApiResponse::sendResponse(404, 'User not found', []);
            }
            $user->delete();
            return ApiResponse::sendResponse(200, 'User deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An unexpected error occurred', ['message' => $e->getMessage()]);
        }
    }
}
