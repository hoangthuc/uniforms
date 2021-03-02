<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function listTemplate(Request $request, ProductType $type){
        $types = ProductType::where('enabled', '=', true)->orderBy('sort_order', 'asc')->get();
        $products = Product::where('enabled', '=', true)->orderBy('sort_order', 'asc');
        if($type->id){
            $products = $products->whereHas('type', function ($q) use ($type){
                $q->where('product_types.id', '=', $type->id);
            });
        }
        $products = $products->get(['id', 'name', 'cover_image']);
        return view('landing.templates.all', ['types' => $types, 'type' => $type, 'products' => $products]);
    }
    public function listTemplateAjax(Request $request, ProductType $type){
        $products = Product::where('enabled', '=', true)->orderBy('sort_order', 'asc');
        if($type->id){
            $products = $products->whereHas('type', function ($q) use ($type){
                $q->where('product_types.id', '=', $type->id);
            });
        }
        $products = $products->paginate(20,['id', 'name', 'cover_image']);
        return [
            'items' => $products,
            'image_url' => config('app.image_server_url')
        ];
    }
}
