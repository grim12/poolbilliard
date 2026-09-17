<?php

namespace Database\Seeders;

use App\Enums\HernaStatus;
use App\Models\Herna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class HernaSeeder extends Seeder
{
    /**
     * Mirrors ui/src/_data/herny.json (directory listing — name/address/city/region/lat/lng
     * only; `url` there is always the placeholder "#", not a real per-venue value, so it's not
     * stored here). "Billiard Club Harlequin Praha" (the first entry) additionally gets the
     * richer fields from ui/src/herna.njk (the prototype's one hardcoded "Demo herna" detail
     * example, not itself one of the 22 listing entries) — see self::HARLEQUIN_EXTRA below.
     */
    private const HERNY = [
        ['name' => 'Billiard Club Harlequin Praha', 'address' => 'Oblouková 819/35', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.073, 'lng' => 14.479],
        ['name' => 'Billiard Club Řipská', 'address' => 'Řipská 831/15', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.0863, 'lng' => 14.4625],
        ['name' => 'Billiard Rajská Zahrada', 'address' => 'Ciglerova 1090/32', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.105, 'lng' => 14.556],
        ['name' => 'Lucky Ball Billiard Club', 'address' => 'Mukařovského 1985/5', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.061, 'lng' => 14.366],
        ['name' => 'LEVELS Prague', 'address' => 'Národní 36/40', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.081, 'lng' => 14.414],
        ['name' => 'Garage Billiard & Bowling Smíchov', 'address' => 'Nádražní 279/1', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.07, 'lng' => 14.4],
        ['name' => 'Billiard Club Balabuška Bohdalec', 'address' => 'Bohdalec 957/2', 'city' => 'Praha', 'region' => 'Praha', 'lat' => 50.058, 'lng' => 14.478],

        ['name' => 'Bowling Bar Admirál', 'address' => 'Pražská 1006/48', 'city' => 'Poděbrady', 'region' => 'Středočeský', 'lat' => 50.1415, 'lng' => 15.1186],
        ['name' => 'Cadillac Bowling & Billiard Club', 'address' => 'Masarykova 989', 'city' => 'Kolín', 'region' => 'Středočeský', 'lat' => 50.028, 'lng' => 15.2],
        ['name' => 'Valhalla Sport Bar', 'address' => 'Havlíčkova 1245', 'city' => 'Mladá Boleslav', 'region' => 'Středočeský', 'lat' => 50.4143, 'lng' => 14.9028],
        ['name' => 'Bowling Ludmila', 'address' => 'Pražská 2639/2', 'city' => 'Mělník', 'region' => 'Středočeský', 'lat' => 50.3511, 'lng' => 14.4738],
        ['name' => 'Stone Bowling Bar Benešov', 'address' => 'Jana Nohy 1441', 'city' => 'Benešov', 'region' => 'Středočeský', 'lat' => 49.7845, 'lng' => 14.6879],

        ['name' => 'Billiard Club Plzeň', 'address' => 'Pobřežní 8', 'city' => 'Plzeň', 'region' => 'Plzeňský', 'lat' => 49.7384, 'lng' => 13.3736],
        ['name' => 'Bowling Bar Klatovy', 'address' => 'Plánická 626', 'city' => 'Klatovy', 'region' => 'Plzeňský', 'lat' => 49.3958, 'lng' => 13.2948],

        ['name' => 'Billiard Club Casablanca Tábor z.s.', 'address' => 'Šafaříkova 1791', 'city' => 'Tábor', 'region' => 'Jihočeský', 'lat' => 49.4144, 'lng' => 14.6578],
        ['name' => 'Kulečníková herna Hexpool', 'address' => 'Písecká 20', 'city' => 'České Budějovice', 'region' => 'Jihočeský', 'lat' => 48.9745, 'lng' => 14.4744],

        ['name' => 'Billiard Liberec', 'address' => 'Sokolovská 1133/67', 'city' => 'Liberec', 'region' => 'Liberecký', 'lat' => 50.7663, 'lng' => 15.0543],
        ['name' => 'Bowling F1 Česká Lípa', 'address' => 'Hrnčířská 761', 'city' => 'Česká Lípa', 'region' => 'Liberecký', 'lat' => 50.685, 'lng' => 14.5378],

        ['name' => 'Billiard Club Chomutov', 'address' => 'Střední 5122', 'city' => 'Chomutov', 'region' => 'Ústecký', 'lat' => 50.4602, 'lng' => 13.4177],

        ['name' => 'Billiard Club Hradec Králové', 'address' => 'Milady Horákové 273', 'city' => 'Hradec Králové', 'region' => 'Královéhradecký', 'lat' => 50.2092, 'lng' => 15.8328],

        ['name' => 'Billiard Club Yellow Fish', 'address' => 'Biskupská 1', 'city' => 'Brno', 'region' => 'Jihomoravský', 'lat' => 49.1951, 'lng' => 16.6068],
        ['name' => 'U 3 kouli', 'address' => 'Údolní 40', 'city' => 'Brno', 'region' => 'Jihomoravský', 'lat' => 49.1936, 'lng' => 16.6113],
        ['name' => 'Sborovna', 'address' => 'Gorkého 41', 'city' => 'Brno', 'region' => 'Jihomoravský', 'lat' => 49.2058, 'lng' => 16.6081],

        ['name' => 'Billiard Club Ostrava', 'address' => 'Nádražní 30', 'city' => 'Ostrava', 'region' => 'Moravskoslezský', 'lat' => 49.8209, 'lng' => 18.2625],
    ];

    private const HARLEQUIN_EXTRA = [
        'about_text' => '<p>Kulečníková herna v centru Prahy se šesti profesionálními stoly a příjemnou atmosférou pro rekreační i soutěžní hráče. Nabízíme pronájem stolů, prodej vybavení i drobné občerstvení — vhodné pro trénink, přátelské zápasy i firemní akce.</p>',
        'about_text_en' => '<p>A billiards venue in the center of Prague with six professional tables and a welcoming atmosphere for recreational and competitive players alike. We offer table rentals, equipment sales, and light refreshments — suitable for training, friendly matches, and corporate events.</p>',
        'phone' => '+420 123 456 789',
        'email' => 'info@demoherna.cz',
        'sports' => ['Poolbilliard', 'Karambol', 'Snooker'],
        'hours' => [
            ['day' => 'Pondělí', 'text' => '14:00–24:00'],
            ['day' => 'Úterý', 'text' => '14:00–24:00'],
            ['day' => 'Středa', 'text' => '14:00–24:00'],
            ['day' => 'Čtvrtek', 'text' => '14:00–24:00'],
            ['day' => 'Pátek', 'text' => '14:00–02:00'],
            ['day' => 'Sobota', 'text' => '16:00–02:00'],
            ['day' => 'Neděle', 'text' => 'Zavřeno'],
        ],
    ];

    private const HARLEQUIN_GALLERY = ['gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg', 'gallery-4.jpg', 'gallery-5.jpg'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::HERNY as $data) {
            $extra = $data['name'] === 'Billiard Club Harlequin Praha' ? self::HARLEQUIN_EXTRA : [];

            if ($extra) {
                $extra['about_text'] = ['cs' => $extra['about_text'], 'en' => $extra['about_text_en']];
                unset($extra['about_text_en']);

                $extra['gallery'] = collect(self::HARLEQUIN_GALLERY)->map(function (string $file) use ($disk) {
                    $path = 'herny/'.$file;

                    if (! $disk->exists($path)) {
                        $disk->put($path, file_get_contents(database_path('seeders/assets/herny/'.$file)));
                    }

                    return $path;
                })->all();
            }

            Herna::updateOrCreate(
                ['name' => $data['name']],
                [...$data, ...$extra, 'status' => HernaStatus::Approved]
            );
        }
    }
}
