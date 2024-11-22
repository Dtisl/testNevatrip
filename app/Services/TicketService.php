<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;


class TicketService
{
    public function reserveAndConfirm(array $data): string
    {
        $barcode = null;
        $retriesLeft = 5;

        DB::transaction(function () use ($data, &$barcode, &$retriesLeft) {
            while ($retriesLeft > 0) {
                try {
                    $barcode = $this->createReservation($data);
                    break;
                } catch (Exception $e) {
                    if ($e->getMessage() === 'barcode already exists') {
                        $retriesLeft--;
                        continue;
                    }
                    throw $e;
                }
            }

            if (!$barcode) {
                throw new Exception('Failed to create reservation after multiple retries');
            }

            $this->confirmReservation($barcode);

            $totalPrice = array_sum(array_map(function ($ticket) {
                return $ticket['price'];
            }, $data['tickets']));

            $order = Order::create([
                'event_id' => $data['event_id'],
                'event_date' => $data['event_date'],
                'equal_price' => $totalPrice,
            ]);

            foreach ($data['tickets'] as $ticketData) {
                $ticketBarcode = $this->generateUniqueBarcode();
                $order->tickets()->create([
                    'ticket_type' => $ticketData['ticket_type'],
                    'barcode' => $ticketBarcode,
                    'price' => $ticketData['price'],
                ]);
            }
        });
        return $barcode;
    }

    private function createReservation(array $data): string
    {
        $barcode = $this->generateUniqueBarcode();

        $response = $this->mockBookApiResponse($data);

        if ($response['status'] === 'error' && $response['error'] === 'barcode already exists') {
            throw new Exception('barcode already exists');
        }

        if ($response['status'] === 'success') {
            return $barcode;
        }

        throw new Exception('Unexpected error during reservation');
    }

    private function generateUniqueBarcode(): string
    {
        $barcode = Str::uuid()->toString();

        while (Ticket::where('barcode', $barcode)->exists()) {
            $barcode = Str::uuid()->toString();
        }

        return $barcode;
    }

    private function confirmReservation(string $barcode): void
    {
        $response = $this->mockApproveApiResponse($barcode);

        if ($response['status'] === 'success') {
            return;
        }

        if (isset($response['error'])) {
            throw new Exception($response['error']);
        }

        throw new Exception('Unexpected error during confirmation');
    }

    private function mockBookApiResponse(array $data): array
    {
        return ['status' => 'success', 'message' => 'order successfully booked'];
    }

    private function mockApproveApiResponse(string $barcode): array
    {
        return ['status' => 'success', 'message' => 'order successfully approved'];
    }
}
