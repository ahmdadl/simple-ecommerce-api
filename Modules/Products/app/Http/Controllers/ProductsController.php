<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Brands\Models\Brand;
use Modules\Brands\Transformers\BrandResource;
use Modules\Categories\Models\Category;
use Modules\Categories\Transformers\CategoryResource;
use Modules\Products\Models\Product;
use Modules\Products\Transformers\ProductResource;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::with(['category', 'brand'])->active()->paginate();

        $response["records"] = ProductResource::collection($products);
        $response["paginationInfo"] = $this->getPaginationInfo($products);

        if ($request->has("forCategory")) {
            $response["category"] = new CategoryResource(
                Category::active()
                    ->where("slug", $request->string("forCategory")->value())
                    ->firstOrFail()
            );
        }

        if ($request->has("forBrand")) {
            $response["brand"] = new BrandResource(
                Brand::active()
                    ->where("slug", $request->string("forBrand")->value())
                    ->firstOrFail()
            );
        }

        return api()->success($response);
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

    /**
     * get pagination info
     */
    private function getPaginationInfo(LengthAwarePaginator $products): object
    {
        return (object) [
            "current_page" => $products->currentPage(),
            "per_page" => $products->perPage(),
            "total" => $products->total(),
            "last_page" => $products->lastPage(),
            "from" => $products->firstItem(),
            "to" => $products->lastItem(),
            "has_more_pages" => $products->hasMorePages(),
        ];
    }
}
