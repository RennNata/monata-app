<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return response()->json($admins);
    }

    // Nambah akun admin baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'nis_nip'  => 'required|unique:users,nis_nip',
                'password' => 'required|min:8',
        ]);

            $admin = User::create([
                'name'     => $request->name,
                'nis_nip'  => $request->nis_nip,
                'password' => Hash::make($request->password),
                'role'     => 'admin'
        ]);

            return response()->json([
                'status'  => true,
                'message' => 'Akun admin baru berhasil dibuat.',
                'data'    => $admin
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $admin = User::findOrFail($id);

            $request->validate([
                'name'     => 'sometimes|required|string|max:255',
                'nis_nip'  => 'sometimes|required|unique:users,nis_nip,' . $admin->id,
                'password' => 'sometimes|required|min:8',
            ]);

            if ($request->has('name')) {
                $admin->name = $request->name;
            }
            if ($request->has('nis_nip')) {
                $admin->nis_nip = $request->nis_nip;
            }
            if ($request->has('password')) {
                $admin->password = Hash::make($request->password);
            }

            $admin->save();

            return response()->json([
                'status'  => true,
                'message' => 'Akun admin berhasil diperbarui.',
                'data'    => $admin
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $admin = User::findOrFail($id);

            if ($admin->role !== 'admin') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Pengguna ini bukan admin.'
                ], 400);
            }

            $admin->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Akun admin berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
