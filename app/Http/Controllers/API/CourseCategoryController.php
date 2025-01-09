<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCategoryResource;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $courseCategories = CourseCategory::with('user')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Course Categories retrieved successfully',
            'data' => CourseCategoryResource::collection($courseCategories)
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
        ]);

        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validated
            ], 400);
        }

        $courseCategory['user_id'] = $request->user()->id;

        $courseCategory = CourseCategory::create($validated);

        $courseCategory->load('user');

        return response()->json([
            'status' => 'success',
            'message' => 'Course Category created successfully',
            'data' => new CourseCategoryResource($courseCategory)
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
        $courseCategory = CourseCategory::find($id);

        if (!$courseCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course Category not found'
            ], 404);
        }

        $courseCategory->load('user');

        return response()->json([
            'status' => 'success',
            'message' => 'Course Category retrieved successfully',
            'data' => new CourseCategoryResource($courseCategory)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $courseCategory = CourseCategory::find($id);

        if (!$courseCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course Category not found'
            ], 404);
        }

        if ($courseCategory->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this course category'
            ], 403);
        }

        $courseCategory->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course Category deleted successfully'
        ], 200);
    }
}
