<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return $this->sendResponse(['categories' => Category::get()], 'Berhasil menampilkan data kategori');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error.", $validator->errors());
        }

        $category = Category::create(['name' => $request->name]);
        $category->save();

        return $this->sendResponse(['id' => $category->id, 'name' => $category->name], 'Kategori berhasil dibuat!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = Category::where(['id' => $id])->first();
        if (!$category) {
            return $this->sendError("Not found", [], 404);
        }

        return $this->sendResponse(['category' => $category], 'Berhasil menampilkan data kategori');
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
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error.", $validator->errors());
        }

        $category = Category::where(['id' => $id])->first();
        if (!$category) {
            return $this->sendError("Not found", [], 404);
        }

        $category->name = $request->name;
        $category->save();

        return $this->sendResponse(['name' => $category->name], 'Kategori berhasil di perbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::where(['id' => $id])->first();
        if (!$category) {
            return $this->sendError("Not found", [], 404);
        }

        $category->delete();

        return $this->sendResponse(null, 'Kategori berhasil dihapus!');
    }
}
