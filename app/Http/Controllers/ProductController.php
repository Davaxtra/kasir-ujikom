<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = DB::table("categories")->get();

        if (request()->ajax()) {
            $product = Product::with('category')->latest()->get();
            return DataTables::of($product)
                ->addIndexColumn()
                ->addColumn('category_name', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('image', function ($row) {
                    $url = asset('storage/product/' . $row->image);
                    return ' <img id="preview" src=' . $url . ' alt="Preview" class="form-group hidden" width="100" height="100" style="border: 5px solid #555;">';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-name="Edit" class="edit btn btn-primary btn-sm editProduct"><i class="fa fa-pen"></i></a>';

                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-name="Delete" class="btn btn-danger btn-sm deleteProduct"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('pages.products.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // untuk menampilkan kategori
        // $search = $request->q;
        // $data = DB::table("categories")
        //     ->select("id", "name")
        //     ->where('name', 'LIKE', "%$search%")
        //     ->get();
        // return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $id = $request->id;

        $details = [
            'name' => $request->name,
            'category_id' => $request->category,
            'stock' => $request->stock,
            'price' => $request->price,
        ];
        $product = Product::find($id);

        if ($product) {
            // Hapus file gambar yang terkait dengan produk sebelumnya
            if ($product->image) {
                File::delete('storage/product/' . $product->image);
            }
        }

        if ($files = $request->file('image')) {

            //delete old file
            // File::delete('public/product/' . $request->image);

            //insert new file
            $destinationPath = 'storage/product/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $details['image'] = "$profileImage";
        }
        $product = Product::updateOrCreate(['id' => $id], $details);

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $data = Product::where('id', $id)->first(['image']);
        // File::delete('public/product/' . $data->image);
        // $product = Product::where('id', $id)->delete();
        // return response()->json($product);

        // Ambil nama file gambar dari produk yang akan dihapus
        $data = Product::findOrFail($id);
        $imageFileName = $data->image;

        // Hapus file gambar terkait dari sistem file
        if ($imageFileName) {
            $imagePath = ('storage/product/' . $imageFileName);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Hapus entri produk dari database
        $product = Product::destroy($id);

        return response()->json($product);
    }
}
