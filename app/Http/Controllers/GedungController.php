<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
   public function welcome(Request $request)
    {
        $query = Gedung::latest();
        
        if ($request->has('date')) {
            $requestDate =\Carbon\Carbon::parse($request->date);
            
            $bookedGedungIds = Booking::where(function($query) use ($requestDate) {
                $query->where(function($q) use ($requestDate) {
                    $q->where('start_date', '<=', $requestDate)
                      ->where('end_date', '>=', $requestDate);
                });
            })
            ->pluck('gedung_id');
            $query->whereNotIn('id', $bookedGedungIds);
        }
        
        $gedungs = $query->take(4)->get();
        
        return view('welcome', compact('gedungs'));
    }

    public function index()
    {
        $gedungs = Gedung::all(); 

        return view('dashboard.gedung.index', [
            'gedungs' => $gedungs
        ]);
    }

    public function create()
    {
        return view('dashboard.gedung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'nullable|integer',
            'ketersediaan' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Gedung::create($validated);

        return redirect()->route('dashboard.gedung.index')
            ->with('success', 'Gedung berhasil ditambahkan');
    }

    public function show(Gedung $gedung)
    {
        return view('dashboard.gedung.show', compact('gedung'));
    }

 public function edit(Gedung $gedung)
{
    return view('dashboard.gedung.edit', compact('gedung'));
}

    public function update(Request $request, Gedung $gedung)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'nullable|integer',
            'ketersediaan' => 'required|string|max:255',
        ]);

        $validated['user_id'] = $gedung->user_id;

        $gedung->update($validated);

        return redirect()->route('dashboard.gedung.index')
            ->with('success', 'Gedung berhasil diperbarui');
    }

    public function destroy(Gedung $gedung)
    {
        $gedung->delete();

          return redirect()->route('dashboard.gedung.index')
        ->with('success', 'Gedung berhasil dihapus');
    }
}