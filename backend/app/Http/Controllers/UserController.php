<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Helper to log actions using affected_user_ids
    protected function logAction(User $actor, string $event, array $affectedUserIds = [], string $level = 'info', ?string $message = null)
    {
        AuditLog::create([
            'user_id'           => $actor->id,
            'event_type'        => $event,
            'event_level'       => $level,
            'message'           => $message ?? $event,
            'affected_user_ids' => $affectedUserIds ?: null,
            'created_at'        => now(),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->logAction($user, 'user.me_viewed', [$user->id], 'info', 'Viewed own profile');

        return response()->json([
            'id'       => $user->id,
            'username' => $user->name,
            'email'    => $user->email,
            'role'     => $user->role,
            'status'   => $user->status,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $users = User::select('id', 'name', 'email', 'role', 'status', 'created_at')->get();
        } elseif ($user->role === 'teller') {
            $users = User::where('role', 'customer')
                ->select('id', 'name', 'email', 'role', 'status', 'created_at')
                ->get();
        } else {
            $this->logAction($user, 'user.list_forbidden', [$user->id], 'warning', 'Tried to list users without permission');
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->logAction($user, 'user.listed', $users->pluck('id')->all(), 'info', 'Listed users');

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,teller,customer',
            'status'   => 'nullable|in:active,suspended,closed',
        ]);

        // Role-based restrictions
        if ($authUser->role === 'admin' && $data['role'] !== 'teller') {
            $this->logAction($authUser, 'user.create_forbidden', [], 'warning', 'Admins can only create tellers');
            return response()->json(['message' => 'Admins can only manage tellers'], 403);
        }
        if ($authUser->role === 'teller' && $data['role'] !== 'customer') {
            $this->logAction($authUser, 'user.create_forbidden', [], 'warning', 'Tellers can only create customers');
            return response()->json(['message' => 'Tellers can only manage customers'], 403);
        }
        if ($authUser->role === 'customer') {
            $this->logAction($authUser, 'user.create_forbidden', [], 'warning', 'Customer tried to create user');
            return response()->json(['message' => 'Customers cannot create users'], 403);
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'status'   => $data['status'] ?? 'active',
        ]);

        $this->logAction($authUser, 'user.created', [$user->id], 'info', "Created user ID {$user->id}");

        return response()->json($user, 201);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->role === 'teller' && $user->role !== 'customer') {
            $this->logAction($authUser, 'user.show_forbidden', [$user->id], 'warning', "Attempted to view user ID {$user->id}");
            return response()->json(['message' => 'Forbidden'], 403);
        }
        if ($authUser->role === 'customer') {
            $this->logAction($authUser, 'user.show_forbidden', [$user->id], 'warning', "Customer attempted to view user ID {$user->id}");
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->logAction($authUser, 'user.viewed', [$user->id], 'info', "Viewed user ID {$user->id}");

        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->role === 'admin' && $user->role !== 'teller') {
            $this->logAction($authUser, 'user.update_forbidden', [$user->id], 'warning', "Admin tried to update non-teller ID {$user->id}");
            return response()->json(['message' => 'Admins can only update tellers'], 403);
        }
        if ($authUser->role === 'teller' && $user->role !== 'customer') {
            $this->logAction($authUser, 'user.update_forbidden', [$user->id], 'warning', "Teller tried to update non-customer ID {$user->id}");
            return response()->json(['message' => 'Tellers can only update customers'], 403);
        }
        if ($authUser->role === 'customer') {
            $this->logAction($authUser, 'user.update_forbidden', [$user->id], 'warning', "Customer tried to update user ID {$user->id}");
            return response()->json(['message' => 'Customers cannot update users'], 403);
        }

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'status'   => 'sometimes|in:active,suspended,closed',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        $this->logAction($authUser, 'user.updated', [$user->id], 'info', "Updated user ID {$user->id}");

        return response()->json($user);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->role === 'admin' && $user->role !== 'teller') {
            $this->logAction($authUser, 'user.close_forbidden', [$user->id], 'warning', "Admin tried to close non-teller ID {$user->id}");
            return response()->json(['message' => 'Admins can only delete tellers'], 403);
        }
        if ($authUser->role === 'teller' && $user->role !== 'customer') {
            $this->logAction($authUser, 'user.close_forbidden', [$user->id], 'warning', "Teller tried to close non-customer ID {$user->id}");
            return response()->json(['message' => 'Tellers can only delete customers'], 403);
        }
        if ($authUser->role === 'customer') {
            $this->logAction($authUser, 'user.close_forbidden', [$user->id], 'warning', "Customer tried to close user ID {$user->id}");
            return response()->json(['message' => 'Customers cannot delete users'], 403);
        }

        $user->status = 'closed';
        $user->save();

        $this->logAction($authUser, 'user.closed', [$user->id], 'info', "Closed user ID {$user->id}");

        return response()->json(['message' => 'User closed']);
    }

    public function updateSelf(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        $this->logAction($user, 'user.self_updated', [$user->id], 'info', 'Updated own account');

        return response()->json([
            'message' => 'Account updated',
            'user'    => $user,
        ]);
    }

    public function suspend(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->role === 'admin' && $user->role !== 'teller') {
            return response()->json(['message' => 'Admins can only suspend tellers'], 403);
        }
        if ($authUser->role === 'teller' && $user->role !== 'customer') {
            return response()->json(['message' => 'Tellers can only suspend customers'], 403);
        }
        if ($authUser->role === 'customer') {
            return response()->json(['message' => 'Customers cannot suspend anyone'], 403);
        }

        $user->status = 'suspended';
        $user->save();

        $this->logAction($authUser, 'user.suspended', [$user->id], 'info', "Suspended user ID {$user->id}");

        return response()->json([
            'message' => 'User suspended',
            'user'    => $user,
        ]);
    }

    public function activate(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->role === 'admin' && $user->role !== 'teller') {
            return response()->json(['message' => 'Admins can only activate tellers'], 403);
        }
        if ($authUser->role === 'teller' && $user->role !== 'customer') {
            return response()->json(['message' => 'Tellers can only activate customers'], 403);
        }
        if ($authUser->role === 'customer') {
            return response()->json(['message' => 'Customers cannot activate anyone'], 403);
        }

        $user->status = 'active';
        $user->save();

        $this->logAction($authUser, 'user.activated', [$user->id], 'info', "Activated user ID {$user->id}");

        return response()->json([
            'message' => 'User activated',
            'user'    => $user,
        ]);
    }
}
