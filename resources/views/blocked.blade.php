<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Blocked</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #111413;
            color: #f6f8f7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            display: grid;
            min-height: 100vh;
            margin: 0;
            place-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #1e2a25, #111413 58%, #241918);
        }

        .panel {
            width: min(100%, 460px);
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            padding: 28px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: clamp(1.8rem, 7vw, 2.6rem);
            letter-spacing: 0;
        }

        p {
            margin: 0 0 18px;
            color: #d2ddd7;
            line-height: 1.55;
        }

        form {
            margin: 0;
        }

        button {
            min-height: 44px;
            border: 0;
            border-radius: 8px;
            background: #f2f5f3;
            color: #111413;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
            padding: 0 16px;
        }
    </style>
</head>
<body>
    <main class="panel">
        <h1>Access Blocked</h1>
        <p>{{ $visitor->name }}, this mobile number is blocked from watching this live stream.</p>

        @if ($visitor->block_reason)
            <p>Reason: {{ $visitor->block_reason }}</p>
        @endif

        <form method="POST" action="{{ route('visitor.reset') }}">
            @csrf
            <button type="submit">Use Different Details</button>
        </form>
    </main>
</body>
</html>
