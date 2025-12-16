<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;

class TransactionController extends Controller
{
    /**
     * View transaction history
     * - Tellers: all transactions
     * - Customers: only transactions involving their accounts
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'customer') {
            $transactions = Transaction::whereHas('fromAccount', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->orWhereHas('toAccount', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest()
                ->get();
        } else {
            $transactions = Transaction::latest()->get();
        }

        return response()->json($transactions);
    }

    /**
     * View a single transaction
     */
    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'customer') {
            $ownsFrom = $transaction->fromAccount->user_id === $user->id;
            $ownsTo   = $transaction->toAccount->user_id === $user->id;

            if (!$ownsFrom && !$ownsTo) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        return response()->json($transaction);
    }

    /**
     * Create a transaction
     * ONLY tellers allowed
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'teller') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'type'             => 'required|in:deposit,withdrawal,transfer',
            'from_account_id'  => 'nullable|exists:accounts,id',
            'to_account_id'    => 'nullable|exists:accounts,id|different:from_account_id',
            'amount'           => 'required|numeric|min:0.01',
            'details'          => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $from  = isset($data['from_account_id'])
                ? Account::lockForUpdate()->find($data['from_account_id'])
                : null;
            $to   = isset($data['to_account_id'])
                ? Account::lockForUpdate()->find($data['to_account_id'])
                : null;

            // BUSINESS RULES
            if ($data['type'] === 'withdrawal' || $data['type'] === 'transfer') {
                if ($from->balance < $data['amount']) {
                    return response()->json(['message' => 'Insufficient funds'], 400);
                }
                $from->balance -= $data['amount'];
                $from->save();
            }

            if ($data['type'] === 'deposit' || $data['type'] === 'transfer') {
                if (!$to) {
                    return response()->json(['message' => 'Destination account required'], 400);
                }
                $to->balance += $data['amount'];
                $to->save();
            }
            if ($data['type'] === 'deposit') {
                $transaction = Transaction::create([
                    'user_id'         => $user->id,
                    'to_account_id'   => $to?->id,
                    'type'            => $data['type'],
                    'amount'          => $data['amount'],
                    'currency'        => 'EUR',
                    'status'          => 'completed',
                    'details'         => $data['details']
                ]);
            } else if ($data['type'] === 'withdrawal') {
                $transaction = Transaction::create([
                    'user_id'         => $user->id,
                    'from_account_id' => $from->id,
                    'type'            => $data['type'],
                    'amount'          => $data['amount'],
                    'currency'        => 'EUR',
                    'status'          => 'completed',
                    'details'         => $data['details']
                ]);
            } else if ($data['type'] === 'transfer') {
                $transaction = Transaction::create([
                    'user_id'         => $user->id,
                    'from_account_id' => $from->id,
                    'to_account_id'   => $to?->id,
                    'type'            => $data['type'],
                    'amount'          => $data['amount'],
                    'currency'        => 'EUR',
                    'status'          => 'completed',
                    'details'         => $data['details']
                ]);
            }

            DB::commit();

            return response()->json($transaction, 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Transaction failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transfer shortcut (optional but clean)
     */
    public function transfer(Request $request): JsonResponse
    {
        $request->merge(['type' => 'transfer']);
        return $this->store($request);
    }
}
