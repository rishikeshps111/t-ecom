<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SubCategoryController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'toggleStatus']),
        ];
    }
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = SubCategory::query();
        $filterCategories = Category::where('status', true)->get();
        if ($request->filled('status')) {
            $query->where('status',  $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $category = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.sub-category.index', compact('category', 'filterCategories'))->render()
            ]);
        }

        return view('admin.settings.sub-category.index', compact('category', 'filterCategories'));
    }

    public function create()
    {
        $last = SubCategory::latest('id')->first();
        $code = 'SUB-' . str_pad(optional($last)->id + 1, 4, '0', STR_PAD_LEFT);
        $category = Category::where('status', 1)->get();
        return view('admin.settings.sub-category.create', compact('code', 'category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:50|unique:sub_categories,code',
            'name'     => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'status'   => 'nullable|in:0,1',
        ]);

        $subCategory =   SubCategory::create([
            'code'        => $request->code,
            'name'        => $request->name,
            'category_id' => $request->category,
            'status'      => $request->status,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($subCategory)
            ->log('SubCategory Created');


        return redirect()->route('admin.manage.sub.category')->with('success', 'Sub Category created successfully.');
    }

    public function edit($id)
    {
        $sub = SubCategory::findorFail($id);
        $category = Category::where('status', 1)->get();
        return view('admin.settings.sub-category.edit', compact('sub', 'category'));
    }

    public function update(Request $request, $id)
    {
        $sub = SubCategory::findOrFail($id);

        $request->validate([
            'code'     => 'required|string|max:50|unique:sub_categories,code,' . $sub->id,
            'name'     => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'status'   => 'nullable|in:0,1',
        ]);

        $sub->update([
            'name'        => $request->name,
            'category_id' => $request->category,
            'status'      => $request->status,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($sub)
            ->log('SubCategory Updated');


        return redirect()->route('admin.manage.sub.category')->with('success', 'Sub Category updated successfully.');
    }

    public function destroy($id)
    {
        $record = SubCategory::findOrFail($id);
        $record->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('SubCategory Deleted');

        return redirect()->route('admin.manage.sub.category')->with('success', 'Sub Category deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $category = SubCategory::find($request->id);

        if (!$category) {
            return response()->json(['success' => false]);
        }

        $category->status = $request->status;
        $category->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($category)
            ->log('SubCategory Status Changed');

        return response()->json(['success' => true, 'status' => $category->status]);
    }
}
