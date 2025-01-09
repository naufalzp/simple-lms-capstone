<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CourseContentResource;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;

class CourseContentController extends Controller
{

    public function store(Request $request, int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        if ($course->teacher_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not the teacher of this course'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'video_url' => 'nullable|string',
            'file_attachment' => 'nullable|string',
            'parent_id' => 'nullable|exists:course_contents,id'
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $validated['course_id'] = $course->id;

        $content = CourseContent::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Content created successfully',
            'data' => new CourseContentResource($content)
        ], 201);
    }
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
