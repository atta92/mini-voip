<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();

        // Function to fetch user's calls
        $calls = $this->getUserCalls($user->id);

        // dd($calls);

        return Inertia::render('CallDashboard', [
            'user' => $user,
            'calls' => $calls,
        ]);
    }

    private function getUserCalls($userId)
    {
        return Call::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

}
