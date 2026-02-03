<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryMediaController extends Controller
{

    public function store(Request $request, $category)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);
        $model = Category::find($category);

        $file = $request->file('file');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        // $file->move(public_path('uploads/category'), $filename);

        $media = $model->addMedia($file)
            ->usingFileName($filename)
            ->toMediaCollection('category');

        return response()->json([
            'id' => $media->id,
            'url' => $media->getUrl(),
        ]);
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
