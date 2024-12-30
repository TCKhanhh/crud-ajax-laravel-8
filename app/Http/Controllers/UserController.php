<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function listUser()
    {
        $users = DB::table('users')->paginate(4);

        return view('User.listUser', ['users' => $users]);
    }


    public function searchUsers(Request $request)
    {
        $search = $request->get('search');
        $users = DB::table('users')
            ->where('name', 'like', '%' . $search . '%')
            ->paginate(4)
            ->appends(['search' => $search]);

        return response()->json([
            'users' => $users->items(),
            'pagination' => $users->links('pagination::bootstrap-4')->toHtml()
        ]);
    }


    public function viewUser($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }


    public function storeUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'required|in:0,1',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = DB::table('users')->find($userId);

            return response()->json([
                'success' => true,
                'message' => 'Người dùng được tạo thành công',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,


                'message' => 'Có lỗi xảy ra khi tạo người dùng',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng'
            ], 404);
        }

        DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công'
        ]);
    }


    public function deleteUser($id)
    {
        $user = DB::table('users')->where('id', $id)->delete();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa người dùng'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Người dùng đã được xóa thành công'
        ]);
    }
}