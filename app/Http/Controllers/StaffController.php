<?php

namespace App\Http\Controllers;

use App\Models\MenuItemOrder;
use App\Models\PickupLocation;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $locations = PickupLocation::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.staff.index', [
            'locations' => $locations,
        ]);
    }

    public function command(PickupLocation $pickupLocation)
    {
        abort_unless($pickupLocation->is_active, 404);

        $columns = $this->loadColumns($pickupLocation);

        return view('pages.staff.command', [
            'pickupLocation' => $pickupLocation,
            'pendingOrders' => $columns['pending'],
            'preparingOrders' => $columns['preparing'],
            'readyOrders' => $columns['ready'],
            'pendingIds' => $columns['pending']->pluck('id')->values(),
        ]);
    }

    public function poll(PickupLocation $pickupLocation)
    {
        abort_unless($pickupLocation->is_active, 404);

        $columns = $this->loadColumns($pickupLocation);

        return response()->json([
            'columns' => [
                'pending' => view('pages.staff.partials.orders_column', [
                    'orders' => $columns['pending'],
                    'status' => 'pending',
                ])->render(),
                'preparing' => view('pages.staff.partials.orders_column', [
                    'orders' => $columns['preparing'],
                    'status' => 'preparing',
                ])->render(),
                'ready' => view('pages.staff.partials.orders_column', [
                    'orders' => $columns['ready'],
                    'status' => 'ready',
                ])->render(),
            ],
            'counts' => [
                'pending' => $columns['pending']->count(),
                'preparing' => $columns['preparing']->count(),
                'ready' => $columns['ready']->count(),
            ],
            'pending_ids' => $columns['pending']->pluck('id')->values(),
        ]);
    }

    private function loadColumns(PickupLocation $pickupLocation): array
    {
        $orders = MenuItemOrder::query()
            ->where('pickup_location_id', $pickupLocation->id)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->with(['items.addons'])
            ->get()
            ->sortBy(fn (MenuItemOrder $order) => $order->pickupAt());

        return [
            'pending' => $orders->where('status', 'pending')->values(),
            'preparing' => $orders->where('status', 'preparing')->values(),
            'ready' => $orders->where('status', 'ready')->values(),
        ];
    }
}

