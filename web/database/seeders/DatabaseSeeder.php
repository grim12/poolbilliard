<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Local dev admin login — updateOrCreate so it survives every `migrate:fresh --seed`
        // instead of having to be recreated by hand with `make:filament-user` each time.
        // Dev-only placeholder password, change it before this ever goes anywhere real.
        User::updateOrCreate(
            ['email' => 'ugrin@nittin.cz'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $this->call(PartnerSeeder::class);
        $this->call(FaqItemSeeder::class);
        $this->call(TournamentSeeder::class);
    }
}
