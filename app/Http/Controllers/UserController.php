<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    //
    public function index(Request $request){

        if($request->ajax()){
            $pegawai = User::latest()->get();
            return DataTables::of($pegawai)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editBtn = '<button type="button" class="btn btn-primary btn-sm editUser" data-toggle="tooltip" data-id="' . $row->id . '" data-original-name="Edit"><i class="fa fa-pen"></i></button>';
                
                $deleteBtn = '<button type="button" class="btn btn-danger btn-sm deleteUser" data-toggle="tooltip" data-id="' . $row->id . '" data-original-name="Delete"><i class="fa fa-trash"></i></button>';
                
                $buttonGroup = '<div class="btn-group" role="group">' . $editBtn . $deleteBtn . '</div>';
                
                return $buttonGroup;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('pages.pegawai.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'password' => 'required',
            'role' => 'required|in:admin,petugas,kasir',
        ];
    
        // Jika tidak sedang dalam proses edit, tambahkan validasi untuk email unik
        if (!$request->filled('user_id')) {
            $rules['email'] = 'required|unique:users,email';
        }
    
        $validatedData = $request->validate($rules);
    
        // Jika ada user_id yang dikirimkan, artinya ini adalah proses update
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
    
            if ($user) {
                // Update data pengguna
                $user->update([
                    'name' => $validatedData['name'],
                    // Pastikan email tersedia sebelum mengaksesnya
                    'email' => $validatedData['email'] ?? $user->email,
                    'password' => bcrypt($validatedData['password']), // Encrypt password jika perlu
                    'role' => $validatedData['role'],
                ]);
    
                return response()->json(['success' => 'Berhasil memperbarui pegawai!']);
            } else {
                return response()->json(['error' => 'Pengguna tidak ditemukan!'], 404);
            }
        } else {
            // Tidak ada user_id yang dikirimkan, artinya ini adalah proses create baru
            User::create([
                'name' => $validatedData['name'],
                // Pastikan email tersedia sebelum mengaksesnya
                'email' => $validatedData['email'] ?? null,
                'password' => bcrypt($validatedData['password']), // Encrypt password jika perlu
                'role' => $validatedData['role'],
            ]);
    
            return response()->json(['success' => 'Berhasil menambahkan pegawai!']);
        }
    }

    public function edit($id){
        // $pegawai = User::find($id);
        // // $pegawai->password = Crypt::decrypt($pegawai->password); // Mendekripsi password
        // // $pegawai->makeVisible('password'); // Memastikan field password terlihat
        // $pegawai->password = Crypt::decrypt($pegawai->password);
        // return response()->json($pegawai);
        try {
            // Mengambil data pengguna berdasarkan ID
            $pegawai = User::find($id);
    
            // Mengembalikan respons dengan data pengguna termasuk password yang terdekripsi
            return response()->json($pegawai);
        } catch (\Exception $e) {
            // Tangkap dan tangani eksepsi jika terjadi kesalahan dalam dekripsi
            return response()->json(['error' => 'Gagal mengambil data user:' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id)->delete();
        if ($user) {
            $user = User::destroy($id);
            return response()->json(['success' => 'Pengguna berhasil dihapus.']);
        } else {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }
    }
}
