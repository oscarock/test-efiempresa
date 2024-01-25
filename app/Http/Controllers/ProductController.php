<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'ean_13' => 'required|string|max:13',
            'active' => 'boolean',
        ]);

        $product = Product::create($request->all());

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'ean_13' => 'required|string|max:13',
            'active' => 'boolean',
        ]);

        $product->update($request->all());

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    /**
     * Search for products based on filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // Aplicar filtros
        $query = Product::query();

        if ($request->has('price')) {
            $query->where('price', '=', $request->input('price'));
        }

        if ($request->has('availability')) {
            $query->where('availability', '=', $request->input('availability'));
        }

        if ($request->has('ean_13')) {
            $query->where('ean_13', '=', $request->input('ean_13'));
        }

        // Obtener resultados
        $products = $query->get();

        return response()->json(['products' => $products], 200);
    }
}
