<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth:api')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Product::all()->pluck('name', 'price', 'category');
        $products = Product::select('name','category', 'price')->get();
        return response()->json(['Product'=>$products], 200)->header('Content-Type', 'application/json');

    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'name' => 'required|min:2|max:225',
            'price' => 'required|numeric',
            'category' =>'required|string',
        ]);
        if($validateData->fails())
        {
            return response()->json(['error'=>$validateData->errors()], 401);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->save();
        return response()->json(['message' => $product], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $product = Product::where('id',$id)->firstOrFail();
        return response()->json(['Product details' => $product], 200)->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }


    public function update(Request $request,  $id)
    {
        $product = Product::find($id);
        if(!$product)
        {
            return response()->json(['Erroe' => "Product Not found"], 404)->header('Content-Type', 'application/json');;

        }
        else
        {
            $validateData = Validator::make($request->all(), [
                'name' => 'required|min:2|max:225',
                'price' => 'required|numeric',
                'category' =>'required|string',
                ]);
                if($validateData->fails())
                {
                    return response()->json(['error'=>$validateData->errors()], 401);
                }
                $product =  Product::find($id);
                $product->name = $request->name;
                $product->price = $request->price;
                $product->category = $request->category;
                $product->save();
                $pro['name'] = $product->name;
                $pro['price'] = $product->price;
                $pro['category'] = $product->category;
                return response()->json(["Product"=>$pro,'Success' => 'The Product has been updated'], 201);
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
