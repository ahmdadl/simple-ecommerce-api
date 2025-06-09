<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Products\Models\Product;
use Modules\Products\Transformers\ProductResource;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'brand'])->active()->paginate();

        return api()->records(ProductResource::collection($products));
    }

    /**
     * Show the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product->loadMissing(['category', 'brand']);

        $record = new ProductResource($product);

        $relatedProducts = Product::where("category_id", $product->category_id)
            ->where("id", "!=", $product->id)
            ->inRandomOrder()
            ->limit(7)
            ->get();
        $relatedProducts = ProductResource::collection($relatedProducts);

        return api()->success([
            "record" => $record,
            "relatedProducts" => $relatedProducts,
        ]);
    }
}
