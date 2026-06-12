<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIFA Live Stream</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #0f1412;
            color: #f7fbf8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(135deg, rgba(9, 35, 26, 0.96), rgba(17, 20, 24, 0.98)),
                #0f1412;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px clamp(16px, 5vw, 48px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand {
            display: grid;
            gap: 4px;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.35rem, 4vw, 2.15rem);
            line-height: 1.1;
            letter-spacing: 0;
        }

        .viewer {
            color: #b8c7c0;
            font-size: 0.95rem;
        }

        form {
            margin: 0;
        }

        button {
            min-height: 40px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #f7fbf8;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            padding: 0 14px;
        }

        button:hover {
            background: rgba(255, 255, 255, 0.14);
        }

        main {
            width: min(1180px, 100%);
            margin: 0 auto;
            padding: clamp(16px, 4vw, 36px);
        }

        .player-shell {
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            overflow: hidden;
            background: #050706;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.36);
        }

        iframe {
            display: block;
            width: 100%;
            aspect-ratio: 16 / 9;
            border: 0;
            background: #050706;
        }

        .status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            color: #b8c7c0;
            font-size: 0.95rem;
        }

        .live-dot {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #eaf7ef;
            font-weight: 800;
        }

        .live-dot::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #37d67a;
            box-shadow: 0 0 0 5px rgba(55, 214, 122, 0.16);
        }

        .message {
            margin-top: 18px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.07);
            padding: 16px;
            color: #d7e2dc;
            line-height: 1.55;
        }

        .message code {
            color: #b6f4cf;
            overflow-wrap: anywhere;
        }

        @media (max-width: 640px) {
            header {
                align-items: flex-start;
                flex-direction: column;
            }

            .status {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <h1>FIFA Live Stream</h1>
            <div class="viewer">Watching as {{ $visitor->name }} | {{ $visitor->mobile }}</div>
        </div>

        <form method="POST" action="{{ route('visitor.reset') }}">
            @csrf
            <button type="submit">Change Details</button>
        </form>
    </header>

    <main>
        <section class="player-shell" aria-label="Live video player">
            @if (filled($playerUrl))
                <iframe
                    src="{{ $playerUrl }}"
                    title="FIFA Live Stream Player"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen
                    referrerpolicy="strict-origin-when-cross-origin"
                ></iframe>
            @else
                <div class="message" style="margin: 0; border: 0; border-radius: 0;">
                    Set your Castr player URL in <code>CASTR_PLAYER_URL</code> inside <code>.env</code>, then run <code>php artisan config:clear</code>.
                </div>
            @endif
            <div class="status">
                <span class="live-dot">LIVE</span>
                <span>Castr player</span>
            </div>
        </section>
    </main>
</body>
</html>
