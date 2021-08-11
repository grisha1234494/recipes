<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Ingridient;

class IngridientsController extends Controller
{
    public function index()
    {
        $ingridients = Ingridient::paginate(10);

        return response()->json($ingridients);
    }
    public function create()
    {
        
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $ingridient = new Ingridient();

        $ingridient->title = $request->title;

        $ingridient->save();

        return response()->json([
            'success' => 'Ingridient created.',
            'ingridient' => $ingridient
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $ingridient = Ingridient::find($id);

        if(!$ingridient) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        return response()->json([
            'ingridient' => $ingridient
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $ingridient = Ingridient::find($id);

        if(!$ingridient) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $ingridient->title = $request->title;

        $ingridient->save();

        return response()->json([
            'success' => 'Ingridient updated.',
            'ingridient' => $ingridient
        ]);
    }
    public function destroy($id)
    {
        $ingridient = Ingridient::find($id);

        if(!$ingridient) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $ingridient->delete();

        return response()->json([
            'success' => 'Ingridient deleted.',
        ]);
    }
}
