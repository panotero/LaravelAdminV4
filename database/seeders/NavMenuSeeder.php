<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NavMenu;
use Illuminate\Support\Facades\DB;

class NavMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu_array = [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-home',
                'link' => '/page_dashboard',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '0',
                'menu_order' => '0'
            ],
            [
                'title' => 'User Management',
                'icon' => 'fas fa-users',
                'link' => '/page_users',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '0',
                'menu_order' => '6'
            ],
            [
                'title' => 'Developer Option',
                'icon' => 'fas fa-users',
                'link' => '#',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '0',
                'menu_order' => '8'
            ],
            [
                'title' => 'Mailer',
                'icon' => '',
                'link' => '/page_mailer',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '3',
                'menu_order' => '1'
            ],
            [
                'title' => 'Menus',
                'icon' => '',
                'link' => '/page_menus',
                'allowed_roles' => json_encode(['1']),
                'parent_menu' => '3',
                'menu_order' => '2'
            ],
        ];

        foreach ($menu_array as $menu) {

            NavMenu::updateOrCreate(
                [
                    'title' => $menu['title']
                ],
                [
                    'icon' => $menu['icon'],
                    'link' => $menu['link'],
                    'allowed_roles' => $menu['allowed_roles'],
                    'parent_menu' => $menu['parent_menu'],
                    'menu_order' => $menu['menu_order']
                ]
            );
        }
    }
}
