<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    private $plans = [
        ['id' => 'A', 'name' => 'Type A', 'price' => 49.99, 'limit' => 5],
        ['id' => 'B', 'name' => 'Type B', 'price' => 34.99, 'limit' => 3],
        ['id' => 'C', 'name' => 'Type C', 'price' => 19.99, 'limit' => 1],
    ];

    public function pricing()
    {
        return view('pricing', [
            'plans' => $this->plans
        ]);
    }

public function subscribe(Request $request)
{
    $request->validate([
      'type' => 'required|in:A,B,C',
    ]);

    $user = auth()->user();

    // map code → credits
    $credits = match($request->type) {
      'A' => 5,
      'B' => 3,
      'C' => 1,
    };

    // only course_credits exists in your table:
    $user->subscriptions()->create([
      'course_credits' => $credits,
    ]);

    // Use the course_balance field which accounts for used credits
    $newBalance = $user->course_balance;

    return response()->json([
      'message'     => "Successfully subscribed to Plan {$request->type}",
      'new_balance' => $newBalance,
    ]);
}

}
