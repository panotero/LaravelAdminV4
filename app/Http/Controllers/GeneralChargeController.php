<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesVersionedRates;
use App\Models\ChargeType;
use App\Models\GeneralCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GeneralChargeController extends Controller
{
    use ManagesVersionedRates;

    public function index(Request $request)
    {
        $charges = GeneralCharge::query()
            ->with('chargeType:charge_type_id,code,name')
            ->when($request->filled('search'), fn($q) => $q->whereHas(
                'chargeType',
                fn($q) => $q->where('name', 'like', "%{$request->search}%")
            ))
            ->orderByDesc('effective_date')
            ->paginate($request->get('per_page', 25));

        return response()->json(['success' => true, 'data' => $charges]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'charge_type_id' => [
                'required',
                'integer',
                Rule::exists('charge_types', 'charge_type_id')->where('applicable_to', ChargeType::APPLICABLE_GENERAL),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        $charge = DB::transaction(function () use ($validated) {
            $this->closePreviousVersion(
                GeneralCharge::class,
                ['charge_type_id' => $validated['charge_type_id']],
                $validated['effective_date']
            );

            return GeneralCharge::create($validated + ['is_active' => true]);
        });

        return response()->json(['success' => true, 'data' => $charge], 201);
    }

    public function show(GeneralCharge $generalCharge)
    {
        return response()->json(['success' => true, 'data' => $generalCharge->load('chargeType')]);
    }

    public function update(Request $request, GeneralCharge $generalCharge)
    {
        $validated = $request->validate([
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $generalCharge->update($validated);

        return response()->json(['success' => true, 'data' => $generalCharge]);
    }

    public function destroy(GeneralCharge $generalCharge)
    {
        $generalCharge->delete();

        return response()->json(['success' => true, 'data' => null]);
    }
}
