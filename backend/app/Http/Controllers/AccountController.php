<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Helper to log actions with affected users
     */
    protected function logAction($actor, string $eventType, string $level, string $message, array $affectedUserIds = [])
    {
        AuditLog::create([
            'user_id' => $actor->id ?? null,
            'event_type' => $eventType,
            'event_level' => $level,
            'message' => $message,
            'affected_user_ids' => $affectedUserIds, // store IDs directly
            'created_at' => now(),
        ]);
    }

    /**
     * List accounts
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'customer') {
            $accounts = Account::where('user_id', $user->id)->get();
        } else {
            $targetRole = $user->role === 'admin' ? 'teller' : 'customer';
            $accounts = Account::whereHas('user', fn($q) => $q->where('role', $targetRole))->get();
        }

        $this->logAction(
            $user,
            'account.listed',
            'info',
            "Listed accounts for role {$user->role}",
            $accounts->pluck('user_id')->unique()->toArray()
        );

        return response()->json($accounts);
    }

    /**
     * Show account
     */
    public function show(Request $request, Account $account)
    {
        $user = $request->user();
        $owner = $account->user;

        if ($user->role === 'customer' && $account->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'admin' && $owner->role !== 'teller') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'teller' && $owner->role !== 'customer') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->logAction(
            $user,
            'account.viewed',
            'info',
            "Viewed account {$account->number}",
            [$owner->id]
        );

        return response()->json($account->load('user'));
    }

    /**
     * Open a new account
     */
    public function store(Request $request)
    {
        $actor = $request->user();

        if (!in_array($actor->role, ['admin', 'teller'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name'    => 'required|string',
            'type'    => 'required|string',
        ]);

        $targetUser = User::findOrFail($data['user_id']);

        if ($actor->role === 'admin' && $targetUser->role !== 'teller') {
            return response()->json(['message' => 'Admins can only open teller accounts'], 403);
        }

        if ($actor->role === 'teller' && $targetUser->role !== 'customer') {
            return response()->json(['message' => 'Tellers can only open customer accounts'], 403);
        }

        $account = Account::create([
            'user_id'  => $targetUser->id,
            'number'   => 'ACC' . now()->format('YmdHis') . rand(100, 999),
            'name'     => $data['name'],
            'type'     => $data['type'],
            'currency' => 'EUR',
            'balance'  => 0,
            'status'   => 'active',
        ]);

        $this->logAction(
            $actor,
            'account.opened',
            'info',
            "{$actor->role} opened account {$account->number} for user {$targetUser->id}",
            [$targetUser->id]
        );

        return response()->json($account, 201);
    }

    /**
     * Edit / close account
     */
    public function update(Request $request, Account $account)
    {
        $actor = $request->user();
        $owner = $account->user;

        if (!in_array($actor->role, ['admin', 'teller'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($actor->role === 'admin' && $owner->role !== 'teller') {
            return response()->json(['message' => 'Admins can only manage teller accounts'], 403);
        }

        if ($actor->role === 'teller' && $owner->role !== 'customer') {
            return response()->json(['message' => 'Tellers can only manage customer accounts'], 403);
        }

        $data = $request->validate([
            'name'   => 'string',
            'type'   => 'string',
            'status' => 'in:active,suspended,closed',
        ]);

        $account->update($data);

        $level = ($data['status'] ?? '') === 'closed' ? 'warning' : 'info';

        $this->logAction(
            $actor,
            'account.updated',
            $level,
            "Account {$account->number} updated by {$actor->role}",
            [$owner->id]
        );

        return response()->json($account);
    }
}
