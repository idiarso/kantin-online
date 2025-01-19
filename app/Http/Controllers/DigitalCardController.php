<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DigitalCardController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        
        if (!$user->qr_code) {
            $user->generateQrCode();
        }

        // Generate QR Code
        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate($user->qr_code);

        // Card data
        $cardData = [
            'name' => $user->name,
            'role' => ucfirst($user->role),
            'id' => $user->student_id ?? $user->employee_id,
            'qr_code' => $user->qr_code,
            'balance' => $user->balance
        ];

        return view('digital-card.show', compact('cardData', 'qrCode'));
    }

    public function download()
    {
        $user = auth()->user();
        
        if (!$user->qr_code) {
            $user->generateQrCode();
        }

        // Generate QR Code image
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($user->qr_code);

        // Save QR Code temporarily
        $path = 'qrcodes/' . $user->id . '.png';
        Storage::disk('public')->put($path, $qrCode);

        return response()->download(
            storage_path('app/public/' . $path),
            'kartu_digital_' . $user->name . '.png'
        )->deleteFileAfterSend();
    }
} 