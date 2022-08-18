<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function checkRole()
    {
        $role = UserHasRoles::where('user_id',Auth::id())->first();
        return $role;
    }
    public function register($id = null, Request $request)
    {
        $message = [
            'name.required' => "Hãy nhập vào tên tài khoản",
            'name.unique' => "Tên tài khoản đã tồn tại",
            'name.min' => "Tên tài khoản ít nhất 3 kí tự",
            'email.required' => 'Hãy nhập tài khoản Email',
            'email.unique' => 'Email đã tồn tại',
            'email.email' => 'Email không đúng định dạng',
            'avatar.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
            'avatar.max' => 'File ảnh không được quá 2MB',
            'avatar.required' => 'Chưa chọn avatar',
            'password.required' => 'Hãy nhập mật khẩu tài khoản',
            'password.min' => 'Mật khẩu ít có 6 kí tự',
            'password.confirmed' => 'Mật khẩu tài khoản không khớp',
            'password_confirmation.required' => 'Hãy nhập lại mật khẩu tài khoản',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
            'phone_number.required' => 'Hãy nhập số điện thoại',
            'phone_number.min' => 'Số điện thoại có độ dài nhỏ nhất là 10 ký tự',
            'phone_number.max' => 'Số điện thoại có độ dài lớn nhất là 11 ký tự',
            'phone_number.regex' => 'Số điện thoại không đúng định dạng',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'min:3',
                    Rule::unique('users')->ignore($id),
                ],
                'phone_number' => [
                    'required',
                    'min:10',
                    'max:11',
                    'regex:/^(09|03|07|08|05)[0-9]{8,9}$/',
                    Rule::unique('users')->ignore($id),
                ],
                'email' => 'required|email|unique:users',
                'avatar' => 'required|mimes:jpg,bmp,png,jpeg|max:2048',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ],
            $message
        );

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }

        $model = new User();
        $model->password = Hash::make($request->password);
        $model->fill($request->all());
        if ($request->has('avatar')) {
            $model->avatar = $request->file('avatar')->storeAs(
                'uploads/users/',
                '-' . $request->avatar->getClientOriginalName()
            );
        }

        $model->save();
        return response()->json(['msg' => 'Registered Successfully']);
    }

    public function login(Request $request)
    {
        dd(User::get());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['msg' => 'Logout Successfull']);
    }
}
