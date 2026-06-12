<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVisitorController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if ((bool) session()->get('admin_access_granted')) {
            return redirect()->route('admin.visitors');
        }

        return view('admin-login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_token' => ['required', 'string'],
        ]);

        $configuredToken = (string) config('stream.admin_token');

        if ($configuredToken === '' || ! hash_equals($configuredToken, $validated['api_token'])) {
            return back()
                ->withErrors(['api_token' => 'Invalid API access token.'])
                ->onlyInput();
        }

        $request->session()->regenerate();
        $request->session()->put('admin_access_granted', true);

        return redirect()->route('admin.visitors');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_access_granted');

        return redirect()->route('admin.login');
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (! $this->isAdmin($request)) {
            return redirect()->route('admin.login');
        }

        return view('admin-visitors', [
            'visitors' => Visitor::query()
                ->latest()
                ->paginate(25)
                ->withQueryString(),
        ]);
    }

    public function block(Request $request, Visitor $visitor): RedirectResponse
    {
        if (! $this->isAdmin($request)) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:190'],
        ]);

        Visitor::where('mobile', $visitor->mobile)->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'block_reason' => $validated['reason'] ?? 'Blocked by admin',
        ]);

        return redirect()
            ->route('admin.visitors')
            ->with('status', "Blocked {$visitor->mobile}");
    }

    public function unblock(Request $request, Visitor $visitor): RedirectResponse
    {
        if (! $this->isAdmin($request)) {
            return redirect()->route('admin.login');
        }

        Visitor::where('mobile', $visitor->mobile)->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'block_reason' => null,
        ]);

        return redirect()
            ->route('admin.visitors')
            ->with('status', "Unblocked {$visitor->mobile}");
    }

    private function isAdmin(Request $request): bool
    {
        return (bool) $request->session()->get('admin_access_granted');
    }
}
