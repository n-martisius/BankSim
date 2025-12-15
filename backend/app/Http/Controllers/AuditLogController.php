<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    /**
     * List audit logs.
     * Admins: all logs, optionally filter by user_id (actor or affected)
     * Tellers: logs related to a specific customer user
     */
    public function index(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $query = AuditLog::query();

        if ($authUser->role === 'admin') {
            // Admins can filter by user_id (actor or affected)
            if ($request->has('user_id')) {
                $userId = (int) $request->input('user_id');
                $query->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->orWhereRaw("JSON_CONTAINS(IFNULL(affected_user_ids, '[]'), JSON_ARRAY(?))", [$userId]);
                });
            }
        } elseif ($authUser->role === 'teller') {
            // Tellers must specify a customer user_id
            $customerId = (int) $request->input('user_id');
            if (!$customerId) {
                return response()->json(['message' => 'user_id parameter is required for tellers'], 400);
            }

            $customer = User::where('id', $customerId)->where('role', 'customer')->first();
            if (!$customer) {
                return response()->json(['message' => 'Customer not found or invalid'], 404);
            }

            $query->where(function ($q) use ($customerId) {
                $q->where('user_id', $customerId)
                    ->orWhereRaw("JSON_CONTAINS(IFNULL(affected_user_ids, '[]'), JSON_ARRAY(?))", [$customerId]);
            });
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return response()->json($logs);
    }

    /**
     * Show a specific audit log
     */
    public function show(Request $request, AuditLog $auditLog): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->role === 'admin') {
            // Admins can view any log
        } elseif ($authUser->role === 'teller') {
            $customerIds = User::where('role', 'customer')->pluck('id')->toArray();

            $related = in_array($auditLog->user_id, $customerIds) ||
                collect($auditLog->affected_user_ids)->intersect($customerIds)->isNotEmpty();

            if (!$related) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($auditLog);
    }
}
