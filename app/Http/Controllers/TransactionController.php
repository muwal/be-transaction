<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        $arrTransaction = $this->serializeArticle($transactions, 'array');

        if ($arrTransaction) {
            $response = response()->json([
                'data' => $arrTransaction,
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

    public function create()
    {
    }

    public function edit($id)
    {
        $transactions = Transaction::findOrFail($id);
        $products = Product::findOrFail($transactions->id_products);
        $types = Type::findOrFail($products->id_types);
        $data = array(
            'id' => $transactions->id,
            'id_product' => $transactions->id_products,
            'product' => $products->name,
            'stock' => $products->stock,
            'qty' => $transactions->qty,
            'id_types' => $types->id,
            'types' => $types->name,
            'created_at' => $transactions->created_at,
            'updated_at' => $transactions->updated_at,
        );

        if ($transactions) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Record not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_products' => 'required|exists:products,id',
                'qty' => 'required|numeric|min:1',
            ],
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'data'    => $validator->errors()
            ], 400);
        } else {

            $products = Product::findOrFail($request->input('id_products'));
            if ($request->input('qty') > $products->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Qty can`t be higher than current stock',
                ], 400);
            } else {
                $transaction = Transaction::create([
                    'id_products' => $request->input('id_products'),
                    'qty' => $request->input('qty')
                ]);

                $products = $products->update([
                    'stock' => $products->stock - $transaction->qty
                ]);
            }

            if ($transaction && $products) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success Create Data!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed Create Data!',
                ], 400);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_products' => 'required|exists:products,id',
                'qty' => 'required|numeric|min:1',
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill the required fields',
                'data'    => $validator->errors()
            ], 400);
        } else {

            $transaction = Transaction::findOrFail($id);
            $products = Product::findOrFail($request->input('id_products'));

            if ($request->input('qty') > $products->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Qty can`t be higher than current stock',
                ], 400);
            } else {
                if ($request->input('qty') > $transaction->qty) {
                    $products = $products->update([
                        'stock' => $products->stock - ($request->input('qty') - $transaction->qty)
                    ]);
                } else {
                    $products = $products->update([
                        'stock' => $products->stock + ($transaction->qty - $request->input('qty'))
                    ]);
                }

                $transaction = $transaction->update([
                    'id_products' => $request->input('id_products'),
                    'qty' => $request->input('qty')
                ]);
            }

            if ($transaction && $products) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success Update Data!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed Update Data!',
                ], 500);
            }
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        if ($transaction) {
            $products = Product::findOrFail($transaction->id_products);
            $products = $products->update([
                'stock' => $products->stock + $transaction->qty
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success Delete Data!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed Delete Data!',
            ], 500);
        }
    }

    public static function serializeArticle($transactions, $type)
    {
        $data = array();
        foreach ($transactions as $transaction) {
            $products = Product::find($transaction->id_products);
            $types = Type::find($products->id_types);
            $item =  array(
                'id' => $transaction->id,
                'id_product' => $transaction->id_products,
                'product' => $products->name,
                'stock' => $products->stock,
                'qty' => $transaction->qty,
                'types' => $types->name,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            );

            if ($type == 'array') {
                $data[] = $item;
            } else {
                $data = $item;
            }
        }
        return $data;
    }

    public function search($name, Request $request)
    {
        if ($name === '') {
            $transactions = Transaction::all();
        } else {
            $transactions = Transaction::whereHas('products', function ($q) use ($name) {
                $q->where('name', 'LIKE', "%$name%");
            })->get();
        }
        $arrTransaction = $this->serializeArticle($transactions, 'array');

        if ($arrTransaction) {
            $response = response()->json([
                'success' => true,
                'data' => $arrTransaction
            ], 200);
        } else {
            $response = response()->json([
                'success' => true,
                'data' => $arrTransaction
            ], 200);
        }

        return $response;
    }
}
