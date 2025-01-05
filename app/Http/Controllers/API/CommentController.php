<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Remove the specified resource from storage.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment not found'
            ], 404);
        }

        if ($request->user()->id != $comment->member->user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this comment'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
