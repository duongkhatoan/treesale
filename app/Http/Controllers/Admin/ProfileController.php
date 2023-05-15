<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $currentUser = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('currentUser'));
    }
    public function edit()
    {
        $currentUser = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('currentUser'));
    }
    public function update(Request $request)
    {
        $data = Auth::guard('admin')->user();
        // dd(get_class($data));
        $data->name = $request->name;
        $data->email = $request->email;

        if ($request->file()) {
            $file = $request->file('file');
            $fileName = Auth::guard('admin')->user()->id . '_' . date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $fileName);
            $data['images'] = $fileName;
        }
        // dd($data['images']);
        $data->save();
        return redirect()->route('admin.profile');
    }
}
