<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ProductMediaController extends Controller
{
    public function store(Request $request,  $product)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
        ]);

        $model = Item::find($product);

        $file = $request->file('file');

        $fileName = time() . '_' . $file->getClientOriginalName();

        $media = $model
            ->addMedia($file)
            ->usingFileName($fileName)
            ->toMediaCollection('images');

        return response()->json([
            'id' => $media->id,
            'url' => $media->getUrl(),
        ]);

        // $media = $model
        //     ->addMediaFromRequest('file')
        //     ->toMediaCollection('images');

        // return response()->json([
        //     'id' => $media->id,
        //     'url' => $media->getUrl(),
        // ]);
    }

    public function destroy($id)
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($id);
        $media->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
