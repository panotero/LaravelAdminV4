<?php

namespace App\Http\Controllers;

use App\Models\ContainerSize;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContainerSizeController extends Controller
{
    public function index(Request $request)
    {
        $sizes = ContainerSize::query()
            ->when($request->filled('search'), fn($q) => $q->where('size', 'like', "%{$request->search}%"))
            ->orderBy('size')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $sizes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'size' => ['required', 'string', 'max:255', 'unique:container_size,size'],
        ]);

        $containerSize = ContainerSize::create($validated);

        return response()->json(['success' => true, 'data' => $containerSize], 201);
    }

    public function show(ContainerSize $containerSize)
    {
        return response()->json(['success' => true, 'data' => $containerSize]);
    }

    public function update(Request $request, ContainerSize $containerSize)
    {
        $validated = $request->validate([
            'size' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('container_size', 'size')->ignore($containerSize->id),
            ],
        ]);

        $containerSize->update($validated);

        return response()->json(['success' => true, 'data' => $containerSize]);
    }

    public function destroy(ContainerSize $containerSize)
    {
        $containerSize->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
