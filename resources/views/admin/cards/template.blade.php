<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Digital Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .card {
            width: 85.6mm;
            height: 54mm;
            border: 1px solid #000;
            margin: 10px;
            padding: 10px;
            box-sizing: border-box;
            position: relative;
            page-break-inside: avoid;
            background: #fff;
        }
        .card-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .card-type {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }
        .card-content {
            display: flex;
            justify-content: space-between;
        }
        .user-info {
            flex: 1;
        }
        .user-name {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .user-details {
            font-size: 12px;
            color: #333;
            margin: 2px 0;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin-left: 10px;
        }
        .card-footer {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .card-number {
            font-family: monospace;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    @foreach($users as $user)
    <div class="card">
        <div class="card-header">
            <h1 class="school-name">{{ config('app.name') }}</h1>
            <div class="card-type">Digital Payment Card</div>
        </div>
        
        <div class="card-content">
            <div class="user-info">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-details">
                    {{ ucfirst($user->role) }}<br>
                    @if($user->role == 'student')
                        Class: {{ $user->class }}<br>
                        Student ID: {{ $user->student_id }}
                    @elseif($user->role == 'teacher')
                        Employee ID: {{ $user->employee_id }}
                    @endif
                </div>
            </div>
            <div class="qr-code">
                {!! QrCode::size(80)->generate($user->card_number) !!}
            </div>
        </div>
        
        <div class="card-footer">
            <div class="card-number">{{ $user->card_number }}</div>
            <div>Valid until: {{ now()->addYear()->format('M Y') }}</div>
        </div>
    </div>
    @endforeach
</body>
</html> 