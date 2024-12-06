<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WatchLater;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WatchLatersController extends Controller
{
    /**
     * Tampil semua video Watch Later dari user tertentu.
     */
    public function index()
    {
        // Ambil ID pengguna yang sedang login
        $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login secara otomatis
        
        // Pastikan user ditemukan
        $user = User::find($userId);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        // Ambil semua konten yang ada di watch later milik user
        $watchLaters = WatchLater::where('id_user', $userId)->with('content')->get();

        return response([
            'message' => 'Watch Later Contents Retrieved',
            'data' => $watchLaters
        ], 200);
    }

    /**
     * Tambah video ke Watch Later
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_content' => 'required|exists:contents,id',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors(),
            ], 400);
        }

        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Cek apakah konten sudah ada di daftar Watch Later
        $existingWatchLater = WatchLater::where('id_user', $userId)
                                        ->where('id_content', $request->id_content)
                                        ->first();

        if ($existingWatchLater) {
            return response([
                'message' => 'Content already added to Watch Later',
            ], 403);
        }

        // Simpan video ke dalam Watch Later
        $watchLater = WatchLater::create([
            'id_user' => $userId,
            'id_content' => $request->id_content,
            'date_added' => date('Y-m-d'),  // Menyimpan tanggal saat ini
        ]);

        return response([
            'message' => 'Content added to Watch Later',
            'data' => $watchLater
        ], 201);
    }

    /**
     * Hapus video dari Watch Later
     */
    public function destroy(string $id)
    {
        // Validasi input
        $watchLater = WatchLater::find($id);

        if(is_null($watchLater)){
            return response([
                'message' => 'watchLater Not Found',
                'data' => null
            ],404);
        }

        if($watchLater->delete()){
            return response([
                'message' => 'Removed from Your Watch Later List',
                'data' => $watchLater,
            ],200);
        }
    }

}
