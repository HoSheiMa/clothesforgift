<?php

use App\Http\Controllers\AssetsColorsController;
use App\Http\Controllers\AssetsSizesController;
use App\Http\Controllers\BonesController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\export;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotifController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StatusActionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Whatsapp;
use App\Http\Controllers\WithdrawController;
use App\Models\Chat;
use App\Models\Item;
use App\Models\Notif;
use App\Models\Order;
use App\Models\products;
use App\Models\User;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Translation\MessageCatalogue;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return redirect("/login");
});

Route::get("/test", function () {
    return view("invoice-report", [
        "orders" => Order::all(),
    ]);
});
Route::get("/r/{url}", function ($url) {
    return redirect("/" . $url);
});
Route::get("/invoice", [OrderController::class, "invoice"]);

Route::middleware(["auth"])->group(function () {
    Route::get("/products", function () {
        return view("products");
    })->name("products");
    Route::get("/getUser", function () {
        $user = Auth::user();
        return [
            "role" => $user->role,
            "name" => $user->name,
            "blocked" => $user->blocked,
            "leader_id" => $user->leader_id,
            "leader_ratio" => $user->leader_ratio,
            "id" => $user->id,
        ];
    });
    Route::get("/product/{product}", [
        ProductsController::class,
        "product_view",
    ])->name("product-view");
    // api
    Route::get("/api/products/approved", [
        ProductsController::class,
        "all_products_approved",
    ]);
    Route::get("/api/products", [ProductsController::class, "all_products"]);
    Route::get("/api/orders", [OrderController::class, "orders"]);

    Route::get("/add-product", [
        ProductsController::class,
        "show_add_product",
    ])->name("add-product-show");
    Route::post("/add-product", [
        ProductsController::class,
        "add_product",
    ])->name("add-product");

    Route::get("/product/{product}/edit", [
        ProductsController::class,
        "edit_product_show",
    ])->name("edit-product-show");
    Route::post("/product/{product}/edit", [
        ProductsController::class,
        "edit_product",
    ])->name("edit-product");
    Route::get("/delete/{images}", [ImagesController::class, "destroy"])->name(
        "delete-image"
    );
    Route::get("/product/{product}/delete", [
        ProductsController::class,
        "delete_product",
    ])
        ->middleware("isAdmin")
        ->name("delete-product");
    Route::get("/product/{product}/status/{status}", [
        ProductsController::class,
        "update_status_product",
    ])
        ->middleware("isAdmin")
        ->name("update_status_product");
    Route::get("/cart", [ProductsController::class, "show_cart"])->name(
        "show_cart"
    );
    Route::get("/show-products", [
        ProductsController::class,
        "show_products",
    ])->name("show-products");
    Route::post("/checkout", [OrderController::class, "checkout"])->name(
        "checkout"
    );

    Route::get("/orders", [OrderController::class, "show"])->name("orders");
    Route::get("/wholesale/{product}", [
        OrderController::class,
        "wholesale",
    ])->name("wholesale");

    // Shipping
    Route::get("/Shipping", [ShippingController::class, "show"]);
    Route::post("/Shipping", [ShippingController::class, "create"]);
    Route::post("/Shipping/{Shipping}/delete", [
        ShippingController::class,
        "destroy",
    ]);
    // end Shipping
    // colors
    Route::get("/colors", [AssetsColorsController::class, "show"]);
    Route::post("/colors", [AssetsColorsController::class, "create"]);
    Route::post("/colors/{color}/delete", [
        AssetsColorsController::class,
        "destroy",
    ]);
    // end colors
    // sizes
    Route::get("/sizes", [AssetsSizesController::class, "show"]);
    Route::post("/sizes", [AssetsSizesController::class, "create"]);
    Route::post("/sizes/{size}/delete", [
        AssetsSizesController::class,
        "destroy",
    ]);
    // end sizes
    Route::get("/ShippingCompanies/get", [
        OrderController::class,
        "companies",
    ])->middleware("isAdmin");
    Route::get("/order/Shipping", [
        OrderController::class,
        "add_ShippingCompany",
    ])->middleware("isAdmin");

    Route::get("order/{orders}/status/{status}", [
        OrderController::class,
        "update_status",
    ]);
    Route::post("/unlock/{order}", [OrderController::class, "unlock"]);

    Route::post("order/{orders}/Shipping/status/{status}", [
        OrderController::class,
        "update_Shipping_status",
    ]);
    Route::get("order/{order}/edit", [OrderController::class, "edit"])->name(
        "order-edit"
    );

    Route::get("order/{order}/delete", [
        OrderController::class,
        "delete",
    ])->middleware("isAdmin");
    Route::post("/item/{item}/delete", [ItemController::class, "destroy"]);
    Route::post("/item/{item}/update", [ItemController::class, "update"]);
    Route::post("order/{order}/update", [OrderController::class, "update"]);
    Route::get("/getColorsByItemId/{item}", [
        ColorsController::class,
        "getColorsByItemId",
    ]);
    Route::get("chats", [ChatController::class, "show"]);
    Route::get("chat/{id}", [ChatController::class, "index"]);
    Route::post("chat/{chat}/message/create", [
        MessageController::class,
        "create",
    ]);
    Route::post("chat/create", [ChatController::class, "create"]);
    Route::post("message/{message}/delete", [
        MessageController::class,
        "destroy",
    ]);
    Route::post("chat/{chat}/delete", [ChatController::class, "destroy"]);
    Route::get("/dashboard", function () {
        return view("dashboard", [
            "notifs" => Notif::all(),
        ]);
    })->name("dashboard");

    // users
    Route::get("/user/{user}/edit", [UserController::class, "edit"]);
    Route::post("/user/{user}/update", [UserController::class, "update"]);
    Route::get("/user/{user}/delete", [UserController::class, "delete"]);
    Route::get("/users/get", [UserController::class, "getAll"]);
    Route::get("/users", [UserController::class, "index"]);
    Route::get("/add-user", [UserController::class, "addUser"]);
    Route::post("/add/user", [UserController::class, "addUserRequest"]);

    // notif
    Route::get("/notif", [NotifController::class, "index"])->middleware(
        "isAdmin"
    );
    Route::get("/notif/get", [NotifController::class, "show"])->middleware(
        "isAdmin"
    );
    Route::get("/notif/{notif}/status/{status}", [
        NotifController::class,
        "update",
    ])->middleware("isAdmin");
    Route::post("/add/notif", [NotifController::class, "create"])->middleware(
        "isAdmin"
    );
    Route::get("/notif/{notif}/delete", [
        NotifController::class,
        "destroy",
    ])->middleware("isAdmin");

    //withdraws
    Route::get("/withdraw", [WithdrawController::class, "index"]);
    Route::get("/withdraw/setting", [WithdrawController::class, "showSetting"]);
    Route::post("/withdraw/setting/update", [
        WithdrawController::class,
        "updateWithDrawSetting",
    ]);
    Route::get("/withdraws/get", [WithdrawController::class, "getAll"]);
    Route::get("/add-withdraw", [WithdrawController::class, "addWithdraw"]);
    Route::post("/add/withdraw", [
        WithdrawController::class,
        "addWithdrawRequest",
    ]);
    Route::post("/withdraw/{withdraw}/delete", [
        WithdrawController::class,
        "destroy",
    ]);
    Route::get("withdraw/{withdraw}/status/{status}", [
        WithdrawController::class,
        "update",
    ]);
    // bonus
    Route::get("/bones", [BonesController::class, "index"]);
    Route::post("/bones", [BonesController::class, "create"])->middleware(
        "isAdmin"
    );
    Route::get("/bones/{bones}/delete", [
        BonesController::class,
        "destroy",
    ])->middleware("isAdmin");
    // whatsapp
    Route::post("/whatsapp/{order}", [Whatsapp::class, "send"]);
    // status action
    Route::get("/status_action", [
        StatusActionController::class,
        "index",
    ])->middleware("isAdmin");
    Route::post("/status_action_update/{statusAction}", [
        StatusActionController::class,
        "update",
    ])->middleware("isAdmin");
    // export
    Route::get("/export", [export::class, "show"])->middleware("isAdmin");
    Route::post("/export", [
        export::class,
        "advance_export_orders",
    ])->middleware("isAdmin");

    // profile
    Route::get("profile/{user}", function ($user) {
        $user = User::find($user);
        if (!$user) {
            return abort(404);
        }
        return View("profile", [
            "user" => $user,
        ]);
    });
});

Auth::routes();

Route::get("/home", [
    App\Http\Controllers\HomeController::class,
    "index",
])->name("home");

Route::get("/blocked", function () {
    return View("blocked");
});