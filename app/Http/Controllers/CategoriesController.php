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
        Category::updateOrCreate(
            [
                'id' => $request->cat_id
            ],
            [
                'name' => $request->name
            ]
        );

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
