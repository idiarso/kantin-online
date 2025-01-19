<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class DigitalCardController extends Controller
{
    public function generate()
    {
        $users = User::whereIn('role', ['student', 'teacher'])
            ->where('status', 'active')
            ->whereNull('card_number')
            ->paginate(10);

        return view('admin.cards.generate', compact('users'));
    }

    public function processGenerate(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        foreach ($request->users as $userId) {
            $user = User::find($userId);
            if (!$user->card_number) {
                $cardNumber = $this->generateUniqueCardNumber();
                $user->update([
                    'card_number' => $cardNumber,
                    'card_generated_at' => now()
                ]);
            }
        }

        return redirect()->route('admin.cards.generate')
            ->with('success', 'Digital cards generated successfully');
    }

    public function print()
    {
        $users = User::whereNotNull('card_number')
            ->where('status', 'active')
            ->orderBy('card_generated_at', 'desc')
            ->paginate(10);

        return view('admin.cards.print', compact('users'));
    }

    public function batch()
    {
        $roles = [
            'student' => User::where('role', 'student')->count(),
            'teacher' => User::where('role', 'teacher')->count()
        ];

        return view('admin.cards.batch', compact('roles'));
    }

    public function processBatch(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,teacher',
            'count' => 'required|integer|min:1|max:100'
        ]);

        $users = User::where('role', $request->role)
            ->where('status', 'active')
            ->whereNull('card_number')
            ->take($request->count)
            ->get();

        foreach ($users as $user) {
            $cardNumber = $this->generateUniqueCardNumber();
            $user->update([
                'card_number' => $cardNumber,
                'card_generated_at' => now()
            ]);
        }

        return redirect()->route('admin.cards.batch')
            ->with('success', count($users) . ' cards generated successfully');
    }

    protected function generateUniqueCardNumber()
    {
        do {
            $number = mt_rand(1000000000, 9999999999); // 10 digits
            $exists = User::where('card_number', $number)->exists();
        } while ($exists);

        return $number;
    }

    public function downloadCard(User $user)
    {
        if (!$user->card_number) {
            return back()->with('error', 'No digital card found for this user');
        }

        $qrCode = QrCode::size(200)->generate($user->card_number);
        $pdf = PDF::loadView('admin.cards.card-template', compact('user', 'qrCode'));

        return $pdf->download('digital-card-' . $user->card_number . '.pdf');
    }

    public function downloadBatch(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->users)
            ->whereNotNull('card_number')
            ->get();

        $pdf = PDF::loadView('admin.cards.batch-template', compact('users'));

        return $pdf->download('digital-cards-batch.pdf');
    }
} 