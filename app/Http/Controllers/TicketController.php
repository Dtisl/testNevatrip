<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use App\Http\Requests\AddTicketRequest;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    private TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function reserveAndConfirm(AddTicketRequest $request): JsonResponse
    {
        try {
            $barcode = $this->ticketService->reserveAndConfirm($request->validated());
            return response()->json([
                'message' => 'Reservation and confirmation successful',
                'barcode' => $barcode
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
