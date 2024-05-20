<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return $this->sendResponse(['products' => Product::get()], 'Berhasil menampilkan data!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => "required",
            'price' => 'required|min:1',
            'category_id' => 'required',
            'expired_at' => 'required|date_format:Y-m-d',
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Kesalahan validasi.", $validator->errors());
        }

        $category = Category::where(['name' => $request->category_id])->first();
        if (!$category) {
            return $this->sendError("Kategori tidak ditemukan", [], 404);
        }

        try {
            //code...
            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category_id = $category->id;
            $product->expired_at = $request->expired_at;
            $product->modified_by = $request->user()->email;

            $path = Storage::disk('public')->put('products', $request->image);
            $product->image = $path;
            $product->save();

            return $this->sendResponse(['id' => $product->id], 'Produk berhasil dibuat!', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError("Ada kesalahan", [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $product = Product::where(['id' => $id])->first();

        if (!$product) return $this->sendError("Produk tidak ditemukan", [], 404);

        return $this->sendResponse(['product' => $product], 'Berhasil menampilkan data produk');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => "required",
            'price' => 'required|min:1',
            'category_id' => 'required',
            'expired_at' => 'required|date',
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Kesalahan validasi.", $validator->errors());
        }

        $category = Category::where(['name' => $request->category_id])->first();
        if (!$category) {
            return $this->sendError("Katgori tidak ditemukan", [], 404);
        }

        $product = Product::where(['name' => $id])->first();

        if (!$product) return $this->sendError("Produk tidak ditemukan", [], 404);

        try {
            //code...
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category_id = $category->id;
            $product->expired_at = $request->expired_at;
            $product->modified_by = $request->user()->email;

            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->put('products', $request->image);
                $product->image = $path;
            }
            $product->save();

            return $this->sendResponse(null, 'Produk berhasil di update!', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError("Ada kesalahan", [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = Product::where(['id' => $id])->first();

        if (!$product) return $this->sendError("Produk tidak ditemukan", [], 404);

        $product->delete();

        return $this->sendResponse(null, 'Data produk berhasil dihapus!');
    }
}
