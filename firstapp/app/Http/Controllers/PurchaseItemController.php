<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\ProductDetail;

class PurchaseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $product = Product::all();
        $purchase = Purchase::find($id);
        $purchaseitem = PurchaseItem::where('purchase_id', $id)->get();
        $totalamount = PurchaseItem::where('purchase_id', $id)->sum('amount');
        $discountamount = PurchaseItem::where('purchase_id', $id)->sum('discount_amount');

        return view('purchaseitem.create', [
            'product'=>$product, 
            'purchase'=>$purchase, 
            'purchaseitem'=>$purchaseitem, 
            'total'=>$totalamount, 
            'discountAmount'=>$discountamount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id) 
    {
        $validated = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'rate' => 'required',
            'sp' => 'required',
        ]);
        $purchaseItem = new Purchaseitem;
        $purchaseItem->quantity=$request->quantity;
        $purchaseItem->rate=$request->rate;
        $purchaseItem->amount=$request->quantity * $request->rate;
        $purchaseItem->discount_percent=$request->discount_percent;
        $purchaseItem->discount_amount=$request->discount_percent / 100 * $purchaseItem->amount;
        $purchaseItem->discount_percent=(!empty($purchaseItem->discount_percent)) ? $purchaseItem->discount_percent : 0;
        $purchaseItem->product_id=$request->product_id;
        $purchaseItem->purchase_id=$id;
        $purchaseItem->purchase_item_type='purchase';
        $purchaseItem->save();

        $productDetail = new ProductDetail;
        $productDetail->code = 'das';
        $productDetail->batch = time();
        $productDetail->quantity = $request->quantity;
        $productDetail->sp = $request->sp;
        $productDetail->mrp = $request->mrp;
        $productDetail->purchase_id = $id;
        $productDetail->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $purchaseItem = PurchaseItem::find($request->product_id);

        $returnItem = new PurchaseItem;
        $returnItem->product_id = $purchaseItem->product_id;
        $returnItem->quantity = $request->quantity;
        $returnItem->rate = $purchaseItem->rate;
        $returnItem->amount = $request->quantity * $purchaseItem->rate;
        $returnItem->discount_percent = $purchaseItem->discount_percent;
        $returnItem->discount_amount = ($purchaseItem->discount_percent / 100) * $returnItem->amount;
        $returnItem->purchase_id = $id;
        $returnItem->purchase_item_type = 'return';
        // return $returnItem;
        $returnItem->save();
        return back();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchaseitem = PurchaseItem::findorfail($id);
        $purchaseitem->delete();
        return back();
    }
}
