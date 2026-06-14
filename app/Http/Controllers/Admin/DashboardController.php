<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MitraFilterService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly MitraFilterService $filterService
    ) {}

    /**
     * Halaman dashboard admin (setelah login).
     */
    public function index(): View
    {
        $stats          = $this->filterService->statistics();
        $monthlyGrowth  = $this->filterService->monthlyGrowth();
        $kategoriPerWil = $this->filterService->kategoriPerWilayah();

        return view('admin.dashboard', compact('stats', 'monthlyGrowth', 'kategoriPerWil'));
    }
}
