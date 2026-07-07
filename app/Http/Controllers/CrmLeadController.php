<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CrmCompanyInfo;
use App\Models\CrmNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CrmLeadController extends Controller
{
    //
    public function index(Request $request)
    {
        $leads = CrmLead::query()
            ->select(
                'id',
                'uuid',
                'contact_name',
                'email',
                'mobile',
                'status',
                'assigned_to',
                'created_at',
                'updated_at'
            )
            ->with([
                'company:id,lead_id,company_name',
                'crmStatus:id,status',
                'user:id,name',
            ])

            // Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($q) use ($search) {
                    $q->where('contact_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('company_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('crmStatus', function ($q) use ($search) {
                            $q->where('status', 'like', "%{$search}%");
                        });
                });
            })

            // Status filter
            ->when(
                $request->filled('status') && strtoupper($request->status) !== 'ALL',
                function ($q) use ($request) {
                    $q->whereHas('crmStatus', function ($q) use ($request) {
                        $q->where('status', strtoupper($request->status));
                    });
                }
            )

            ->orderByDesc('updated_at')
            ->paginate($request->get('per_page', 25))
            ->appends($request->query());

        $allLeads = CrmLead::with('crmStatus')->get();

        $statusCounts = $allLeads
            ->groupBy(fn($lead) => optional($lead->crmStatus)->status)
            ->map(fn($group) => $group->count());

        return response()->json([
            'success' => true,
            'data' => $leads,
            'status_counts' => [
                'ALL' => $allLeads->count(),
                'LEAD' => $statusCounts->get('LEAD', 0),
                'QUALIFIED' => $statusCounts->get('QUALIFIED', 0),
                'OPPORTUNITY' => $statusCounts->get('OPPORTUNITY', 0),
                'NEGOTIATION' => $statusCounts->get('NEGOTIATION', 0),
                'WIN' => $statusCounts->get('WIN', 0),
                'LOST' => $statusCounts->get('LOST', 0),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $lead = CrmLead::create([

                'contact_name' => $request->contact_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'position' => $request->position ?? null,
                'status' => $request->status,
                'source' => $request->source,
                'assigned_to' => auth()->id(),
                'estimated_value' => $request->est_value,
                'expected_close_date' => Carbon::now()->addWeek(),
                'status_updated_at' => now(),
            ]);

            CrmCompanyInfo::create([
                'lead_id' => $lead->id,
                'company_name' => $request->company_name,
            ]);
            if (isset($request->notes)) {

                CrmNote::create([
                    'lead_id' => $lead->id,
                    'note' => $request->notes,
                    'created_by' => auth()->id(),
                ]);
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => $lead->load('company', 'notes')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($uuid)
    {

        $lead = CrmLead::with(
            'company',
            'notes.user',
            'activities.user',
            'crmStatus:id,status',
            'user',
            'proposals.status'
        )->where('uuid', $uuid)->firstOrFail();

        return response()->json([

            'success' => true,
            'data' => $lead
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $updatePayload = [
            'contact_name' => $request->contact_name,
            'email' => $request->contact_email,
            'mobile' => $request->contact_mobile,
        ];
        try {
            DB::beginTransaction();

            CrmLead::where('uuid', $uuid)->firstOrFail()->update($updatePayload);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
        return response()->json([

            'success' => true,
            'message' => 'updated!'
        ]);
    }

    public function destroy($id)
    {
        CrmLead::findOrFail($id)->delete();
        return response()->json([

            'success' => true,
            'message' => 'Deleted'
        ]);
    }
}
