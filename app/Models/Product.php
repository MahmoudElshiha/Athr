<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    public $guarded = [];

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productPenalties(): HasMany
    {
        return $this->hasMany(ProductPenalty::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderProducts(): BelongsToMany
    {
        return $this->belongsToMany(OrderProduct::class);
    }

    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

    public function favourites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourites');
    }


    public function isFavouritedByUser($user_id): bool
    {

        return $this->favourites()->where('user_id', $user_id)->exists();
    }

    public function favourites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourites');
    }
    public  function isFavourite(): bool
    {
        return $this->favourites()->where('user_id', auth()->id())->exists();
    }

    public function uploadImages( $valdiated){
        $uploadedImages = [];
        foreach ($valdiated['images'] as $image) {
            try {
                $path = $image->store('products/' . $this->id, 'public');

                $productImage = $this->productImages()->create([
                    'product_id' => $this->id,
                    'image' => $path,
                ]);

                $uploadedImages[] = $productImage;
            } catch (\Exception $e) {
                // If any upload fails, delete all previously uploaded images
                foreach ($uploadedImages as $uploadedImage) {
                    Storage::disk('public')->delete($uploadedImage->image);
                    $uploadedImage->delete();
                }

                return api_error('Failed to upload images', 500);
            }
        }
    }
}
