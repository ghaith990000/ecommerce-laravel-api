<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    public function index(Request $request){
        $query = Product::query();

        // Apply filters
        if($request->has('category_id')){
            $query->where('category_id', $request->category_id);
        }

        // Apply sorting
        if($request->has('sort_by')){
            $sortBy = $request->sort_by;
            $sortDirection = $request->has('sort_dir') && $request->sort_dir === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortBy, $sortDirection);
        }

        // Apply pagination
        $perPage = $request->has('per_page') ? $request->per_page : 10;
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required|string',
        //     'description' => 'required|string',
        //     'price' => 'required|numeric',
        // ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

}
