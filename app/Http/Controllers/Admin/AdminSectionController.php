<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminSectionController extends Controller
{
    public function show(string $section): View
    {
        $sections = config('admin.sections');
        abort_unless(isset($sections[$section]), 404);

        return view('admin.sections.placeholder', [
            'section' => $sections[$section],
        ]);
    }
}
