<?php

namespace Illuminate\Notifications\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Notifications\Messages\MrMessagingMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MrMessagingReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'originator' => ['required', 'string'],
            'recipient' => ['required', 'string'],
            'message' => ['required', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        Log::warning(class_basename($this) . ' - Validation failed', ['request' => request()?->all(), 'errors' => $validator->errors()]);
        parent::failedValidation($validator);
    }
}
