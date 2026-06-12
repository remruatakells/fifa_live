<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Access</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f7f6;
            color: #18201c;
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
            background: linear-gradient(135deg, #eef4f1, #f9fbfa);
        }

        main {
            width: min(100%, 420px);
            border: 1px solid #dde5df;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 20px 55px rgba(24, 32, 28, 0.08);
            padding: 28px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: clamp(1.7rem, 7vw, 2.35rem);
            letter-spacing: 0;
        }

        p {
            margin: 0 0 22px;
            color: #59675f;
            line-height: 1.5;
        }

        label {
            display: grid;
            gap: 8px;
            margin-bottom: 14px;
            font-weight: 800;
        }

        input {
            width: 100%;
            min-height: 46px;
            border: 1px solid #ccd7d0;
            border-radius: 8px;
            padding: 0 12px;
            font: inherit;
        }

        input:focus {
            border-color: #16733a;
            box-shadow: 0 0 0 3px rgba(22, 115, 58, 0.12);
            outline: none;
        }

        input:focus-visible,
        button:focus-visible {
            outline: 4px solid #f6d365;
            outline-offset: 4px;
        }

        .error {
            margin: 6px 0 0;
            color: #b92818;
            font-size: 0.92rem;
            font-weight: 700;
        }

        button {
            width: 100%;
            min-height: 46px;
            border: 0;
            border-radius: 8px;
            background: #16733a;
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 900;
        }

        button:focus-visible {
            background: #21904d;
        }

        @media (min-width: 900px) {
            body {
                font-size: 18px;
            }

            main {
                width: min(100%, 500px);
            }

            input,
            button {
                min-height: 56px;
            }
        }
    </style>
</head>
<body>
    <main>
        <h1>Admin Access</h1>
        <p>Enter the API access token to manage live stream visitors.</p>

        <form method="POST" action="{{ route('admin.authenticate') }}">
            @csrf

            <label>
                API access token
                <input type="password" name="api_token" autocomplete="current-password" required autofocus>
                @error('api_token')
                    <span class="error">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit">Open Admin Panel</button>
        </form>
    </main>
    <script src="{{ asset('js/tv-remote.js') }}"></script>
</body>
</html>
