<?php

namespace App\Http\Requests\Api\Voucher;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class MrMessagingReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'status' => ['required', 'string'], // DELIVRD, EXPIRED, DELETED, UNDELIV, ACCEPTD, UNKNOWN, REJECTD
            'submitdate' => ['required', 'date'], // The time and date at which the short message was submitted, formatted as “YYYY-MM-DD HH:MM:SS”, e.g. “2010-01-01 14:18:00”
            'donedate' => ['required', 'date'], // The time and date at which the short message reached its final state, formatted as “YYYY-MM-DD HH:MM:SS”, e.g. “2010-01-01 14:18:00”
            'submitted' => ['required', 'string'], // Number of short messages originally submitted
            'delivered' => ['required', 'string'], // Number of short messages delivered
            'error' => ['required', 'string'], // Error code of the SMS delivery
            'text' => ['required', 'string'], // The first 20 characters or the short message
            'sender' => ['required', 'string'], // the receiver of the MT
            'receiver' => ['required', 'string'], // the sender of the MT
            'message' => ['required', 'string'], // Content in response
            'time' => ['required', 'string'], // Time the message was sent.
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        Log::warning(class_basename($this) . ' - Validation failed', ['request' => $this->request->all(), 'errors' => $this->errorBag]);
        parent::failedValidation($validator);
    }
}
