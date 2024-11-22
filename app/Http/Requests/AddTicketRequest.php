<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|integer',
            'event_date' => 'required|date',
            'tickets' => 'required|array',
            'tickets.*.ticket_type' => 'required|string|max:255',
            'tickets.*.price' => 'required|numeric|min:0',
        ];
    }
}
