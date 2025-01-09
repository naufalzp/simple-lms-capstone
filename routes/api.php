<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\CourseCategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CourseAnnouncementController;
use App\Http\Controllers\API\CourseContentController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\CourseFeedbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:register');

    Route::middleware('auth:api')->group(function () {
        // Courses
        Route::get('my-courses', [CourseController::class, 'myCourses']);
        Route::get('courses', [CourseController::class, 'index']);
        Route::post('courses', [CourseController::class, 'store'])->middleware('throttle:course_create');
        Route::get('courses/{id}', [CourseController::class, 'show']);
        Route::put('courses/{id}', [CourseController::class, 'update']);
        Route::delete('courses/{id}', [CourseController::class, 'destroy']);
        Route::get('courses/{id}/contents', [CourseController::class, 'listContent']);
        Route::get('courses/{id}/contents/{contentId}', [CourseController::class, 'detailContent']);
        Route::post('courses/{id}/enroll', [CourseController::class, 'enroll']);
        
        // Contents
        Route::post('courses/{id}/contents', [CourseContentController::class, 'store'])->middleware('throttle:content_create');
        Route::get('contents/{id}/comments', [CourseContentController::class, 'listComments']);
        Route::post('contents/{id}/comments', [CourseContentController::class, 'storeComment'])->middleware('throttle:comment');
        
        // Comments
        Route::delete('comments/{id}', [CommentController::class, 'destroy']);
        
        // Announcements
        Route::get('courses/{id}/announcements', [CourseAnnouncementController::class, 'showByCourse']);
        Route::post('announcements', [CourseAnnouncementController::class, 'store']);
        Route::put('announcements/{id}', [CourseAnnouncementController::class, 'update']);
        Route::delete('announcements/{id}', [CourseAnnouncementController::class, 'destroy']);

        // Categories
        Route::get('categories', [CourseCategoryController::class, 'index']);
        Route::post('categories', [CourseCategoryController::class, 'store']);
        Route::get('categories/{id}', [CourseCategoryController::class, 'show']);
        Route::delete('categories/{id}', [CourseCategoryController::class, 'destroy']);

        // Feedback
        Route::get('courses/{courseId}/feedbacks', [CourseFeedbackController::class, 'index']);
        Route::post('courses/{courseId}/feedbacks', [CourseFeedbackController::class, 'store']);
        Route::get('courses/{courseId}/feedbacks/{id}', [CourseFeedbackController::class, 'show']);
        Route::put('courses/{courseId}/feedbacks/{id}', [CourseFeedbackController::class, 'update']);
        Route::delete('courses/{courseId}feedbacks/{id}', [CourseFeedbackController::class, 'destroy']);

        // Auth
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});