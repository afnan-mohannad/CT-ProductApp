<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private string $file = 'products.json';

    public function index()
    {
        $data = $this->getData();
        $products = collect($data)->sortByDesc('created_at')->values()->all();
        $totalSum = collect($products)->sum(fn($p) => $p['quantity'] * $p['price']);
        return view('products.index', compact('products', 'totalSum'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $products = $this->getData();
        $data['created_at'] = now()->toDateTimeString();
        $products[] = $data;
        $this->saveData($products);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $index)
    {
        $data = $request->validate([
            'product' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $products = $this->getData();
        if (!isset($products[$index])) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $products[$index]['product'] = $data['product'];
        $products[$index]['quantity'] = $data['quantity'];
        $products[$index]['price'] = $data['price'];
        $this->saveData($products);

        return response()->json(['success' => true]);
    }

    private function getData(): array
    {
        $path = public_path($this->file);
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true);
    }

    private function saveData(array $data): void
    {
        file_put_contents(public_path($this->file), json_encode($data, JSON_PRETTY_PRINT));
    }
}
