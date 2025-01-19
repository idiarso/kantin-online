<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CourierRequest;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::latest()
            ->withCount(['orders', 'deliveries'])
            ->paginate(10);

        return view('admin.couriers.index', compact('couriers'));
    }

    public function create()
    {
        return view('admin.couriers.create');
    }

    public function store(CourierRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('couriers', 'public');
        }

        $data['status'] = $request->boolean('status');
        $data['active_orders'] = 0;

        Courier::create($data);

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier created successfully');
    }

    public function edit(Courier $courier)
    {
        return view('admin.couriers.edit', compact('courier'));
    }

    public function update(CourierRequest $request, Courier $courier)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($courier->photo) {
                Storage::disk('public')->delete($courier->photo);
            }
            $data['photo'] = $request->file('photo')->store('couriers', 'public');
        }

        $data['status'] = $request->boolean('status');

        $courier->update($data);

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier updated successfully');
    }

    public function destroy(Courier $courier)
    {
        if ($courier->active_orders > 0) {
            return back()->with('error', 'Cannot delete courier with active orders');
        }

        if ($courier->photo) {
            Storage::disk('public')->delete($courier->photo);
        }

        $courier->delete();

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier deleted successfully');
    }

    public function updateStatus(Courier $courier)
    {
        $courier->status = !$courier->status;
        $courier->save();

        return back()->with('success', 'Courier status updated successfully');
    }

    public function show(Courier $courier)
    {
        $courier->loadCount(['orders', 'deliveries']);
        $courier->load(['orders' => function ($query) {
            $query->latest()->limit(5);
        }]);

        return view('admin.couriers.show', compact('courier'));
    }

    public function available()
    {
        $couriers = Courier::available()
            ->withCount(['orders', 'deliveries'])
            ->get();

        return response()->json($couriers);
    }
} 