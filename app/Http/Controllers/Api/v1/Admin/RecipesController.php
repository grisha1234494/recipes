<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\Recipe;
use App\Models\Ingridient;
use App\Models\Image;
use App\Models\RecipeImage;

class RecipesController extends Controller
{
    public function index()
    {
        $recipes = Recipe::paginate(10);

        return response()->json($recipes);
    }
    public function create()
    {
        return response()->json([
            'ingridients' => Ingridient::get(['id', 'title'])
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'recipe_ingridients.*' => ['exists:ingridients,id'],
            'recipe_images.*' => ['exists:images,id'],
        ], [
            'recipe_ingridients.*.*' => 'The selected ingridients is invalid.',
            'recipe_images.*.*' => 'The selected images is invalid.',
        ]);

        if($validator->fails()) {
            if($validator->errors()->first('recipe_ingridients.*')) {
                $validator->errors()->add('recipe_ingridients', $validator->errors()->first('recipe_ingridients.*'));
            }
            if($validator->errors()->first('recipe_images.*')) {
                $validator->errors()->add('recipe_images', $validator->errors()->first('recipe_images.*'));
            }

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $recipe = new Recipe();

        $recipe->user_id = Auth::user()->id;
        $recipe->title = $request->title;
        $recipe->description = $request->description;

        $recipe->save();

        $recipe->ingridients()->sync($request->recipe_ingridients ?? []);

        if($request->recipe_images) {
            $images = Image::whereIn('id', $request->recipe_images)->get();
        }

        if($images ?? null) {
            foreach($images as $image) {
                $recipe_image = new RecipeImage();

                $recipe_image->recipe_id = $recipe->id;
                $recipe_image->image_id = $image->id;
                $recipe_image->image_path = $image->path;

                $recipe_image->save();
            }
        }

        return response()->json([
            'success' => 'Recipe created.',
            'recipe' => $recipe
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $recipe = Recipe::find($id);
        $recipe_images = $recipe->images->pluck('id');

        return response()->json([
            'recipe' => $recipe,
            'ingridients' => Ingridient::get(['id', 'title']),
            'recipe_ingridients' => $recipe->ingridients->pluck('id'),
            'recipe_images' => $recipe->images->pluck('id'),
            'images' => ($recipe_images) ? Image::whereIn('id', $recipe_images)->get() : []
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'recipe_ingridients.*' => ['exists:ingridients,id'],
            'recipe_images.*' => ['exists:images,id'],
        ], [
            'recipe_ingridients.*.*' => 'The selected ingridients is invalid.',
            'recipe_images.*.*' => 'The selected images is invalid.',
        ]);

        if($validator->fails()) {
            if($validator->errors()->first('recipe_ingridients.*')) {
                $validator->errors()->add('recipe_ingridients', $validator->errors()->first('recipe_ingridients.*'));
            }
            if($validator->errors()->first('recipe_images.*')) {
                $validator->errors()->add('recipe_images', $validator->errors()->first('recipe_images.*'));
            }

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $recipe = Recipe::find($id);
        
        $recipe->title = $request->title;
        $recipe->description = $request->description;

        $recipe->save();

        $recipe->ingridients()->sync($request->recipe_ingridients ?? []);
        $recipe->images()->sync([]);

        if($request->recipe_images) {
            $images = Image::whereIn('id', $request->recipe_images)->get();
        }

        if($images ?? null) {
            foreach($images as $image) {
                $recipe_image = new RecipeImage();

                $recipe_image->recipe_id = $recipe->id;
                $recipe_image->image_id = $image->id;
                $recipe_image->image_path = $image->path;

                $recipe_image->save();
            }
        }

        return response()->json([
            'success' => 'Recipe updated.',
            'recipe' => $recipe
        ]);
    }
    public function destroy($id)
    {
        $recipe = Recipe::find($id);

        if(!$recipe) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $recipe->delete();

        return response()->json([
            'success' => 'Recipe deleted.'
        ]);
    }
}
