<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class CategoryController extends Controller implements HasMiddleware
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
        $query = Category::query();

        if ($request->filled('status')) {
            $query->where('status',  $request->status);
        }
        if ($request->filled('category')) {
            $query->where('name', 'LIKE', '%' . $request->category . '%');
        }

        $category = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.settings.category.index', compact('category'))->render()
            ]);
        }
        return view('admin.settings.category.index', compact('category'));
    }

    public function create()
    {
        $code = 'CT/' . rand(100, 999);
        return view('admin.settings.category.create', compact('code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:0,1',
        ]);

        $data = $request->only(['code', 'name', 'status']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/category_images'), $filename);
            $data['image'] = 'uploads/category_images/' . $filename;
        }

        $category =  Category::create($data);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($category)
            ->log('Business Category Created');

        return redirect()->route('admin.manage.category')->with('success', 'Category added successfully!');
    }

    public function edit($id)
    {
        $cate = Category::findorFail($id);
        return view('admin.settings.category.edit', compact('cate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:0,1',
        ]);

        $category = Category::findOrFail($id);

        $data = [
            'code' => $request->code,
            'name' => $request->name,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/category_images'), $filename);
            $data['image'] = 'uploads/category_images/' . $filename;
        }

        $category->update($data);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($category)
            ->log('Business Category Updated');

        return redirect()->route('admin.manage.category')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category =   Category::findOrFail($id);
        $category->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($category)
            ->log('Business Category Deleted');
        return redirect()->route('admin.manage.category')->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $category = Category::find($request->id);

        if (!$category) {
            return response()->json(['success' => false]);
        }

        $category->status = $request->status;
        $category->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($category)
            ->log('Business Category Status Changed');

        return response()->json(['success' => true, 'status' => $category->status]);
    }
}
