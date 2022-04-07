<?php

namespace App\Http\Controllers;

use App\Models\assets_colors;
use App\Models\assets_sizes;
use App\Models\colors;
use App\Models\images;
use App\Models\products;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function show_cart()
    {
        $role = Auth::user()->role;

        return view("cart", [
            "role" => $role,
            "Shippings" => Shipping::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    public function show_add_product()
    {
        $role = Auth::user()->role;

        if ($role == "admin" || $role == "seller") {
            return view("add-product", [
                "colors" => assets_colors::all(),
                "sizes" => assets_sizes::all(),
            ]);
        }
    }
    public function delete_product(Request $request, products $product)
    {
        $product->delete();
    }
    public function update_status_product(
        Request $request,
        products $product,
        $status
    ) {
        $product["status"] = $status;
        $product->save();
        return $product;
    }
    public function edit_product_show(Request $request, products $product)
    {
        if (!Gate::allows("update-product", [$product])) {
            return abort(500);
        }
        $role = Auth::user()->role;

        if ($role == "admin" || $role == "seller") {
            $colors = $product->colors;
            $images = $product->images;
            return view("edit-products", [
                "colors" => assets_colors::all(),
                "sizes" => assets_sizes::all(),
                "product" => $product,
                "product_colors" => $colors,
                "images" => $images,
            ]);
        }
    }
    public function all_products_approved()
    {
        $role = Auth::user()->role;
        $products = products::where("status", "approved")->get();
        foreach ($products as $product_key => $product) {
            $colors = $product->colors;
            $available = 0;
            foreach ($colors as $key => $color) {
                $available += (int) $color->available;
            }
            $products[$product_key]["available"] = $available;
        }
        return [
            "data" => $products,
            "role" => $role,
        ];
    }
    public function all_products()
    {
        $role = Auth::user()->role;
        $products = [];
        if ($role == "admin") {
            $products = products::all();
        } elseif ($role == "seller") {
            $products = products::where(
                "created_by",
                (string) Auth::user()->id
            )->get();
        }
        foreach ($products as $product_key => $product) {
            $colors = $product->colors;
            $available = 0;
            foreach ($colors as $key => $color) {
                $available += (int) $color->available;
            }
            $products[$product_key]["available"] = $available;
        }
        return [
            "data" => $products,
            "role" => $role,
        ];
    }
    public function show_products(Request $request)
    {
        return view("show-products");
    }

    public function edit_product(Request $request, products $product)
    {
        if (!Gate::allows("update-product", [$product])) {
            return abort(500);
        }
        $request->validate([
            "name" => ["required"],
            "price" => ["required"],
            "min_price" => ["nullable"],
            "max_price" => ["nullable"],
            "details" => ["required"],
            "type" => ["required"],
            "items-colors" => ["required"],
            "items-sizes" => ["required"],
            "items-available" => ["required"],
        ]);

        // save images and icon to server
        if (!empty($request->file("icon"))) {
            $icon_path = $request->file("icon")->store("public/images");
            $icon_path = "/" . str_replace("public", "storage", $icon_path);
        } else {
            $icon_path = $product->icon; // use old one
        }
        $images_paths = [];
        if (!empty($request->file("images"))) {
            foreach ($request->file("images") as $key => $value) {
                $path = $value->store("public/images");
                $path = "/" . str_replace("public", "storage", $path);
                array_push($images_paths, $path);
            }
        }

        // create products
        $product->update([
            "name" => $request->name,
            "price" => $request->price,
            "icon" => $icon_path,
            "max_price" => $request->max_price,
            "min_price" => $request->min_price,
            "details" => $request->details,
            "type" => $request->type,
        ]);

        // add image to db
        // if (sizeof($images_paths) > 0) {
        //     images::where("products_id", $product->id)->each(function ($p) {
        //         $p->delete();
        //     });
        // }

        foreach ($images_paths as $key => $value) {
            images::create([
                "url" => $value,
                "products_id" => $product->id,
                "name" => "",
            ]);
        }

        // create products colors

        $items_colors = $request->{'items-colors'};
        $items_sizes = $request->{'items-sizes'};
        $items_available = $request->{'items-available'};

        colors::where("products_id", $product->id)->each(function ($p) {
            $p->delete();
        });

        foreach ($items_colors as $key => $value) {
            if (empty($value)) {
                continue;
            }
            colors::create([
                "color" => $value,
                "size" => $items_sizes[$key],
                "available" => $items_available[$key],
                "products_id" => $product->id,
            ]);
        }
        $request->session()->flash("done", " تمت العملية بنجاح");
        return redirect("/products");
    }
    public function product_view(Request $request, products $product)
    {
        $colors = $product->colors;
        $available = 0;
        foreach ($colors as $key => $color) {
            $available += (int) $color->available;
        }
        return view("products-view", [
            "product" => $product,
            "colors" => $product->colors,
            "images" => $product->images,
            "available" => $available,
            "role" => Auth::user()->role,
        ]);
    }
    public function add_product(Request $request)
    {
        $request->validate([
            "name" => ["required"],
            "price" => ["required"],
            "min_price" => ["nullable"],
            "max_price" => ["nullable"],
            "details" => ["required"],
            "type" => ["required"],
            "items-colors" => ["required"],
            "items-sizes" => ["required"],
            "items-available" => ["required"],
            "icon" => ["required"],
            "images" => ["required"],
        ]);

        // save images and icon to server
        $icon_path = $request->file("icon")->store("public/images");
        $icon_path = "/" . str_replace("public", "storage", $icon_path);
        $images_paths = [];
        foreach ($request->file("images") as $key => $value) {
            $path = $value->store("public/images");
            $path = "/" . str_replace("public", "storage", $path);
            array_push($images_paths, $path);
        }

        // create products
        $p = products::create([
            "name" => $request->name,
            "price" => $request->price,
            "icon" => $icon_path,
            "max_price" => $request->max_price,
            "min_price" => $request->min_price,
            "details" => $request->details,
            "created_by" =>
                Auth::user()->role == "seller" ? Auth::user()->id : "SYSTEM",
            "type" => $request->type,
        ]);

        // add image to db
        foreach ($images_paths as $key => $value) {
            images::create([
                "url" => $value,
                "products_id" => $p->id,
                "name" => "",
            ]);
        }

        // create products colors

        $items_colors = $request->{'items-colors'};
        $items_sizes = $request->{'items-sizes'};
        $items_available = $request->{'items-available'};

        foreach ($items_colors as $key => $value) {
            colors::create([
                "color" => $value,
                "size" => $items_sizes[$key],
                "available" => $items_available[$key],
                "products_id" => $p->id,
            ]);
        }
        $request->session()->flash("done", "لقد تم العمليه بنجاح");
        return redirect("/add-product");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(products $products)
    {
        //
    }
}