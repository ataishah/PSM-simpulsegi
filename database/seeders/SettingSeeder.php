<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = array(
            array(
                "id" => 1,
                "key" => "pusher_app_id",
                "value" => "1806602",
                "created_at" => "2024-05-22 06:51:29",
                "updated_at" => "2024-05-22 06:51:29",
            ),
            array(
                "id" => 2,
                "key" => "pusher_key",
                "value" => "bda3c5d373f15571415a",
                "created_at" => "2024-05-22 06:51:29",
                "updated_at" => "2024-05-22 06:51:29",
            ),
            array(
                "id" => 3,
                "key" => "pusher_secret",
                "value" => "3e8457e58511ad0cb7cd",
                "created_at" => "2024-05-22 06:51:29",
                "updated_at" => "2024-05-22 06:51:29",
            ),
            array(
                "id" => 4,
                "key" => "pusher_cluster",
                "value" => "ap1",
                "created_at" => "2024-05-22 06:51:29",
                "updated_at" => "2024-05-22 06:51:29",
            ),
            array(
                "id" => 5,
                "key" => "site_name",
                "value" => "simpulsegi",
                "created_at" => "2024-05-30 17:04:26",
                "updated_at" => "2024-05-30 17:04:26",
            ),
            array(
                "id" => 6,
                "key" => "site_default_currency",
                "value" => "MYR",
                "created_at" => "2024-05-30 17:04:26",
                "updated_at" => "2024-05-30 17:04:26",
            ),
            array(
                "id" => 7,
                "key" => "site_currency_icon",
                "value" => "RM",
                "created_at" => "2024-05-30 17:04:26",
                "updated_at" => "2024-05-30 17:04:26",
            ),
            array(
                "id" => 8,
                "key" => "site_currency_icon_position",
                "value" => "left",
                "created_at" => "2024-05-30 17:04:26",
                "updated_at" => "2024-05-30 17:04:26",
            ),
            array(
                "id" => 9,
                "key" => "logo",
                "value" => "/uploads/media_6658c961c760c.png",
                "created_at" => "2024-05-30 17:42:00",
                "updated_at" => "2024-05-30 18:45:53",
            ),
            array(
                "id" => 10,
                "key" => "footer_logo",
                "value" => "/uploads/media_6658ccf9ad3e1.png",
                "created_at" => "2024-05-30 17:42:00",
                "updated_at" => "2024-05-30 19:01:13",
            ),
            array(
                "id" => 11,
                "key" => "breadcrumb",
                "value" => "/uploads/media_6658cba07fe7d.png",
                "created_at" => "2024-05-30 17:42:00",
                "updated_at" => "2024-05-30 18:55:28",
            ),
            array(
                "id" => 12,
                "key" => "site_email",
                "value" => "service@simpulsegi.com",
                "created_at" => "2024-05-30 18:24:24",
                "updated_at" => "2024-05-30 18:24:24",
            ),
            array(
                "id" => 13,
                "key" => "site_phone",
                "value" => "01123345678",
                "created_at" => "2024-05-30 18:24:24",
                "updated_at" => "2024-05-30 18:24:24",
            ),
            array(
                "id" => 14,
                "key" => "favicon",
                "value" => "/uploads/media_6658cd0eba9f3.png",
                "created_at" => "2024-05-30 18:50:31",
                "updated_at" => "2024-05-30 19:01:34",
            ),
        );


        \DB::table('settings')->insert($settings);
    }
}
