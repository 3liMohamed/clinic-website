<?php

namespace Database\Seeders;

use App\Models;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedSettings();
    }

    private function seedUsers(): void
    {
        $items = [
            [
                'name' => 'Super Admin',
                'email' => 'super@admin.com',
                'phone' => '01110208574',
                'is_active' => true,
                'password' => bcrypt('super@admin.com'),
            ],
        ];
        foreach ($items as $item) {
            Models\User::create($item);
        }
    }

    private function seedSettings(): void
    {
        $items = [
            [
                'key' => 'appearance:language',
                'value' => 'en',
            ],
            [
                'key' => 'appearance:theme',
                'value' => 'light',
            ],
            [
                'key' => 'appearance:color',
                'value' => 'Sky',
            ],
            [
                'key' => 'site:name',
                'value' => 'SiteName',
            ],
            [
                'key' => 'logo:favicon',
                'value' => 'default.webp',
            ],
            [
                'key' => 'logo:light',
                'value' => 'default.webp',
            ],
            [
                'key' => 'logo:dark',
                'value' => 'default.webp',
            ],
            [
                'key' => 'contact:phone',
                'value' => '+201110208574',
            ],
            [
                'key' => 'contact:email',
                'value' => 'ali3mohamed99@gmail.com',
            ],
            [
                'key' => 'contact:address',
                'value' => 'address',
            ],
            [
                'key' => 'social:facebook',
                'value' => '#',
            ],
            [
                'key' => 'social:instagram',
                'value' => '#',
            ],
            [
                'key' => 'social:twitter',
                'value' => '#',
            ],
            [
                'key' => 'social:youtube',
                'value' => '#',
            ],
        ];

        foreach ($items as $item) {
            Models\Setting::create($item);
        }
    }


}
