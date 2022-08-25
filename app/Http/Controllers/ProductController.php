<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function store(Request $request): Response|Application|ResponseFactory
    {

        if ($request->hasFile('image') && $request->file('image')->isValid() && $request->has('product_name')) {
            $request_image_path = $request->file('image');

            $validated_product = $request->validate(['product_name' => 'required|min:2']);

            $image_uuid = Str::uuid();
            $image_path = self::FOLDER . '/' . $image_uuid . '.jpg';
            $thum_path = self::FOLDER . '/' . self::THUMB_PREFIX . $image_uuid . '.jpg';

            $image = InterventionImage::make($request_image_path);

            $imageResized = $image->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, function ($const) {
                $const->aspectRatio();
                $const->upsize();
            })
                ->orientate();

            $width = $imageResized->width();
            $height = $imageResized->height();

            Storage::makeDirectory(self::FOLDER);
            Storage::put($image_path, $imageResized->stream('jpg', 75)->__toString(), ['CacheControl' => 'max-age=315360000']);

            $thumResized = $image->resize(self::THUMB_IMAGE_WIDTH, self::THUMB_IMAGE_HEIGHT, function ($const) {
                $const->aspectRatio();
                $const->upsize();
            })
                ->orientate();

            Storage::put($thum_path, $thumResized->stream('jpg', 75)->__toString(), ['CacheControl' => 'max-age=315360000']);

            $validated_product += ['picture_src' => $image_path, 'thumb' => $thum_path, 'width' => $width, 'height' => $height];

            Product::create($validated_product);

            return response("Product stored successfully ", 200);
        } else {
            return response("No image or product name in request", 500);
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
            $product = Product::findOrFail($id);
            Storage::delete([$product->picture_src, $product->thumb]);
            $product->delete();

            return response()->json('Product ' . $id . ' deleted.');
        } else {
            abort(403);
        }
    }

    public function update($id, Request $request)
    {

     if($request->has('product_name')){
         $product = Product::findOrFail($id);

         $validated = $request->validate(['product_name' => 'required|min:2']);
         $product->product_name = $validated['product_name'];

         $product->save();

         return response("Name of product with id " . $id . " changed to " . $validated['product_name'], 200);
     } else {
         return response("No product name in request", 500);
     }
    }


}
