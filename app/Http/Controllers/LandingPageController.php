<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function content()
    {
        $content = [
            'title' => Setting::get('landing_title'),
            'description' => Setting::get('landing_description'),
            'opening_hours' => Setting::get('landing_opening_hours'),
            'contact_email' => Setting::get('landing_contact_email'),
            'contact_phone' => Setting::get('landing_contact_phone'),
            'address' => Setting::get('landing_address'),
        ];

        return view('admin.landing.content', compact('content'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'opening_hours' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            Setting::set('landing_title', $request->title);
            Setting::set('landing_description', $request->description);
            Setting::set('landing_opening_hours', $request->opening_hours);
            Setting::set('landing_contact_email', $request->contact_email);
            Setting::set('landing_contact_phone', $request->contact_phone);
            Setting::set('landing_address', $request->address);

            return redirect()->route('admin.landing.content')
                ->with('success', 'Content updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating landing content: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the content.');
        }
    }

    public function banners()
    {
        $banners = Setting::get('landing_banners', []);
        if (!is_array($banners)) {
            $banners = [];
            Setting::set('landing_banners', $banners);
        }
        return view('admin.landing.banners', compact('banners'));
    }

    public function updateBanners(Request $request)
    {
        $request->validate([
            'banners' => 'required|array|min:1',
            'banners.*.title' => 'required|string|max:255',
            'banners.*.description' => 'required|string',
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'banners.*.link' => 'nullable|url'
        ]);

        try {
            $banners = [];
            $existingBanners = Setting::get('landing_banners', []);
            if (!is_array($existingBanners)) {
                $existingBanners = [];
            }

            foreach ($request->banners as $index => $banner) {
                $bannerData = [
                    'title' => $banner['title'],
                    'description' => $banner['description'],
                    'link' => $banner['link'] ?? null
                ];

                // Handle image upload
                if (isset($banner['image']) && $banner['image']->isValid()) {
                    if (isset($existingBanners[$index]['image'])) {
                        Storage::disk('public')->delete($existingBanners[$index]['image']);
                    }
                    $bannerData['image'] = $banner['image']->store('banners', 'public');
                } elseif (isset($existingBanners[$index]['image'])) {
                    $bannerData['image'] = $existingBanners[$index]['image'];
                }

                $banners[] = $bannerData;
            }

            Setting::set('landing_banners', $banners);
            Setting::clearCache();

            return redirect()->route('admin.landing.banners')
                ->with('success', 'Banners updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating banners: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the banners.');
        }
    }

    public function deleteBanner($index)
    {
        try {
            $banners = Setting::get('landing_banners', []);
            if (!is_array($banners)) {
                $banners = [];
            }

            if (isset($banners[$index])) {
                if (isset($banners[$index]['image'])) {
                    Storage::disk('public')->delete($banners[$index]['image']);
                }
                unset($banners[$index]);
                $banners = array_values($banners);
                Setting::set('landing_banners', $banners);
                Setting::clearCache();
            }

            return back()->with('success', 'Banner deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting banner: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the banner.');
        }
    }

    public function announcements()
    {
        $announcements = Setting::get('landing_announcements', []);
        return view('admin.landing.announcements', compact('announcements'));
    }

    public function updateAnnouncements(Request $request)
    {
        $request->validate([
            'announcements' => 'required|array',
            'announcements.*.title' => 'required|string|max:255',
            'announcements.*.content' => 'required|string',
            'announcements.*.status' => 'required|in:active,inactive'
        ]);

        Setting::set('landing_announcements', $request->announcements);

        return redirect()->route('admin.landing.announcements')
            ->with('success', 'Announcements updated successfully');
    }

    public function deleteAnnouncement($index)
    {
        $announcements = Setting::get('landing_announcements', []);

        if (isset($announcements[$index])) {
            unset($announcements[$index]);
            $announcements = array_values($announcements);
            Setting::set('landing_announcements', $announcements);
        }

        return back()->with('success', 'Announcement deleted successfully');
    }
} 