<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminSectionController extends Controller
{
    public function show(string $section): View|RedirectResponse
    {
        if ($section === 'sales') {
            return redirect()->route('admin.sales.orders');
        }

        $sections = config('admin.sections');
        abort_unless(isset($sections[$section]), 404);

        return view('admin.sections.placeholder', [
            'section' => $sections[$section],
        ]);
    }
}
