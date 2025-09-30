<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;



class ProfileController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');


        $exhibitedItems = $user->items()->latest()->get();


        $purchasedItems = $user->purchases()->with('item')->latest()->get();

        return view('profile.profile', [
            'user' => $user,
            'page' => $page,
            'exhibitedItems' => $exhibitedItems,
            'purchasedItems' => $purchasedItems,
        ]);
    }

    // プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image_path = $path;
        }

        $isFirstSetup = ! $user->is_profile_set;
        $user->is_profile_set = true;
        $user->save();

        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );
        if ($isFirstSetup) {
            return redirect()->route('items.index');
        }

        return redirect()->route('profile.index');
    }

    public function edit()
    {
        $user = Auth::user()->load('address');


        return view('profile.edit', compact('user'));
    }
}
