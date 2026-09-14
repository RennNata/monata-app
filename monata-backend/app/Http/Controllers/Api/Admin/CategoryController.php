<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Exception;


class CategoryController extends Controller
{
    
    public function index() {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil diambil',
                'data' => $categories
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id) {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil diambil',
                'data' => $category
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255',
            ]);

            $category = new Category();
            $category->nama_kategori = $request->nama_kategori;
            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id) {
        try {

            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'nama_kategori' => 'required|string|max:255',
            ]);

            $category->nama_kategori = $request->nama_kategori;
            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {
        try {
            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
