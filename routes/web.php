<?php

use App\Models\CmsPage;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $contact = Setting::find('contact_us')?->value ?? [];
    $about = CmsPage::where('slug', 'about-us')->first();
    return view('welcome', compact('contact', 'about'));
})->name('home');

Route::get('/about', function () {
    $about = CmsPage::where('slug', 'about-us')->first();
    $contact = Setting::find('contact_us')?->value ?? [];
    return view('pages.about', compact('about', 'contact'));
})->name('about');

Route::get('/contact', function () {
    $contact = Setting::find('contact_us')?->value ?? [];
    return view('pages.contact', compact('contact'));
})->name('contact');

Route::get('/privacy-policy', function () {
    $page = CmsPage::where('slug', 'page-privacy-policy')->first();
    $global = Setting::find('global')?->value ?? [];
    $content = $page?->description ?? ($global['privacyPolicy'] ?? '');
    return view('pages.policy', ['title' => 'Privacy Policy', 'content' => $content]);
})->name('privacy');

Route::get('/terms-and-conditions', function () {
    $global = Setting::find('global')?->value ?? [];
    $content = $global['termsAndConditions'] ?? '';
    return view('pages.policy', ['title' => 'Terms & Conditions', 'content' => $content]);
})->name('terms');

Route::get('/page/{slug}', function ($slug) {
    $page = CmsPage::where('slug', $slug)->where('publish', true)->firstOrFail();
    return view('pages.policy', ['title' => $page->name, 'content' => $page->description]);
})->name('cms.page');
