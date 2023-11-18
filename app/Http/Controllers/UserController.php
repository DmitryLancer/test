<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

            User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'number' => $request->number,
                'image' => $imageName,
            ]);

            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            return response()->json([
                'message' => 'Пользователь успешно добавлен!'
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Не получилось добавить пользователя!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Пользователь не найден!'
            ],404);
        }

        return response()->json([
            'user' => $user
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserStoreRequest $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'Пользователь не найден!'
                ],404);
            }


            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->number = $request->number;

            if ($request->image) {
                $storage = Storage::disk('public');

                if ($storage->exists($user->image))
                    $storage->delete($user->image);

                $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
                $user->image = $imageName;

                $storage->put($imageName, file_get_contents($request->image));
            }

            $user->save();

            return response()->json([
                'message' => 'Пользователь успешно обновлен!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Что то пошло не так!'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user){
            return response()->json([
                'message' => 'Пользователь не найден'
            ],404);
        }

        $storage = Storage::disk('public');

        if ($storage->exists($user->image))
            $storage->delete($user->image);

        $user->delete();

        return response()->json([
            'message' => 'Пользователь успешно удален'
        ],200);
    }
}
