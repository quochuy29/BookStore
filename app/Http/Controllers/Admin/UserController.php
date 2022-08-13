<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\RoleHasService;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $hasRole;

    public function __construct(RoleHasService $hasRole)
    {
        $this->hasRole = $hasRole;
    }

    public function index()
    {
        return User::get();
    }

    public function store(Request $request)
    {
        $model = new User();

        $message = [
            'name.required' => "Tên người dùng không được trống",
            'phone_number.required' => "Số điện thoại không được trống",
            'phone_number.unique' => "Số điện thoại đã tồn tại",
            'avatar.required' => "Ảnh đại diện của người dùng không được trống",
            'avatar.mimes' => 'File ảnh không đúng định dạng (jpg, bmp, png, jpeg)',
            'avatar.max' => 'File ảnh không được quá 2MB',
            'email.required' => "Email của người dùng không được trống",
            'email.email' => "Email không đúng định dạng",
            'email.unique' => "Email đã tồn tại",
            'password.required' => "Mật khẩu không được trống",
            'password.min' => "Mật khẩu ít nhất 6 kí tự",
            'password.max' =>"Mật khẩu nhiều nhất 30 kí tự",
            'password.confirmed' =>"Mật khẩu không khớp",
            'password_confirmation.required'=>"Hãy nhập lại mật khẩu"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required'
                ],
                'phone_number' => [
                    'required',
                    Rule::unique('users')
                ],
                'permission.*' => [
                    'required'
                ],
                'avatar' => 'required|mimes:jpg,bmp,png,jpeg|max:2048',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users'),
                ],
                'role_id' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if (is_numeric($value)) {
                            $role = Roles::where('id', $value)->first();
                            if (!$role) {
                                return $fail('Không tìm thấy ID của vài trò này');
                            }
                        } else {
                            return $fail('Không tìm thấy ID chứa ký tự là chữ');
                        }
                    },
                ],
                'password' => [
                    'required',
                    'min:6',
                    'max:30',
                    'confirmed'
                ],
                'password_confirmation' => [
                    'required'
                ]
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->errors(), 'tu' => $request->all()], 422);
        }

        $model->fill($request->all());
        if ($request->has('avatar')) {
            $model->avatar = $request->file('avatar')->storeAs(
                'uploads/users/',
                '-' . $request->avatar->getClientOriginalName()
            );
        }
        $model->save();
        if ($request->role_id) {
            $this->hasRole->addRoleUser($model->id, $request->role_id);
        }

        return response()->json(['status' => "Tạo người dùng thành công"]);
    }

    public function updateForm($id)
    {
        $model = User::with('roles')->find($id);
        return $model;
    }
}
