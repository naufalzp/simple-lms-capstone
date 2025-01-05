<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CourseContentResource;
use App\Models\Comment;
use App\Models\CourseContent;
use Illuminate\Http\Request;

class CourseContentController extends Controller
{
    /**
     * Get comments for a specific content
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listComments(int $id)
    {
        $content = CourseContent::find($id);

        if (!$content) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content not found'
            ], 404);
        }

        $content->load('comments');

        return response()->json([
            'status' => 'success',
            'message' => 'Comments retrieved successfully',
            'data' => CourseContentResource::collection($content)
        ], 200);
    }

    /**
     * Store a new comment for a specific content
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeComment(Request $request, int $id)
    {
        $content = CourseContent::find($id);

        if (!$content) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content not found'
            ], 404);
        }

        if ($content->course->isMember($request->user() === false)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not a member of this course'
            ], 403);
        }

        $validated = $request->validate([
            'comment' => 'required|string'
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $comment = Comment::create([
            'content_id' => $content->id,
            'member_id' => $request->user()->id,
            'comment' => $request->comment
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'data' => new CommentResource($comment)
        ], 201);
    }
}
