<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\Image;

class ImagesController extends Controller
{
    public function index()
    {
        $images = Image::orderBy('id', 'desc')->paginate(10);

        return response()->json($images);
    }
    public function create()
    {

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => ['required'],
            'images.*' => ['mimes:jpg,jpeg,png', 'max:10'],//20480
        ], [
            'images.*.*' => 'The selected images is invalid.',
        ]);

        if($validator->fails()) {
            if($validator->errors()->first('images.*')) {
                $validator->errors()->add('images', $validator->errors()->first('images.*'));
            }

            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $images = [];
        foreach($request->images as $request_image) {
            $path = Storage::disk('public')->putFile('recipes', new File($request_image));

            $image = new Image();

            $image->user_id = Auth::user()->id;
            $image->path = $path;

            $image->save();

            $images[] = $image;
        }

        return response()->json([
            'success' => 'Images uploaded.',
            'images' => $images
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        
    }
    public function update(Request $request, $id)
    {
        
    }
    public function destroy($id)
    {
        $image = Image::find($id);

        if(!$image) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json([
            'success' => 'Image deleted.'
        ]);
    }
}
