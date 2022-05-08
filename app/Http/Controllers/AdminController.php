<?php

namespace App\Http\Controllers;

use App\Models\Blob;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AdminController extends Controller
{
    public ProductService $productService;
    function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    function Index(Request $request)
    {
        return view('admin/pages/home');
    }
    function  Category(Request $request)
    {
        return view('admin/pages/category');
    }
    function  Product(Request $request)
    {
        return view('admin/pages/product');
    }
    function  ProductSave(Request $request, $id)
    {
        $this->validate($request,Product::RULES);
        $file = $request->file('file');
        if ($file && $file->isValid())
        {
            $blob = Blob::create([
                'file_path' => $file->store(''),
                'name' => $request['name'],
                'created_by' => 0
            ]);
            $request['default_image'] = $blob->id;
        }
        $product = Product::find($id);
        $this->productService->update($id, $request->except(['_token', 'file']));
        return redirect('/admin/product/'.$id);
    }
    function  ProductDetail(Request $request, int $id)
    {
        $product = $this->productService->getById($id);
        if ($product)
        {
            return view('admin/pages/product-detail', ['product' => $product]);
        }
        else
        {
            throw new NotFoundResourceException();
        }
    }
    function  ProductDetails(Request $request)
    {
        return view('admin/pages/product-detail-list');
    }
}
