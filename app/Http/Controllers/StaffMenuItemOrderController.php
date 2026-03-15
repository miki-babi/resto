<?php

namespace App\Http\Controllers;

use App\Models\MenuItemOrder;
use Illuminate\Http\Request;

class StaffMenuItemOrderController extends Controller
{
    public function accept(MenuItemOrder $order)
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Order must be NEW to accept.',
            ], 422);
        }

        $order->status = 'preparing';
        $order->save();

        return response()->json([
            'ok' => true,
            'status' => $order->status,
        ]);
    }

    public function ready(MenuItemOrder $order)
    {
        if ($order->status !== 'preparing') {
            return response()->json([
                'message' => 'Order must be PREPARING to mark ready.',
            ], 422);
        }

        $order->status = 'ready';
        $order->save();

        return response()->json([
            'ok' => true,
            'status' => $order->status,
        ]);
    }

    public function pickedUp(MenuItemOrder $order)
    {
        if ($order->status !== 'ready') {
            return response()->json([
                'message' => 'Order must be READY to mark picked up.',
            ], 422);
        }

        $order->status = 'completed';
        $order->save();

        return response()->json([
            'ok' => true,
            'status' => $order->status,
        ]);
    }
}

