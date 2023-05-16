<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $data->name = $request->name;
        if ($data->isDirty('email')) {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Admin::class],
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $data->email = $request->email;

        if ($request->file()) {
            $file = $request->file('file');
            @unlink(public_path('upload/admin_images/' . $data->images));
            $fileName = Auth::guard('admin')->user()->id . '_' . date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $fileName);
            $data['images'] = $fileName;
        }
        $data->save();
        $notification = array(
            'message' => 'Admin profile updated successfully',
            'alert-type' => 'success',
        );
        return redirect()->route('admin.profile')->with($notification);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::guard('admin')->user()->password)) {
                        $fail(__('The current password is incorrect.'));
                    }
                },
            ],
            'new_password' => 'required|min:8|confirmed',
        ]);
        $admin = Auth::guard('admin')->user();
        $admin->password = Hash::make($request->get('new_password'));
        $admin->save();
        $notification = array(
            'message' => 'Admin profile updated password successfully',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);
    }
}
