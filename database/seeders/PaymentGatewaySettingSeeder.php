<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_gateway_settings = array(
                array(
                    "id" => 1,
                    "key" => "paypal_status",
                    "value" => "1",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 16:06:51",
                ),
                array(
                    "id" => 2,
                    "key" => "paypal_account_mode",
                    "value" => "sandbox",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 16:06:51",
                ),
                array(
                    "id" => 3,
                    "key" => "paypal_country",
                    "value" => "MY",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 16:06:51",
                ),
                array(
                    "id" => 4,
                    "key" => "paypal_currency",
                    "value" => "MYR",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 16:06:51",
                ),
                array(
                    "id" => 5,
                    "key" => "paypal_rate",
                    "value" => "1",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 16:06:51",
                ),
                array(
                    "id" => 6,
                    "key" => "paypal_api_key",
                    "value" => "AUpD3kZae5Bgw57GikY0ov8zJes0H6FnvZGbWCQ_yYse9nDde-RQwLefpQIEWD2-_6kM1KJrPy11ezye",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 17:20:41",
                ),
                array(
                    "id" => 7,
                    "key" => "paypal_secret_key",
                    "value" => "EFFUkaUoXxpjjyN4lWd_DZTqRI54S6T6TjNrOkILtih2N0ag-jGgbvQ3s5O4ZvU-4mqzpmdVAM8JTIQA",
                    "created_at" => "2024-05-17 16:06:51",
                    "updated_at" => "2024-05-17 17:20:41",
                ),
                array(
                    "id" => 8,
                    "key" => "paypal_logo",
                    "value" => "/uploads/media_664784e072704.png",
                    "created_at" => "2024-05-17 16:25:04",
                    "updated_at" => "2024-05-17 16:25:04",
                ),
            );

        \DB::table('payment_gateway_settings')->insert($payment_gateway_settings);
    }
}
