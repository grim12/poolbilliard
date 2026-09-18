<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\CommitteeMemberFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * One row in the "Výkonný výbor" list on /sportovni-svaz — mirrors macros/committee-list.njk's
 * `members` array. `photo` is optional (the macro falls back to a placeholder icon), same
 * "must work without the image" idea as ClubMember. `name`/`email` aren't translatable (proper
 * nouns/contact data), `role` is (genuine editorial text, e.g. "Rozvoj a podpora mládeže").
 */
class CommitteeMember extends Model
{
    /** @use HasFactory<CommitteeMemberFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['role'];

    protected $fillable = [
        'name',
        'role',
        'role_translations',
        'email',
        'photo',
        'sort_order',
    ];

    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->photo ? Storage::disk('public')->url($this->photo) : null);
    }
}
