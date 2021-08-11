<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Recipe;
use App\Models\RecipeComment;

class RecipesCommentsController extends Controller
{
    public function store(Request $request, $recipe_id)
    {
        $recipe = Recipe::find($recipe_id);

        $validator = Validator::make($request->all(), [
            'text' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->messages()
            ], 200);
        }

        $comment = new RecipeComment();

        $comment->user_id = Auth::user()->id;
        $comment->recipe_id = $recipe->id;
        $comment->parent_id = $request->parent_id ?? null;
        $comment->text = $request->text;

        $comment->save();

        return response()->json([
            'status' => true,
            'success' => 'Comment created.',
            'comment' => $comment
        ], 200);
    }
}
