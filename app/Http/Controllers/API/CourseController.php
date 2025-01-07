<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseContentResource;
use App\Http\Resources\CourseMemberResource;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseMember;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $courses = Course::with('teacher', 'category')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Courses retrieved successfully',
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:course_categories,id',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public/courses');
        }

        $validated['teacher_id'] = $request->user()->id;

        $course = Course::create($validated);

        $course->load('teacher', 'category');

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => new CourseResource($course)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $course->load('teacher', 'category');

        return response()->json([
            'status' => 'success',
            'message' => 'Course retrieved successfully',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:course_categories,id',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $course = Course::find($id);
        
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        if ($request->user()->id != $course->teacher_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this course'
            ], 403);
        }
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public/courses');
        }

        $course->update($validated);

        $course->load('teacher', 'category');

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request ,int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        if ($request->user()->id != $course->teacher_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this course'
            ], 403);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ], 200);
    }

    /**
     * Get all courses created by the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myCourses(Request $request)
    {
        $courses = Course::where('teacher_id', $request->user()->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Courses retrieved successfully',
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * Get all contents of a course
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listContent(int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Course contents retrieved successfully',
            'data' => new CourseContentResource($course->contents)
        ], 200);
    }

    /**
     * Get a specific content of a course
     *
     * @param int $id
     * @param int $contentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailContent(int $id, int $contentId)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $content = $course->contents()->find($contentId);

        if (!$content) {
            return response()->json([
                'status' => 'error',
                'message' => 'Content not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Content retrieved successfully',
            'data' => new CourseContentResource($content)
        ], 200);
    }

    /**
     * Enroll in a course
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function enroll(Request $request, int $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $courseMember = CourseMember::create([
            'course_id' => $course->id,
            'user_id' => $request->user()->id,
            'roles' => 'std'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully enrolled in this course',
            'data' => new CourseMemberResource($courseMember)
        ], 200);
    }
}
