<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct()
    {
        $products = Product::all();
        $arrProduct = $this->serializeArticle($products, 'array');

        if ($arrProduct) {
            $response = response()->json([
                'data' => $arrProduct,
                'messages' => 'Success',
                'success' => true,
            ], 200);
        } else {
            $response = response()->json([
                'data' => [],
                'messages' => 'Data doesn`t exist',
                'success' => false,
            ], 200);
        }

        return $response;
    }

    public static function serializeArticle($products, $type)
    {
        $data = array();
        foreach ($products as $product) {
            $types = Type::find($product->id_types);
            $item =  array(
                'id' => $product->id,
                'product' => $product->name,
                'stock' => $product->stock,
                'id_types' => $product->id_types,
                'types' => $types->name,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            );

            if ($type == 'array') {
                $data[] = $item;
            } else {
                $data = $item;
            }
        }
        return $data;
    }
}
