<?php


namespace App\Http\Controllers;

use App\Models\ContainerClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContainerClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = ContainerClass::query()
            ->when($request->filled('search'), fn($q) => $q->where('class', 'like', "%{$request->search}%"))
            ->orderBy('class')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $classes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class' => ['required', 'string', 'max:255', 'unique:container_class,class'],
        ]);

        $containerClass = ContainerClass::create($validated);

        return response()->json(['success' => true, 'data' => $containerClass], 201);
    }

    public function show(ContainerClass $containerClass)
    {
        return response()->json(['success' => true, 'data' => $containerClass]);
    }

    public function update(Request $request, ContainerClass $containerClass)
    {
        $validated = $request->validate([
            'class' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('container_class', 'class')->ignore($containerClass->id),
            ],
        ]);

        $containerClass->update($validated);

        return response()->json(['success' => true, 'data' => $containerClass]);
    }

    public function destroy(ContainerClass $containerClass)
    {
        $containerClass->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
