<?php

namespace App\Http\Requests;

use App\Enums\Region;
use App\Enums\Sport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Mirrors the required-vs-optional fields of ui/src/registrace-herny.njk's form (fields marked
 * "*" there are `required` here). `postal_code` from that mock isn't validated/stored — `Herna`
 * has no matching column (never added, nothing reads it), same "dropped, no real destination"
 * reasoning as RuleCard's unused `iconVariant="balls"`.
 */
class StoreHernaRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Honeypot — a real visitor never sees or fills this field (hidden via CSS, see
            // resources/views/registrace-herny.blade.php); a bot that fills every input trips
            // it and gets rejected. Minimal spam defense, no external service/dependency.
            'company' => ['prohibited'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'region' => ['required', Rule::enum(Region::class)],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'sports' => ['required', 'array', 'min:1'],
            'sports.*' => [Rule::enum(Sport::class)],
            'hours' => ['nullable', 'array'],
            'hours.*' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }
}
