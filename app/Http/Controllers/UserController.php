<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('users/Index', [
            'users' => User::all()->except(auth()->user()->id),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('users/Create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        User::create($validatedData);

        return to_route('users.index');
    }

    public function show(Request $request): Response
    {
        return Inertia::render('users/Show', [
            'user' => User::find($request->user),
        ]);
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('users/Edit', [
            'user' => User::find($request->user),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->update($validated);

        return back();
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->user);

        $user->delete();

        return back();
    }

    public function trashed(): Response
    {
        return Inertia::render('users/Trashed', [
            'users' => User::onlyTrashed()->get(),
        ]);
    }

    public function restore(Request $request): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($request->user);

        $user->restore();

        return back();
    }

    public function delete(Request $request): RedirectResponse
    {
        User::forceDestroy($request->user);

        return back();
    }
}
