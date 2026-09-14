<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\ClubMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClubSeeder extends Seeder
{
    /**
     * Mirrors ui/src/_data/kluby.json (directory listing — name/full_name/address/city/region/
     * lat/lng only; `url` there is always the dead "/klub/" placeholder, not a real per-club
     * value, so it's not stored here). "SMÍCHOFF Billiard Club Praha" additionally gets the
     * richer fields from ui/src/klub.njk (the prototype's one hardcoded detail example) below.
     */
    private const CLUBS = [
        ['name' => 'BC Golden Horn Praha', 'full_name' => 'Billiard Club Golden Horn Praha, z.s.', 'address' => 'Bendova 1425/12, 16300 Praha 6', 'city' => 'Praha 6, Řepy', 'region' => 'Praha', 'lat' => 50.0836, 'lng' => 14.304],
        ['name' => 'Řipská BC', 'full_name' => 'Billiard Club Řipská, z.s.', 'address' => 'Řipská 20, 13000 Praha 3', 'city' => 'Praha 3', 'region' => 'Praha', 'lat' => 50.0863, 'lng' => 14.4625],
        ['name' => 'SK Billiard Centrum Praha', 'full_name' => 'Sportovní klub Billiard Centrum Praha, z.s.', 'address' => 'Broumarská 25, 19800 Praha 14', 'city' => 'Praha 14', 'region' => 'Praha', 'lat' => 50.105, 'lng' => 14.556],
        ['name' => 'SK Divočáci z Mníšku', 'full_name' => 'Sportovní klub Divočáci z Mníšku, z.s.', 'address' => 'Kurzova 2202/20, 15500 Praha 5', 'city' => 'Praha 5', 'region' => 'Praha', 'lat' => 50.061, 'lng' => 14.366],
        ['name' => 'SK Harlequin Praha', 'full_name' => 'Sportovní klub Harlequin Praha, z.s.', 'address' => 'Vršovická 68, 10100 Praha 10', 'city' => 'Praha 10', 'region' => 'Praha', 'lat' => 50.073, 'lng' => 14.479],
        ['name' => 'SK Petřiny', 'full_name' => 'Sportovní klub Petřiny, z.s.', 'address' => 'Ankarská 1896/2, 16300 Praha 6', 'city' => 'Praha 6', 'region' => 'Praha', 'lat' => 50.087, 'lng' => 14.348],
        ['name' => 'SMÍCHOFF Billiard Club Praha', 'full_name' => 'SMÍCHOFF Billiard Club Praha, z.s.', 'address' => 'Nový zlíchov 6, Praha 5, 150 00', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.07, 'lng' => 14.4],

        ['name' => 'BC Balabuška', 'full_name' => 'Billiard Club Balabuška, z.s.', 'address' => 'Průmyslová 458, 25129 Dobřejovice', 'city' => 'Dobřejovice', 'region' => 'Středočeský', 'lat' => 50.005, 'lng' => 14.554],
        ['name' => 'BC Kladno', 'full_name' => 'Billiard Club Kladno, z.s.', 'address' => 'T. G. Masaryka 108, 27201 Kladno', 'city' => 'Kladno', 'region' => 'Středočeský', 'lat' => 50.143, 'lng' => 14.103],
        ['name' => 'BC Mnichovo Hradiště', 'full_name' => 'Billiard Club Mnichovo Hradiště, z.s.', 'address' => 'Nádražní 315, 29501 Mnichovo Hradiště', 'city' => 'Mnichovo Hradiště', 'region' => 'Středočeský', 'lat' => 50.527, 'lng' => 14.97],
        ['name' => 'DUNS Benátky n.J.', 'full_name' => 'DUNS Benátky nad Jizerou, z.s.', 'address' => 'Náměstí 17. listopadu 124, 29471 Benátky nad Jizerou', 'city' => 'Benátky nad Jizerou', 'region' => 'Středočeský', 'lat' => 50.296, 'lng' => 14.827],

        ['name' => '1. BC Děčín z. s.', 'full_name' => '1. Billiard Club Děčín, z.s.', 'address' => 'Riegrova 90, 40501 Děčín', 'city' => 'Děčín', 'region' => 'Ústecký', 'lat' => 50.7811, 'lng' => 14.215],
        ['name' => '1. KK Chomutov', 'full_name' => '1. Kulečníkový klub Chomutov, z.s.', 'address' => 'Husova 302, 43001 Chomutov', 'city' => 'Chomutov', 'region' => 'Ústecký', 'lat' => 50.4602, 'lng' => 13.4177],
        ['name' => 'BC Louny', 'full_name' => 'Billiard Club Louny, z.s.', 'address' => 'Rybalkova 67, 44001 Louny', 'city' => 'Louny', 'region' => 'Ústecký', 'lat' => 50.354, 'lng' => 13.797],
        ['name' => 'BC Rybárna', 'full_name' => 'Billiard Club Rybárna, z.s.', 'address' => 'Alej 17. listopadu 1256, 41301 Roudnice nad Labem', 'city' => 'Roudnice nad Labem', 'region' => 'Ústecký', 'lat' => 50.426, 'lng' => 14.263],

        ['name' => 'DELTA Billiard', 'full_name' => 'DELTA Billiard, z.s.', 'address' => 'Podlesí 45, 66442 Popůvky', 'city' => 'Popůvky', 'region' => 'Jihomoravský', 'lat' => 49.189, 'lng' => 16.493],
        ['name' => 'SC Rozmarýn Brno', 'full_name' => 'Sportovní centrum Rozmarýn Brno, z.s.', 'address' => 'Vídeňská 264, 61900 Brno', 'city' => 'Brno', 'region' => 'Jihomoravský', 'lat' => 49.1951, 'lng' => 16.6068],
        ['name' => 'TJ Sokol Hodonín', 'full_name' => 'Tělovýchovná jednota Sokol Hodonín, z.s.', 'address' => 'Bratislavská 5, 69501 Hodonín', 'city' => 'Hodonín', 'region' => 'Jihomoravský', 'lat' => 48.8496, 'lng' => 17.1319],

        ['name' => 'Billiard Stars', 'full_name' => 'Billiard Stars, z.s.', 'address' => 'Skořická 320, 33401 Přeštice', 'city' => 'Přeštice', 'region' => 'Plzeňský', 'lat' => 49.557, 'lng' => 13.335],
        ['name' => 'BK Bio-Systém Plzeň', 'full_name' => 'Billiard klub Bio-Systém Plzeň, z.s.', 'address' => 'Slovanská 100, 32600 Plzeň', 'city' => 'Plzeň', 'region' => 'Plzeňský', 'lat' => 49.7384, 'lng' => 13.3736],
        ['name' => 'SB Plzeň', 'full_name' => 'Sportovní billiard Plzeň, z.s.', 'address' => 'Nádražní 88, 33041 Vejprnice', 'city' => 'Vejprnice', 'region' => 'Plzeňský', 'lat' => 49.762, 'lng' => 13.296],

        ['name' => 'BC Casablanca Tábor', 'full_name' => 'Billiard Club Casablanca Tábor, z.s.', 'address' => 'Budějovická 508, 39001 Tábor', 'city' => 'Tábor', 'region' => 'Jihočeský', 'lat' => 49.4144, 'lng' => 14.6578],
        ['name' => 'BC Metropol České Budějovice', 'full_name' => 'Billiard Club Metropol České Budějovice, z.s.', 'address' => 'Lannova třída 16, 37001 České Budějovice', 'city' => 'České Budějovice', 'region' => 'Jihočeský', 'lat' => 48.9745, 'lng' => 14.4744],

        ['name' => 'Billiard Ostrov', 'full_name' => 'Billiard Ostrov, z.s.', 'address' => 'Jáchymovská 44, 36301 Ostrov', 'city' => 'Ostrov', 'region' => 'Karlovarský', 'lat' => 50.304, 'lng' => 12.943],

        ['name' => 'BC Přichovice', 'full_name' => 'Billiard Club Přichovice, z.s.', 'address' => 'Přichovice 112, 51232 Přichovice', 'city' => 'Přichovice', 'region' => 'Liberecký', 'lat' => 50.627, 'lng' => 15.23],

        ['name' => 'BC Ostrava', 'full_name' => 'Billiard Club Ostrava, z.s.', 'address' => 'Nádražní 30, 70200 Ostrava', 'city' => 'Ostrava', 'region' => 'Moravskoslezský', 'lat' => 49.8209, 'lng' => 18.2625],

        ['name' => 'MPC Pardubice', 'full_name' => 'MPC Pardubice, z.s.', 'address' => 'Palackého třída 220, 53002 Pardubice', 'city' => 'Pardubice', 'region' => 'Pardubický', 'lat' => 50.0343, 'lng' => 15.7812],

        ['name' => 'Kulečníkový klub Zlín', 'full_name' => 'Kulečníkový klub Zlín, z.s.', 'address' => 'Gahurova 5265, 76001 Zlín', 'city' => 'Zlín', 'region' => 'Zlínský', 'lat' => 49.2265, 'lng' => 17.6707],
    ];

    /**
     * "SMÍCHOFF Billiard Club Praha" is the one club ui/'s prototype fleshes out with a full
     * detail page (ui/src/klub.njk) — about text, members, ambassador, recruitment notice.
     * Member photos and the hero image are committed under database/seeders/assets/ (see
     * PartnerSeeder for why: storage/app/public/ itself is gitignored).
     */
    private const SMICHOFF_EXTRA = [
        'about_text' => '<p>Sportovní klub, který sídlí na pražském Smíchově a je uzavřený pouze pro členy a jejich hosty. Zaměřujeme se na úzký kolektiv hráčů, kteří mají velký zájem o trénink a zlepšování se. Naší prioritou je kvalitní hráčské zázemí a vybavení srovnatelné s evropskou úrovní.</p>',
        'ambassador_name' => 'Jan Strádal',
        'recruitment_open' => false,
        'recruitment_text' => '<p>Nábor je v tuto chvíli uzavřený z důvodu tréninkových kapacit, ale v případě zájmu neváhejte kontaktovat klubového předsedu Honzu Strádala, který sdělí případné možnosti.</p>',
    ];

    private const SMICHOFF_MEMBERS = [
        ['name' => 'Jan Strádal', 'photo' => 'stradal.jpg'],
        ['name' => 'Jakub Stuna', 'photo' => 'stuna.jpg'],
        ['name' => 'Milan Ugrin', 'photo' => 'ugrin.jpg'],
        ['name' => 'Petr Havel', 'photo' => 'havel.jpg'],
        ['name' => 'Heath Williams', 'photo' => null],
        ['name' => 'Erik Kůs', 'photo' => null],
        ['name' => 'Josef Lomský', 'photo' => null],
        ['name' => 'Adam Žaba', 'photo' => null],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::CLUBS as $data) {
            $extra = $data['name'] === 'SMÍCHOFF Billiard Club Praha' ? self::SMICHOFF_EXTRA : [];

            if ($extra) {
                $heroPath = 'clubs/smichoff-hero.jpg';

                if (! $disk->exists($heroPath)) {
                    $disk->put($heroPath, file_get_contents(database_path('seeders/assets/clubs/smichoff-hero.jpg')));
                }

                $extra['image'] = $heroPath;
            }

            $club = Club::updateOrCreate(['name' => $data['name']], [...$data, ...$extra]);

            if ($data['name'] === 'SMÍCHOFF Billiard Club Praha') {
                foreach (self::SMICHOFF_MEMBERS as $index => $member) {
                    $photoPath = null;

                    if ($member['photo']) {
                        $photoPath = 'club-members/'.$member['photo'];

                        if (! $disk->exists($photoPath)) {
                            $disk->put($photoPath, file_get_contents(database_path('seeders/assets/club-members/'.$member['photo'])));
                        }
                    }

                    ClubMember::updateOrCreate(
                        ['club_id' => $club->id, 'name' => $member['name']],
                        ['photo' => $photoPath, 'sort_order' => $index]
                    );
                }
            }
        }
    }
}
