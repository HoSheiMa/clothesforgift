<?php

namespace App\Http\Controllers;

use App\Models\User;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        if ($role === "admin" || $role === "support" || $role === "leader") {
            return View("users");
        }
    }
    public function getAll(Request $request)
    {
        $role = Auth::user()->role;

        if ($role === "admin" || $role === "support") {
            if ($request->filter == "pending") {
                return [
                    "data" => User::where("blocked", "true")->get(),
                ];
            }
            return [
                "data" => User::with("leader")->get(),
            ];
        } elseif ($role === "leader") {
            return [
                "data" => User::where("leader_id", Auth::user()->id)->get(),
            ];
        }
    }
    public function edit(Request $request, User $user)
    {
        $role = Auth::user()->role;

        if (
            $role === "admin" ||
            $role === "support" ||
            $user->leader_id == Auth::user()->id // under this leader
        ) {
            return View("userEdit", [
                "user" => $user,
            ]);
        } else {
            return View("userEdit", [
                "user" => Auth::user(),
            ]);
        }
    }
    public function delete(Request $request, User $user)
    {
        $role = Auth::user()->role;

        if ($role === "admin" || $role === "support") {
            $user->delete();
        }
    }
    public function update(Request $request, User $user)
    {
        $roles = [
            "admin",
            "support",
            "pagesCoordinator",
            "leader",
            "marketer",
            "seller",
            "Shippingcompany",
        ];
        $role = Auth::user()->role;

        if (
            $role === "admin" ||
            $role === "support" ||
            $user->leader_id == Auth::user()->id // under this leader
        ) {
            // update own profile or other profiles
            $user->update([
                "name" => $request->name ? $request->name : "",
                "phone" => $request->phone ? $request->phone : "",
                "role" =>
                    $request->role && in_array($request->role, $roles)
                        ? $request->role
                        : $user->role,
                "blocked" => $request->blocked ? $request->blocked : "",
                "password" => $request->password
                    ? Hash::make($request->password)
                    : $user->password,
            ]);
            return View("userEdit", [
                "user" => $user,
            ]);
        } else {
            // update his own profile
            $user = User::find(Auth::user()->id);
            $user->update([
                "phone" => $request->phone ? $request->phone : "",

                "name" => $request->name ? $request->name : "",
                "password" => $request->password
                    ? Hash::make($request->password)
                    : "",
            ]);
            return View("userEdit", [
                "user" => $user,
            ]);
        }
    }
    public function addUser(Request $request)
    {
        return View("addUser");
    }
    public function addUserRequest(Request $request)
    {
        $user = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "role" => ["required"],
            "phone" => ["required"],
            // "isleader" => ["string", "nullable"],
            "leader_ratio" => ["integer", "nullable"],
            "phone" => ["required"],
            "password" => ["required", "string", "min:8", "confirmed"],
        ]);

        if (
            (!empty($request->isleader) && $request->isleader == "on") ||
            Auth::user()->role == "leader"
        ) {
            $leader = Auth::user()->id;
            $user["leader_id"] = $leader;
        }

        $user["password"] = Hash::make($user["password"]);
        User::Create($user);
    }
}