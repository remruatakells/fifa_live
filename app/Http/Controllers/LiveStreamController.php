<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LiveStreamController extends Controller
{
    public function welcome(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('visitor_id')) {
            return redirect()->route('live.player');
        }

        return view('visitor-form');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_key' => ['required', 'string', 'max:190'],
            'name' => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'email' => ['nullable', 'email', 'max:190'],
        ]);

        $configuredApiKey = (string) config('stream.user_access_api_key');

        if ($configuredApiKey === '' || ! hash_equals($configuredApiKey, $validated['api_key'])) {
            return back()
                ->withErrors(['api_key' => 'Invalid API key.'])
                ->withInput($request->except('api_key'));
        }

        $blockedMatch = Visitor::where('mobile', $validated['mobile'])
            ->where('is_blocked', true)
            ->latest('blocked_at')
            ->first();

        $visitor = Visitor::create([
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'] ?? null,
            'api_key_hash' => hash('sha256', $validated['api_key']),
            'api_key_suffix' => substr($validated['api_key'], -4),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'is_blocked' => (bool) $blockedMatch,
            'blocked_at' => $blockedMatch?->blocked_at,
            'block_reason' => $blockedMatch?->block_reason,
            'last_seen_at' => now(),
        ]);

        $request->session()->put('visitor_id', $visitor->id);

        return redirect()->route('live.player');
    }

    public function player(Request $request): View|RedirectResponse
    {
        $visitor = Visitor::find($request->session()->get('visitor_id'));

        if (! $visitor) {
            $request->session()->forget('visitor_id');

            return redirect()->route('visitor.form');
        }

        if ($visitor->is_blocked) {
            return view('blocked', [
                'visitor' => $visitor,
            ]);
        }

        $visitor->forceFill(['last_seen_at' => now()])->save();

        return view('live-player', [
            'visitor' => $visitor,
            'playerUrl' => config('stream.castr_player_url'),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->session()->forget('visitor_id');

        return redirect()->route('visitor.form');
    }
}
