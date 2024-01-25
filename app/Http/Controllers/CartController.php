<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function create(Request $request)
    {
        // Validaciones personalizadas
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Obtener el producto
        $product = Product::findOrFail($request->input('product_id'));

        // Realizar controles de stock
        if ($product->stock < $request->input('quantity')) {
            return response()->json(['error' => 'Stock insuficiente para este producto.'], 400);
        }

        // Calcular precio total (puedes agregar impuestos aquí si es necesario)
        $totalPrice = $product->price * $request->input('quantity');

        // Crear el carrito
        $cart = Cart::create([
            'user_id' => auth()->user()->id,
            'product_id' => $product->id,
            'quantity' => $request->input('quantity'),
            'total_price' => $totalPrice,
        ]);

        // Actualizar el stock del producto
        $product->decrement('stock', $request->input('quantity'));

        return response()->json(['message' => 'Carrito creado con éxito', 'cart' => $cart], 201);
    }
}
