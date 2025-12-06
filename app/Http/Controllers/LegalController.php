<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Controller for legal pages.
 *
 * Handles display of Terms of Service, Privacy Policy, and Cookie Policy pages.
 */
class LegalController extends Controller
{
    /**
     * Display the Terms of Service page.
     */
    public function terms(): View
    {
        return view('legal.terms', [
            'title' => 'Terms of Service',
            'metaDescription' => 'Los Santos Radio Terms of Service - Read our terms and conditions for using our online radio platform.',
        ]);
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy(): View
    {
        return view('legal.privacy', [
            'title' => 'Privacy Policy',
            'metaDescription' => 'Los Santos Radio Privacy Policy - Learn how we collect, use, and protect your personal information.',
        ]);
    }

    /**
     * Display the Cookie Policy page.
     */
    public function cookies(): View
    {
        return view('legal.cookies', [
            'title' => 'Cookie Policy',
            'metaDescription' => 'Los Santos Radio Cookie Policy - Information about how we use cookies on our website.',
        ]);
    }
}
