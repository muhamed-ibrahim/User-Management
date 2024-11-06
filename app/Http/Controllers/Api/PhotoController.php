<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Photo;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\CreatePhotoRequest;

class PhotoController extends Controller
{
    public function createPhotoUser(CreatePhotoRequest $request)
    {
        try {
            $user = auth()->user();

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('storage/user_photo/'), $filename);

            $data['image'] = $filename;
            $data['user_id'] = $user->id;

            $UserPhoto = Photo::create($data);
            return ApiResponse::sendResponse(201, 'Photo created for user successfully', $UserPhoto);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An unexpected error occurred', ['message' => $e->getMessage()]);
        }
    }

    public function createPhotoPost(CreatePhotoRequest $request, $id)
    {
        try {
            $user = auth()->user();

            $post = Post::find($id);
            if (!$post) {
                return ApiResponse::sendResponse(404, 'Post not found');
            }
            if ($post->user_id !== Auth::user()->id) {
                return ApiResponse::sendResponse(403, 'You do not have permission to add a photo to this post');
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('storage/post_photo/'), $filename);

            $data['image'] = $filename;
            $data['post_id'] = $post->id;

            $PostPhoto = Photo::create($data);

            return ApiResponse::sendResponse(201, 'Post photo created successfully', $PostPhoto);
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(500, 'An unexpected error occurred', ['message' => $e->getMessage()]);
        }
    }
}
