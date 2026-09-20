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
        // $this->seedUsers();
        // $this->seedSettings();
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
                'value' => 'ar',
            ],
            [
                'key' => 'appearance:theme',
                'value' => 'light',
            ],
            [
                'key' => 'appearance:color',
                'value' => 'Gray',
            ],
            [
                'key' => 'site:name',
                'value' => 'MyGyms',
            ],
            [
                'key' => 'logo:favicon',
                'value' => 'settings/01M1NQWYNFQVNHY6BNYAXMYPG1.png',
            ],
            [
                'key' => 'logo:light',
                'value' => 'settings/01M1NQWYNGNMDZ6J4EQTQCHC3M.png',
            ],
            [
                'key' => 'logo:dark',
                'value' => 'settings/01M1NQWYNGNMDZ6J4EQTQCHC3N.png',
            ],
            [
                'key' => 'contact:phone',
                'value' => '1110208574',
            ],
            [
                'key' => 'contact:email',
                'value' => 'ali3mohamed99@gmail.com',
            ],
            [
                'key' => 'contact:address',
                'value' => 'asd',
            ],
            [
                'key' => 'social:facebook',
                'value' => 'https://www.facebook.com/',
            ],
            [
                'key' => 'social:instagram',
                'value' => 'https://www.facebook.com/',
            ],
            [
                'key' => 'social:twitter',
                'value' => 'https://www.facebook.com/',
            ],
            [
                'key' => 'social:youtube',
                'value' => 'https://www.facebook.com/',
            ],
        ];

        foreach ($items as $item) {
            Models\Setting::create($item);
        }
    }


}
