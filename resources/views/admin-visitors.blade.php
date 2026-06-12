<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitors</title>
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
            margin: 0;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px clamp(16px, 5vw, 48px);
            border-bottom: 1px solid #dde5df;
            background: #ffffff;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.4rem, 4vw, 2rem);
            letter-spacing: 0;
        }

        main {
            width: min(1200px, 100%);
            margin: 0 auto;
            padding: 24px clamp(12px, 4vw, 32px);
        }

        .status {
            margin-bottom: 16px;
            border: 1px solid #b6dec1;
            border-radius: 8px;
            background: #e8f7ec;
            color: #174d28;
            padding: 12px 14px;
            font-weight: 700;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #dde5df;
            border-radius: 8px;
            background: #fff;
        }

        table {
            width: 100%;
            min-width: 1040px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e7ece8;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #eef3f0;
            color: #33443b;
            font-size: 0.88rem;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            border-radius: 999px;
            padding: 0 10px;
            font-size: 0.88rem;
            font-weight: 800;
        }

        .badge.allowed {
            background: #e8f7ec;
            color: #196333;
        }

        .badge.blocked {
            background: #ffe9e5;
            color: #9b2f1d;
        }

        form {
            display: flex;
            gap: 8px;
            margin: 0;
        }

        input {
            min-height: 38px;
            width: 170px;
            border: 1px solid #ccd7d0;
            border-radius: 8px;
            padding: 0 10px;
            font: inherit;
        }

        button {
            min-height: 38px;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
            padding: 0 12px;
            white-space: nowrap;
        }

        .block {
            background: #c83422;
            color: #fff;
        }

        .unblock {
            background: #16733a;
            color: #fff;
        }

        nav {
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Live Stream Visitors</h1>
        <div style="display: flex; align-items: center; gap: 14px;">
            <strong>{{ $visitors->total() }} records</strong>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>API Key</th>
                        <th>Status</th>
                        <th>Last Seen</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor->name }}</td>
                            <td>{{ $visitor->mobile }}</td>
                            <td>{{ $visitor->email ?: '-' }}</td>
                            <td>{{ $visitor->api_key_suffix ? '...' . $visitor->api_key_suffix : '-' }}</td>
                            <td>
                                <span class="badge {{ $visitor->is_blocked ? 'blocked' : 'allowed' }}">
                                    {{ $visitor->is_blocked ? 'Blocked' : 'Allowed' }}
                                </span>
                            </td>
                            <td>{{ optional($visitor->last_seen_at)->format('Y-m-d H:i') ?: '-' }}</td>
                            <td>{{ $visitor->block_reason ?: '-' }}</td>
                            <td>
                                @if ($visitor->is_blocked)
                                    <form method="POST" action="{{ route('admin.visitors.unblock', $visitor) }}">
                                        @csrf
                                        <button class="unblock" type="submit">Unblock Mobile</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.visitors.block', $visitor) }}">
                                        @csrf
                                        <input type="text" name="reason" placeholder="Reason">
                                        <button class="block" type="submit">Block Mobile</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No visitor records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            {{ $visitors->links() }}
        </nav>
    </main>
</body>
</html>
