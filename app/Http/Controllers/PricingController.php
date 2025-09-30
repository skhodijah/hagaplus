<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get()
            ->map(function($package) {
                // Mark the middle package as popular if there are 3 packages
                $package->is_popular = $package->id === 2; // Assuming 3 packages and middle one is ID 2
                return $package;
            });

        return view('pricing', compact('packages'));
    }
}
