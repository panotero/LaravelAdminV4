<?php

namespace App\Http\Controllers;

use App\Models\SettingRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(
            SettingRole::withCount('users')->orderBy('role_name')->get()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => ['required', 'string', 'max:255', 'unique:setting_role,role_name'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        try {
            $role = SettingRole::create(['role_name' => $request->role_name]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
                'data' => $role,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create role', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create role.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $role = SettingRole::find($id);

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'role_name' => ['required', 'string', 'max:255', 'unique:setting_role,role_name,' . $id],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        try {
            $role->update(['role_name' => $request->role_name]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'data' => $role,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update role', ['id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update role.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        $role = SettingRole::find($id);

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found.'], 404);
        }

        $usersCount = $role->users()->count();

        if ($usersCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this role - {$usersCount} user(s) are still assigned to it.",
            ], 422);
        }

        try {
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete role', ['id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role.',
            ], 500);
        }
    }
}
