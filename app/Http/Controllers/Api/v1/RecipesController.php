<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Recipe;
use App\Models\Ingridient;



use Illuminate\Support\Facades\DB;

class RecipesController extends Controller
{
    public function index(Request $request)
    {
        //dump($request->all());

        $filterIngridients = ($request->ingridients && is_array($request->ingridients)) ? $request->ingridients : [];

        //$recipes = Recipe::with('ingridients:id,title');
        $recipes = new Recipe();

        if($filterIngridients) {
            $recipes = $recipes->whereHas('ingridients', function (Builder $query) use ($filterIngridients) {
                $query->whereIn('ingridient_id', $filterIngridients);
            });
        }


        $recipes = $recipes->paginate(10);
        $ingridients = Ingridient::get(['id', 'title']);

        return response()->json([
            /*test*/'user' => auth()->user()->name ?? 'null',
            'recipes' => $recipes,
            'ingridients' => $ingridients,
            'filterIngridients' => $filterIngridients,
        ], 200);
    }
    public function show($id)
    {
        $recipe = Recipe::find($id);
        /*
        $test = DB::select(
            
        );*/

        //dd($recipe->recipe_comments->toArray());

        return response()->json([
            //'test' => $test,
            'recipe' => $recipe,
            'recipe_comments' => $recipe->recipe_comments,
        ], 200);
    }
}
