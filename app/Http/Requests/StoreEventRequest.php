<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use App\Enums\EventTypeEnum;
use App\Enums\EventStatusEnum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'type' => ['required', Rule::Enum(EventTypeEnum::class)],
            'meeting_url' => ['nullable', 'url', 'max:255'],
            'recording_url' => ['nullable', 'url', 'max:255'],
            'cover' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                File::image()
                    ->min(1)
                    ->max(1 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(128)->maxWidth(1080)->ratio(1 / 1)),
            ],
        ];

        // Menambahkan rule 'quota' ketika kegiatan terbatas
        if ($this->input('type') === EventTypeEnum::TERBATAS->value) {
            $rules['quota'] = ['required', 'integer', 'min:1'];
        }

        // 
        /** @var \App\Models\Event|null $event */
        $event = $this->route('event');

        // Cegah pengeditan start_date, type, dan quota jika kegiatan sudah dimulai
        if ($event?->has_started) {
            $rules = Arr::except($rules, ['start_date', 'type', 'quota']);
        }

        return $rules;
    }
}
