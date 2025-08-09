<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('users/Index', [
            'users' => User::all()
        ]);
    }
    public function show(Request $request): Response
    {
        return Inertia::render('users/Show', [
            'user' => User::find($request->user)
        ]);
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
            'users' => User::onlyTrashed()->get()
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

    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = User::create([
            'prefixname' => $validatedData['prefixname'],
            'firstname' => $validatedData['firstname'],
            'middlename' => $validatedData['middlename'],
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
