<?php

namespace Database\Seeders;

use App\Models\CommitteeMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CommitteeMemberSeeder extends Seeder
{
    /**
     * Mirrors the `committeeList()` call in ui/src/sportovni-svaz.njk. Photo files are
     * committed under database/seeders/assets/committee-members/ (mirrored from
     * ui/src/uploads/) and copied onto the "public" storage disk here, same reasoning as
     * PartnerSeeder.
     */
    private const MEMBERS = [
        ['name' => 'Robin Vladyka', 'role' => 'Prezident sekce', 'email' => 'robin.vladyka@cmbs.cz', 'photo' => 'vv-robin-vladyka.png'],
        ['name' => 'Tomáš Vančura', 'role' => 'Rozvoj a podpora mládeže', 'email' => 'tomas.vancura@poolbilliard.cz', 'photo' => 'vv-tomas-vancura.png'],
        ['name' => 'Milan Ugrin', 'role' => 'Organizace soutěží a propagace', 'email' => 'milan.ugrin@poolbilliard.cz', 'photo' => 'vv-milan-ugrin.png'],
        ['name' => 'Jan Strádal', 'role' => 'Komunikace, strategické plánování a fundraising', 'email' => 'jan.stradal@poolbilliard.cz', 'photo' => 'vv-jan-stradal.png'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assetsPath = database_path('seeders/assets/committee-members');
        $disk = Storage::disk('public');

        foreach (self::MEMBERS as $index => $member) {
            $storagePath = "committee-members/{$member['photo']}";

            if (! $disk->exists($storagePath)) {
                $disk->put($storagePath, file_get_contents($assetsPath.'/'.$member['photo']));
            }

            CommitteeMember::updateOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'role' => $member['role'],
                    'photo' => $storagePath,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
