<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserCart;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Cart;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $action = 'login';
        return view('frontend.pages.login', ['action' => $action]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        if (Auth::user()->role == 'user') {
            $userId = Auth::user()->id;
            $savedCart = UserCart::where("user_id", $userId)->first();
            if ($savedCart) {
                $items = unserialize($savedCart->cart_data);
                foreach ($items as $key => $item) {
                    Cart::session($userId)->add($item);
                }
            }
            return redirect()->intended(RouteServiceProvider::HOME);
        } else if (Auth::user()->role = 'vendor')
            return redirect()->route("vendor.profile");
        else return redirect()->route("admin.profile");
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::user()->id;
        $cart = Cart::session($userId)->getContent()->toArray();
        UserCart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => serialize($cart)]
        );

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
