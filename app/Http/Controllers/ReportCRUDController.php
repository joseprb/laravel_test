<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('laporan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tgl' => 'required|date',
            'judul' => 'required'
        ]);

        $validatedData['pelapor'] = auth()->user()->id;
        $validatedData['status'] = 'open';
        
        Report::create($validatedData);

        return redirect('/laporan')->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $report)
    {
        $validatedData = $request->validate([
            'solusi' => 'required|max:255'
        ]);

        // $validatedData['teknisi'] = auth()->user()->id;
        $validatedData['status'] = 'close';

        $rep = Report::findOrFail($report);

        $rep->update($validatedData);

        return redirect('laporan')->with('success', 'Solusi berhasil ditambahkan');
    }

    public function assignTeknisi(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $report->update(['teknisi' => $request->input('teknisi')]);

        return redirect('laporan')->with('success', 'Teknisi berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
