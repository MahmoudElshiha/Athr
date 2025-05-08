<?php

namespace App\Http\Controllers;

use App\Http\Requests\Favourite\ManageFavouriteRequest;
use App\Http\Resources\FavouriteResource;
use App\Models\Favourite;
use App\Models\User;
use App\Models\Product;


class FavouriteController extends Controller
{
    public function index()
    {
        return api_success(
            FavouriteResource::collection(
                auth()->user()->favourites()->with('product')->get()
            )
        );
    }

    public function store(ManageFavouriteRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        // Check if the product exists for the user in favourites
        $existingFavourite = $user->favourites()
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingFavourite) {
            return api_error('Product already in favourites', 422);
        }

        $favourite = Favourite::create([
            'user_id' => $user->id,
            'product_id' => $validated['product_id']
        ]);

        return api_success(
            new FavouriteResource($favourite->load('product')),
        );
    }


    public function destroy(int $user_id, int $fav_id)
    {
        $favourite  = Favourite::find($fav_id);

        // Check if the favourite exists
        if (!$favourite) {
            return api_error('Favourite not found', 404);
        }

        if ($favourite->user_id !== auth()->id()) {
            return api_error('Unauthorized', 403);
        }

        $favourite->delete();

        return api_success();
    }
}
