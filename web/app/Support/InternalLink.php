<?php

namespace App\Support;

use App\Models\Article;
use App\Models\Club;
use App\Models\Herna;
use App\Models\Notice;
use App\Models\RecurringTournament;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;

/**
 * Lets an admin point a button/card at either a static page or one specific record, instead of
 * typing a raw URL — the same choice then resolves to the correct cs/en URL on its own (see
 * resolve()), so nothing has to be re-picked when the EN site actually goes live. `link_route` /
 * `linkable` win over a manually typed `url` when set — see App\Filament\Support\InternalLinkFields,
 * the shared form fragment every "linkable" model's Filament form uses for this.
 */
class InternalLink
{
    /**
     * Static pages selectable from the "Interní stránka" dropdown — route base name => label.
     * Each one pairs 1:1 with an 'en.'-prefixed mirror (see routes/web.php).
     */
    public static function routeOptions(): array
    {
        return [
            'home' => 'Domů',
            'kluby' => 'Kluby',
            'herny' => 'Herny',
            'kalendar' => 'Kalendář',
            'souteze' => 'Soutěže',
            'jak-zacit' => 'Jak začít',
            'sportovni-svaz' => 'Sportovní svaz',
            'novinky' => 'Novinky',
            'faq' => 'FAQ',
            'pravidla' => 'Pravidla',
            'partneri' => 'Partneři',
            'registrace-herny' => 'Registrace herny',
            'zpravodajstvi.vykonny-vybor' => 'Zprávy výkonného výboru',
        ];
    }

    /**
     * Linkable model types selectable from the "Konkrétní záznam" dropdown — model class =>
     * label. Each one must also appear in self::SHOW_ROUTES and self::LABEL_FIELD below.
     */
    public static function linkableTypes(): array
    {
        return [
            Club::class => 'Klub',
            Herna::class => 'Herna',
            Article::class => 'Článek',
            Notice::class => 'Zpráva výkonného výboru',
            Tournament::class => 'Turnaj',
            RecurringTournament::class => 'Pravidelný turnaj',
        ];
    }

    /**
     * The named "show" route for each linkable type — {model:slug_cs}/{model:slug_en} bound, so
     * route($name, $record) already picks the right slug column for whichever locale is active
     * (see routes/web.php's docblock).
     */
    private const SHOW_ROUTES = [
        Club::class => 'klub.show',
        Herna::class => 'herna.show',
        Article::class => 'novinky.show',
        Notice::class => 'zpravodajstvi.vykonny-vybor.show',
        Tournament::class => 'turnaj.show',
        RecurringTournament::class => 'pravidelny-turnaj.show',
    ];

    /**
     * Which attribute identifies a record in the "Konkrétní záznam" picker's option list —
     * Club/Herna's `name` isn't translatable (proper nouns), the rest use their (translatable)
     * `title`.
     */
    private const LABEL_FIELD = [
        Club::class => 'name',
        Herna::class => 'name',
        Article::class => 'title',
        Notice::class => 'title',
        Tournament::class => 'title',
        RecurringTournament::class => 'title',
    ];

    /**
     * @return array<int|string, string> record id => display label, for the given linkable type
     */
    public static function recordOptions(string $modelClass): array
    {
        $field = self::LABEL_FIELD[$modelClass] ?? null;

        if (! $field || ! is_a($modelClass, Model::class, true)) {
            return [];
        }

        return $modelClass::query()->get()->mapWithKeys(fn (Model $record) => [$record->getKey() => $record->{$field}])->all();
    }

    /**
     * $route/$linkable (an admin's "Interní stránka"/"Konkrétní záznam" pick) win over a
     * manually typed $url — see this class's docblock for why.
     */
    public static function resolve(?string $route, ?Model $linkable, ?string $url): ?string
    {
        $isEn = app()->getLocale() === 'en';

        if ($linkable) {
            $routeName = self::SHOW_ROUTES[$linkable::class] ?? null;

            if ($routeName) {
                return route(($isEn ? 'en.' : '').$routeName, $linkable);
            }
        }

        if ($route) {
            return route(($isEn ? 'en.' : '').$route);
        }

        return $url;
    }

    /**
     * Same as resolve(), for a plain array shape (e.g. one Banner::$buttons entry) instead of an
     * Eloquent model's own link_route/linkable_type/linkable_id columns.
     */
    public static function resolveFromArray(array $link): ?string
    {
        $linkable = null;

        if (! empty($link['linkable_type']) && ! empty($link['linkable_id'])) {
            $linkable = ($link['linkable_type'])::find($link['linkable_id']);
        }

        return self::resolve($link['link_route'] ?? null, $linkable, $link['url'] ?? null);
    }
}
