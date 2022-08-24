<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as InterventionImage;

class ProductController extends Controller
{
    const IMAGE_WIDTH = 2400;
    const IMAGE_HEIGHT = 2400;
    const THUMB_IMAGE_WIDTH = 600;
    const THUMB_IMAGE_HEIGHT = 600;
    const THUMB_PREFIX = 'thumb_';
    const FOLDER = 'image';

    public function store(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {

        $request_path = $request->file('image');
        $product = $request->input('product');
        if ($request->hasFile('image')) {
            $image_path = self::FOLDER . '/' . Str::uuid() . '.jpg';
            $thum_path = self::FOLDER . '/' . self::THUMB_PREFIX . Str::uuid() . '.jpg';

            $image = InterventionImage::make($request_path);

            $imageResized = $image->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, function ($const) {
                $const->aspectRatio();
                $const->upsize();
            })
                ->orientate();

            $width = $imageResized->width();
            $height = $imageResized->height();

            Storage::makeDirectory(self::FOLDER);
            Storage::put($image_path, $imageResized->stream('jpg', 75)->__toString());

            $thumResized = $image->resize(self::THUMB_IMAGE_WIDTH, self::THUMB_IMAGE_HEIGHT, function ($const) {
                $const->aspectRatio();
                $const->upsize();
            })
                ->orientate();

            Storage::put($thum_path, $thumResized->stream('jpg', 75)->__toString());

            $data = Product::create(['product-name' => $product, 'picture-src' => $image_path, 'thumb' => $thum_path, 'width' => $width, 'height' => $height]);

            return response("200", 200);
        } else {
            return response("No image in request", 500);
        }

    }

    public function view()
    {
        $allProducts = [];

        foreach (Product::All() as $product) {
            $allProducts[] = $product;
        }
        return response()->json($allProducts);
    }

    public function delete($id)
    {
        if (Gate::allows('delete-product')) {
            //return response()->json(Product::where('id', $id)->first());
            return response()->json(Product::findOrFail($id));
        } else {
            abort(403);

        }
    }

    public function update($id)
    {
        return response()->json(Product::findOrFail($id));

    }


}
