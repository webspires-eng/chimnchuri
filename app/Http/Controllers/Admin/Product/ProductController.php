<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ItemStoreRequest;
use App\Models\Item;
use App\Services\Api\V1\Admin\ItemService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ItemService $itemService
    ) {}
    public function index()
    {
        $products = $this->itemService->getAllItems();
        // return $products->load("sizes");
        $data["products"] = $products;
        return view("admin.products.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.products.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request)
    {

        $products = $this->itemService->store($request->all());

        return $products;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Item::with("sizes")->find($id);
        // return $product;

        return view("admin.products.edit", compact("product"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $products = $this->itemService->updateItem($id, $request->all());
        return $products;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $products = $this->itemService->deleteItem($id);

        return redirect()->route('products.index');
    }
}
