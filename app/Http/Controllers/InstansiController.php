<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data instansi dengan paginasi
        $instansis = Instansi::latest()->paginate(10);
        return view('instansi.index', compact('instansis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan halaman form untuk menambah instansi baru
        return view('instansi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|alpha_dash|unique:instansis,subdomain',
            'status_langganan' => ['required', Rule::in(['aktif', 'nonaktif', 'trial'])],
        ]);

        // Membuat record baru di database
        Instansi::create($validatedData);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instansi $instansi)
    {
        // Menampilkan detail satu instansi
        return view('instansi.show', compact('instansi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instansi $instansi)
    {
        // Menampilkan halaman form untuk mengedit instansi
        return view('instansi.edit', compact('instansi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instansi $instansi)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            // Pastikan subdomain unik, kecuali untuk record yang sedang diedit
            'subdomain' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('instansis')->ignore($instansi->id)],
            'status_langganan' => ['required', Rule::in(['aktif', 'nonaktif', 'trial'])],
        ]);

        // Update record di database
        $instansi->update($validatedData);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('instansi.index')->with('success', 'Data instansi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instansi $instansi)
    {
        // Hapus record dari database
        // Pastikan Anda sudah mengatur onDelete('cascade') pada migrasi terkait
        // untuk menghapus data yang berhubungan (users, karyawan, dll)
        $instansi->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil dihapus.');
    }
}
