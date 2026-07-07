<?php

namespace App\Http\Controllers;

use App\Models\ChargeType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChargeTypeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['success' => true, 'data' => ChargeType::query()
            ->when($request->filled('search'), fn($q) => $q->where(function ($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%");
            }))
            // Lets the Port Charges / General Charges dropdowns ask for
            // only the charge types usable in that context, e.g.
            // ?applicable_to=PORT or ?applicable_to=GENERAL
            ->when($request->filled('applicable_to'), fn($q) => $q->where('applicable_to', $request->applicable_to))
            ->orderBy('name')
            ->paginate($request->get('per_page', 25))]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:charge_types,code'],
            'name' => ['required', 'string', 'max:100'],
            'applicable_to' => ['required', Rule::in([ChargeType::APPLICABLE_PORT, ChargeType::APPLICABLE_GENERAL])],
            'is_active' => ['boolean'],
        ]);

        $chargeType = ChargeType::create($validated);

        return response()->json(['success' => true, 'data' => $chargeType], 201);
    }

    public function show(ChargeType $chargeType)
    {
        return response()->json(['success' => true, 'data' => $chargeType]);
    }

    public function update(Request $request, ChargeType $chargeType)
    {
        $validated = $request->validate([
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('charge_types', 'code')->ignore($chargeType->charge_type_id, 'charge_type_id')],
            'name' => ['sometimes', 'string', 'max:100'],
            'applicable_to' => ['sometimes', Rule::in([ChargeType::APPLICABLE_PORT, ChargeType::APPLICABLE_GENERAL])],
            'is_active' => ['boolean'],
        ]);

        $chargeType->update($validated);

        return response()->json(['success' => true, 'data' => $chargeType]);
    }

    public function destroy(ChargeType $chargeType)
    {
        $chargeType->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
