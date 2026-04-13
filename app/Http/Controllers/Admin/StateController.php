<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class StateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'location', 'locations_store', 'locations_edit', 'locations_destroy']),
        ];
    }
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = State::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $states = $query->orderBy('id', 'desc')->paginate($entries);
        $lastState = State::orderBy('id', 'desc')->first();
        $nextId = $lastState ? $lastState->id + 1 : 1;

        $code = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.state.index', compact('states', 'code'))->render()
            ]);
        }
        return view('admin.settings.state.index', compact('states', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $state =    State::create([
            'code' => $request->code,
            'name' => $request->name,
            'status' => $request->status,
        ]);


        activity()
            ->causedBy(Auth::id())
            ->performedOn($state)
            ->log('State Created');

        return redirect()->back()->with('success', 'State added successfully.');
    }

    public function edit(Request $request, $id)
    {
        $entries = $request->get('entries', 10);
        $query = State::query();

        $states = $query->orderBy('id', 'desc')->paginate($entries);
        $state = State::findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.state.index', compact('states', 'state'))->render()
            ]);
        }
        return view('admin.settings.state.index', compact('state', 'states'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $state = State::findOrFail($id);
        $state->update([
            'code' => $request->code,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($state)
            ->log('State Updated');

        return redirect()->route('admin.manage.states')->with('success', 'State updated successfully.');
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($state)
            ->log('State Deleted');


        return redirect()->back()->with('success', 'State deleted successfully.');
    }

    public function location(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Location::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('state')) {
            $query->where('state_id', $request->state);
        }

        $locations = $query->orderBy('id', 'desc')->paginate($entries);
        $lastState = Location::orderBy('id', 'desc')->first();
        $nextId = $lastState ? $lastState->id + 1 : 1;

        $code = str_pad($nextId, 4, '0', STR_PAD_LEFT);
        $states = State::where('status', 1)->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.location.index', compact('locations', 'code', 'states'))->render()
            ]);
        }
        return view('admin.settings.location.index', compact('locations', 'code', 'states'));
    }

    public function locations_store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'state' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $location =  Location::create([
            'code' => $request->code,
            'name' => $request->name,
            'state_id' => $request->state,
            'status' => $request->status,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($location)
            ->log('Location Created');


        return redirect()->back()->with('success', 'Location added successfully.');
    }

    public function locations_edit(Request $request, $id)
    {
        $entries = $request->get('entries', 10);
        $query = Location::query();

        $locations = $query->orderBy('id', 'desc')->paginate($entries);
        $location = Location::findOrFail($id);
        $states = State::where('status', 1)->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.state.index', compact('locations', 'location', 'states'))->render()
            ]);
        }
        return view('admin.settings.location.index', compact('locations', 'location', 'states'));
    }

    public function locations_update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'state' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $state = Location::findOrFail($id);
        $state->update([
            'code' => $request->code,
            'name' => $request->name,
            'state_id' => $request->state,
            'status' => $request->status,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($state)
            ->log('Location Updated');

        return redirect()->route('admin.manage.locations')->with('success', 'Location updated successfully.');
    }

    public function locations_destroy($id)
    {
        $state = Location::findOrFail($id);
        $state->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($state)
            ->log('Location Deleted');


        return redirect()->back()->with('success', 'Location deleted successfully.');
    }
}
