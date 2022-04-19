<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
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
    function  ProductDetail(Request $request)
    {
        return view('admin/pages/product-detail');
    }
}
