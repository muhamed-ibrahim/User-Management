<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreatePostRequest;

class PostController extends Controller
{

    public function create(CreatePostRequest $request)
    {

        try {
            $request['user_id'] = Auth::id();
            $post = Post::create($request->all());
            return ApiResponse::sendResponse(201, 'Post created successfully', $post);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An unexpected error occurred', ['message' => $e->getMessage()]);
        }
    }
}
