<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function index(Request $request) {
        try {
            $user = $request->user();
            if ($user->role === 'siswa') {
                // Jika user adalah siswa, ambil transaksi miliknya sendiri
                $transactions = Transaction::where('id_user', $user->id)->get();
            } else {
                // Jika user adalah admin atau super_admin, ambil semua transaksi
                $transactions = Transaction::all();
            }

            return response()->json([
                'status' => true,
                'message' => 'Data transaksi berhasil diambil',
                'data' => $transactions
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
            $transaction = Transaction::with('transaction_detail.product')->findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'Data transaksi berhasil diambil',
                'data' => $transaction
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
                'transaction_details' => 'required|array',
                'transaction_details.*.id_produk' => 'required|exists:products,id',
                'transaction_details.*.jumlah' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();

            // 1. Bikin transaksi kosong dulu (total harga nyusul)
            $transaction = Transaction::create([
                'kode_transaksi' => 'TRX-' . strtoupper(uniqid()),
                'id_user' => $request->user()->id, // AMBIL DARI TOKEN! Aman dari hacker.
                'id_admin' => null, // Belum ada admin yang approve
                'total_harga' => 0, 
                'status_pembayaran' => 'belum_bayar',
                'status' => 'pending'
            ]);

            $totalHargaAsli = 0;

            // 2. Loop detail sekalian ngitung total duitnya
            foreach ($request->transaction_details as $detail) {
                $product = Product::findOrFail($detail['id_produk']);
                
                if ($product->stok < $detail['jumlah']) {
                    throw new Exception("Stok produk {$product->nama_produk} kagak cukup bro.");
                }

                $subtotal = $product->harga * $detail['jumlah'];
                $totalHargaAsli += $subtotal;

                $product->stok -= $detail['jumlah'];
                $product->save();

                $transaction->transaction_detail()->create([
                    'id_produk' => $detail['id_produk'],
                    'jumlah' => $detail['jumlah'],
                    'harga_satuan' => $product->harga, // Ambil harga asli dari DB!
                    'subtotal' => $subtotal
                ]);
            }

            // 3. Update total harganya pake hasil itungan backend
            $transaction->update(['total_harga' => $totalHargaAsli]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi aman terkendali!',
                'data' => $transaction->load('transaction_detail.product')
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
}

    public function updateStatus(Request $request, $id) {
        try {
            $transaction = Transaction::findOrFail($id);

            if (!$transaction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'status_pembayaran' => 'required|in:belum_bayar,sudah_bayar',
                'status' => 'required|in:pending,selesai,dibatalkan',
            ]);

            $transaction->status_pembayaran = $request->status_pembayaran;
            $transaction->status = $request->status;
            $transaction->save();

            return response()->json([
                'status' => true,
                'message' => 'Status transaksi berhasil diperbarui',
                'data' => $transaction
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
