<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Rate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class CallController extends Controller
{
    // User ke calls list
    public function index()
    {
        $calls = auth()->user()->calls()->latest()->get();
        return response()->json($calls);
    }

    // Call start
    public function start(Request $request)
    {
        $user = auth()->user();

        if ($user->balance <= 0) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        $call = Call::create([
            'user_id' => $user->id,
            'started_at' => now(),
            'status' => 'started'
        ]);

        // Redis me start time store karo
        Redis::set("call_start_{$call->id}", now());

        return response()->json($call);
    }

    // Call end
    public function end(Call $call)
    {
        $user = auth()->user();

        // Ownership check
        if ($call->user_id !== $user->id || $call->status !== 'started') {
            return response()->json(['error' => 'Invalid call'], 400);
        }

        $start = Redis::get("call_start_{$call->id}");
        $duration = now()->diffInSeconds($start);

        $rate = Rate::first()->price_per_minute ?? 5;
        $cost = ceil($duration / 60) * $rate;

        // Update call
        $call->update([
            'ended_at' => now(),
            'duration_seconds' => $duration,
            'cost' => $cost,
            'status' => 'ended'
        ]);

        // Deduct user balance
        $user->decrement('balance', $cost);

        // Redis key delete
        Redis::del("call_start_{$call->id}");

        return response()->json($call);
    }
}
