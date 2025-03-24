<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Student;

class DepositController extends Controller
{
    /**
     * Tampilkan daftar setoran.
     */
    public function index()
    {
        $deposits = Deposit::with('student')->latest()->paginate(10);
        return view('deposits.index', compact('deposits'));
        
    }

    

    /**
     * Tampilkan form tambah setoran.
     */
    public function create()
    {
        $students = Student::all();
        return view('deposits.create', compact('students'));
    }

    /**
     * Simpan data setoran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1000',
            'date_paid' => 'required|date',
        ]);

        Deposit::create($request->all());

        return redirect()->route('deposits.index')->with('success', 'Setoran berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail setoran.
     */
    public function show(Deposit $deposit)
    {
        return view('deposits.show', compact('deposit'));
    }

    /**
     * Tampilkan form edit setoran.
     */
    public function edit(Deposit $deposit)
    {
        $students = Student::all();
        return view('deposits.edit', compact('deposit', 'students'));
    }

    /**
     * Perbarui data setoran.
     */
    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1000',
            'date_paid' => 'required|date',
        ]);

        $deposit->update($request->all());

        return redirect()->route('deposits.index')->with('success', 'Setoran berhasil diperbarui!');
    }

    /**
     * Hapus setoran.
     */
    public function destroy(Deposit $deposit)
    {
        $deposit->delete();
        return redirect()->route('deposits.index')->with('success', 'Setoran berhasil dihapus!');
    }

}
