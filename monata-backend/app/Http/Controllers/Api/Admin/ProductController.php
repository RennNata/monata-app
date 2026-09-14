<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Exception;

class ProductController extends Controller
{
    public function index() {
        try {
            $products = Product::all();
            return response()->json([
                'status' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $products
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
            $product = Product::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $product
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
                'id_kategori' => 'required|exists:categories,id',
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|integer',
                'stok' => 'required|integer',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $product = new Product();
            $product->id_kategori = $request->id_kategori;
            $product->nama_produk = $request->nama_produk;
            $product->harga = $request->harga;
            $product->stok = $request->stok;

            if ($request->hasFile('foto')) {
                $imageName = time().'.'.$request->foto->extension();
                $request->foto->move(public_path('images'), $imageName);
                $product->foto = $imageName;
            }

            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product
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
            $product = Product::findOrFail($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'id_kategori' => 'required|exists:categories,id',
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|integer',
                'stok' => 'required|integer',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $product->id_kategori = $request->id_kategori;
            $product->nama_produk = $request->nama_produk;
            $product->harga = $request->harga;
            $product->stok = $request->stok;

            if ($request->hasFile('foto')) {
                // gapus foto lama jika ada
                if ($product->foto && file_exists(public_path('images/'.$product->foto))) {
                    unlink(public_path('images/'.$product->foto));
                }
                $imageName = time().'.'.$request->foto->extension();
                $request->foto->move(public_path('images'), $imageName);
                $product->foto = $imageName;
            }

            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
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
            $product = Product::findOrFail($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Delete image if exists
            if ($product->foto && file_exists(public_path('images/'.$product->foto))) {
                unlink(public_path('images/'.$product->foto));
            }

            $product->delete();

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
