<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $category = Category::latest()->get();
            return DataTables::of($category)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<button type="button" class="btn btn-primary btn-sm editKategori" data-toggle="tooltip" data-id="' . $row->id . '" data-original-name="Edit"><i class="fa fa-pen"></i></button>';
                    
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm deleteKategori" data-toggle="tooltip" data-id="' . $row->id . '" data-original-name="Delete"><i class="fa fa-trash"></i></button>';
                    
                    $buttonGroup = '<div class="btn-group" role="group">' . $editBtn . $deleteBtn . '</div>';
                    
                    return $buttonGroup;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.categories.index');
    }

    public function store(Request $request)
    {
         // Lakukan validasi
    $validatedData = $request->validate([
        'name' => 'required|string',
        'cat_id' => 'nullable|exists:categories,id',
    ]);

    if ($validatedData['cat_id']) {
        // Jika cat_id tersedia, lakukan update
        Category::where('id', $validatedData['cat_id'])->update([
            'name' => $validatedData['name'],
        ]);
    } else {
        // Jika cat_id tidak tersedia, buat entri baru
        Category::create([
            'name' => $validatedData['name'],
        ]);
    }

    return response()->json(['success' => 'Product saved successfully.']);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::find($id)->delete();

        return response()->json(['success' => 'Product deleted successfully.']);
    }
}
