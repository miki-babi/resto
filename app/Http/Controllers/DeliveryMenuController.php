<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeliveryRequest;
use App\Models\Delivery;
use App\Services\OrderService;

class DeliveryMenuController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function index()
    {
        $menuData = $this->orderService->getDeliveryMenuData();

        return view('pages.delivery-menu', array_merge($menuData, [
            'preorderSource' => 'menu',
            'preorderSubmitRoute' => 'delivery.submit',
            'isStandaloneDelivery' => true,
        ]));
    }

    public function submit(StoreDeliveryRequest $request)
    {
        $delivery = $this->orderService->createDelivery($request->validated());

        return redirect()->route('delivery.confirmation', $delivery);
    }

    public function showConfirmation(Delivery $delivery)
    {
        return view('pages.delivery-confirmation', [
            'delivery' => $delivery,
            'items' => $delivery->items->map(fn ($item) => [
                'name' => $item->menu_item_title,
                'quantity' => $item->quantity,
                'line_total_price' => $item->line_total_price,
            ]),
        ]);
    }
}
