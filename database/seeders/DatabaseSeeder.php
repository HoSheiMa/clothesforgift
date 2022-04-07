<?php

namespace Database\Seeders;

use App\Models\assets_colors;
use App\Models\assets_sizes;
use App\Models\Chat;
use App\Models\colors;
use App\Models\Item;
use App\Models\Message;
use App\Models\Order;
use App\Models\products;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "id" => "1",
                "name" => "admin",
                "role" => "admin",
                "phone" => "20123456789",
                "leader_id" => null,
                "blocked" => "false",
                "email" => "admin@admin.com",
                "email_verified_at" => null,
                "password" =>
                    '$2y$10$0YK7CnB0iw6A4RgCXvxeoOiBQxAXzpLGSxSWDhyvtphgyplr7hDCK',
                "remember_token" =>
                    "wfsTamIfZT0itiJSKSkGeNpBX0u878FlouhdEqSJKrG9K1Hx6osmKkMMuD3M",
                "created_at" => null,
                "updated_at" => null,
                "active_balance" => 0,
                "pending_balance" => 0,
            ],
            [
                "id" => "2",
                "name" => "Shipping1",
                "phone" => "20123456789",

                "role" => "Shippingcompany",
                "leader_id" => null,
                "blocked" => "false",
                "email" => "Shippingcompany1@Shippingcompany1.com",
                "email_verified_at" => null,
                "password" =>
                    '$2y$10$0YK7CnB0iw6A4RgCXvxeoOiBQxAXzpLGSxSWDhyvtphgyplr7hDCK',
                "remember_token" => null,
                "created_at" => null,
                "updated_at" => null,
            ],
        ];
        $assets_sizes = [
            [
                "id" => "1",
                "size" => "12",
                "created_at" => null,
                "updated_at" => null,
            ],
            [
                "id" => "2",
                "size" => "14",
                "created_at" => null,
                "updated_at" => null,
            ],
        ];
        $assets_colors = [
            [
                "id" => "1",
                "color" => "red",
                "color_code" => "red",
                "created_at" => null,
                "updated_at" => null,
            ],
        ];

        $Shippings = [
            [
                "id" => "1",
                "location" => "القاهرة",
                "admin" => "10",
                "support" => "10",
                "pagesCoordinator" => "10",
                "leader" => "10",
                "marketer" => "10",
                "seller" => "10",
                "created_at" => "2021-11-20 22:59:11",
                "updated_at" => "2021-11-20 22:59:11",
            ],
        ];
        $products = [
            [
                "id" => "1",
                "name" => "product 1",
                "created_by" => "SYSTEM",
                "status" => "awaiting approve",
                "price" => "50",
                "min_price" => "55",
                "max_price" => "100",
                "details" =>
                    "product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1product 1",
                "type" => "حريمي",
                "icon" =>
                    "/storage/images/oOMj1DDnoS1nU4uZvGQUBdQG7WdgI14TBjYHJyBD.jpg",
                "created_at" => "2021-11-20 22:46:26",
                "updated_at" => "2021-11-20 22:46:26",
            ],
        ];
        $colors = [
            [
                "id" => "1",
                "color" => "red",
                "size" => "12",
                "available" => "100",
                "products_id" => "1",
                "created_at" => null,
                "updated_at" => null,
            ],
        ];
        $items = [
            [
                "id" => "1",
                "color_id" => "1",
                "min_price" => "50",
                "needed_price" => "55",
                "benefits" => "5",
                "needed" => "10",
                "order_id" => "1",
                "created_at" => null,
                "updated_at" => null,
            ],
        ];
        $chats = [
            [
                "id" => "1",
                "from" => "1",
                "to" => "2",
                "last_sender" => "2",
                "order_id" => "1",
                "created_at" => null,
                "updated_at" => null,
            ],
        ];
        $messages = [
            [
                "id" => "1",
                "from" => "1",
                "name" => "default name",
                "message" => "msg 2",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "2",
                "from" => "2",
                "name" => "default name",
                "message" => "msg 1",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "3",
                "from" => "1",
                "name" => "default name",
                "message" => "msg 3",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "4",
                "from" => "2",
                "name" => "default name",
                "message" => "msg 4",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "5",
                "from" => "1",
                "name" => "default name",
                "message" => "msg 5",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "6",
                "from" => "2",
                "name" => "default name",
                "message" => "msg 6",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "7",
                "from" => "1",
                "name" => "default name",
                "message" => "msg 7",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "8",
                "from" => "2",
                "name" => "default name",
                "message" => "msg 8",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "9",
                "from" => "1",
                "name" => "default name",
                "message" => "msg 9",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
            [
                "id" => "10",
                "from" => "2",
                "name" => "default name",
                "message" => "msg 10",
                "chat_id" => "1",
                "created_at" => "2021-11-26 04:13:31",
                "updated_at" => null,
            ],
        ];
        foreach ($messages as $message) {
            Message::create($message);
        }
        foreach ($chats as $chat) {
            Chat::create($chat);
        }

        // \App\Models\User::factory(10)->create();
        foreach ($users as $user) {
            User::create($user);
        }
        foreach ($assets_sizes as $assets_size) {
            assets_sizes::create($assets_size);
        }
        foreach ($assets_colors as $assets_color) {
            assets_colors::create($assets_color);
        }
        foreach ($Shippings as $Shipping) {
            Shipping::create($Shipping);
        }
        foreach ($products as $product) {
            products::create($product);
        }
        foreach ($colors as $color) {
            colors::create($color);
        }

        Setting::create([
            "name" => "withdraw_limit",
            "value" => "100",
        ]);
    }
}