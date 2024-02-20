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

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-name="Edit" class="edit btn btn-primary btn-sm editCategory"><i class="fa fa-pen"></i></a>';

                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-name="Delete" class="btn btn-danger btn-sm deleteCategory"><i class="fa fa-trash"></i></a>';

                    return $btn;
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
