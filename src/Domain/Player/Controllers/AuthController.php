<?php

namespace Domain\Player\Controllers;

use Illuminate\Http\Request;
use Domain\Player\Models\Player;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Domain\Player\Requests\SigninRequest;

class AuthController extends Controller
{
    public function login(SigninRequest $request): RedirectResponse
    {
        $player = Player::where('account_id', $request->input('account_id'))->first();

        if (! $player) {
            $player = Player::create([
                'account_id' => $request->input('account_id'),
                'key_1' => $request->input('key_1'),
                'key_2' => $request->input('key_2'),
            ]);
        }

        Auth::login($player);

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
