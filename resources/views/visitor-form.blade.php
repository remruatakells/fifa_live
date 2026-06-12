<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIFA Live</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #101513;
            color: #f4f7f5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(120deg, rgba(25, 82, 55, 0.88), rgba(16, 21, 19, 0.82)),
                url("https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?auto=format&fit=crop&w=1800&q=80") center / cover;
        }

        main {
            display: grid;
            min-height: 100vh;
            place-items: center;
            padding: 32px 16px;
        }

        .panel {
            width: min(100%, 440px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 8px;
            background: rgba(12, 17, 15, 0.88);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.35);
            padding: 28px;
            backdrop-filter: blur(12px);
        }

        h1 {
            margin: 0 0 8px;
            font-size: clamp(2rem, 8vw, 3.25rem);
            line-height: 1;
            letter-spacing: 0;
        }

        p {
            margin: 0 0 24px;
            color: #c8d4ce;
            line-height: 1.55;
        }

        label {
            display: grid;
            gap: 8px;
            margin: 16px 0;
            color: #eaf0ed;
            font-weight: 700;
        }

        input {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font: inherit;
            padding: 13px 14px;
            outline: none;
        }

        input:focus {
            border-color: #58d68d;
            box-shadow: 0 0 0 3px rgba(88, 214, 141, 0.18);
        }

        .error {
            margin-top: 6px;
            color: #ffb4a8;
            font-size: 0.9rem;
            font-weight: 600;
        }

        button {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 8px;
            background: #37d67a;
            color: #082015;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
            margin-top: 10px;
        }

        button:hover {
            background: #62e798;
        }

        .note {
            margin-top: 16px;
            margin-bottom: 0;
            font-size: 0.92rem;
            color: #aebbb5;
        }
    </style>
</head>
<body>
    <main>
        <section class="panel" aria-labelledby="page-title">
            <h1 id="page-title">FIFA Live</h1>
            <p>Enter your API key and details to watch the live stream.</p>

            <form method="POST" action="{{ route('visitor.register') }}">
                @csrf

                <label>
                    API key
                    <input type="password" name="api_key" autocomplete="off" required autofocus>
                    @error('api_key')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </label>

                <label>
                    Name
                    <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </label>

                <label>
                    Mobile
                    <input type="tel" name="mobile" value="{{ old('mobile') }}" autocomplete="tel" required>
                    @error('mobile')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </label>

                <label>
                    Email <span style="font-weight: 500; color: #aebbb5;">optional</span>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="email">
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </label>

                <button type="submit">Watch Live</button>
            </form>

            <p class="note">Your API key is checked before access and only a hash is stored.</p>
        </section>
    </main>
</body>
</html>
