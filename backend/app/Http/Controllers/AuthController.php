<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Helper to log actions with affected users
     *
     * @param User|null $actor The user performing the action
     * @param string $event Event type string
     * @param string $level Event level (info, warning, error)
     * @param string|null $message Optional custom message
     * @param array|null $affectedUserIds Optional list of affected users
     */
    protected function logAction(?User $actor, string $event, string $level = 'info', ?string $message = null, array $affectedUserIds = [])
    {
        AuditLog::create([
            'user_id'           => $actor?->id,
            'event_type'        => $event,
            'event_level'       => $level,
            'message'           => $message ?? $event,
            'affected_user_ids' => $affectedUserIds ? json_encode($affectedUserIds) : null,
            'created_at'        => now(),
        ]);
    }

    /**
     * Login user and return an API token.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            $this->logAction(null, 'auth.login_failed', 'warning', "Failed login attempt for email {$request->email}");
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->status === 'closed') {
            Auth::logout();
            $this->logAction($user, 'auth.login_closed', 'warning', 'Attempted login on closed account');
            return response()->json(['message' => 'Your account is closed'], 403);
        }

        if ($user->status === 'suspended') {
            Auth::logout();
            $this->logAction($user, 'auth.login_suspended', 'warning', 'Attempted login on suspended account');
            return response()->json(['message' => 'Your account is suspended'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->logAction($user, 'auth.login_success', 'info', 'User logged in');

        return response()->json([
            'user'       => $user,
            'token'      => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user (delete current token or all tokens).
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            if (method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            } elseif (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }

            $this->logAction($user, 'auth.logout', 'info', 'User logged out');
        }

        return response()->json(['message' => 'Logged out'], 200);
    }

    /**
     * Return authenticated user info.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->logAction($user, 'auth.me_viewed', 'info', 'Viewed own profile');

        return response()->json($user);
    }
}
