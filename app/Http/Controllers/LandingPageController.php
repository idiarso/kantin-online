<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function content()
    {
        $content = [
            'hero_title' => Setting::get('landing_hero_title', 'Welcome to Our School Canteen'),
            'hero_subtitle' => Setting::get('landing_hero_subtitle', 'Order your favorite meals online'),
            'hero_description' => Setting::get('landing_hero_description', 'Fresh, delicious, and convenient food service for students and staff'),
            'hero_image' => Setting::get('landing_hero_image'),
            'about_title' => Setting::get('landing_about_title', 'About Our Canteen'),
            'about_content' => Setting::get('landing_about_content', 'Our school canteen provides healthy and tasty meals...'),
            'features' => [
                [
                    'title' => Setting::get('landing_feature1_title', 'Online Ordering'),
                    'description' => Setting::get('landing_feature1_description', 'Order your meals easily through our online platform')
                ],
                [
                    'title' => Setting::get('landing_feature2_title', 'Healthy Options'),
                    'description' => Setting::get('landing_feature2_description', 'We provide nutritious and balanced meal options')
                ],
                [
                    'title' => Setting::get('landing_feature3_title', 'Fast Delivery'),
                    'description' => Setting::get('landing_feature3_description', 'Quick delivery service to your classroom or office')
                ]
            ]
        ];

        return view('admin.landing.content', compact('content'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'about_title' => 'required|string|max:255',
            'about_content' => 'required|string',
            'features' => 'required|array|size:3',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'required|string'
        ]);

        if ($request->hasFile('hero_image')) {
            $oldImage = Setting::get('landing_hero_image');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $imagePath = $request->file('hero_image')->store('landing', 'public');
            Setting::set('landing_hero_image', $imagePath);
        }

        Setting::set('landing_hero_title', $request->hero_title);
        Setting::set('landing_hero_subtitle', $request->hero_subtitle);
        Setting::set('landing_hero_description', $request->hero_description);
        Setting::set('landing_about_title', $request->about_title);
        Setting::set('landing_about_content', $request->about_content);

        foreach ($request->features as $index => $feature) {
            Setting::set('landing_feature' . ($index + 1) . '_title', $feature['title']);
            Setting::set('landing_feature' . ($index + 1) . '_description', $feature['description']);
        }

        return redirect()->route('admin.landing.content')
            ->with('success', 'Landing page content updated successfully');
    }

    public function banners()
    {
        $banners = Setting::get('landing_banners', []);
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

        $banners = [];
        $existingBanners = Setting::get('landing_banners', []);

        foreach ($request->banners as $index => $banner) {
            $bannerData = [
                'title' => $banner['title'],
                'description' => $banner['description'],
                'link' => $banner['link'] ?? null
            ];

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

        return redirect()->route('admin.landing.banners')
            ->with('success', 'Banners updated successfully');
    }

    public function deleteBanner($index)
    {
        $banners = Setting::get('landing_banners', []);

        if (isset($banners[$index])) {
            if (isset($banners[$index]['image'])) {
                Storage::disk('public')->delete($banners[$index]['image']);
            }
            unset($banners[$index]);
            $banners = array_values($banners);
            Setting::set('landing_banners', $banners);
        }

        return back()->with('success', 'Banner deleted successfully');
    }
} 