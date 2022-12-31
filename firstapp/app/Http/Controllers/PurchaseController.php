<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Purchase::all();
        return view('purchase.index', ['purchase'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supplier = Supplier::all();
        return view('purchase.create', ['supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase = new Purchase;
        $purchase->date=$request->date;
        $purchase->invoice_no=$request->invoice_no;
        $purchase->supplier_id=$request->supplier_id;
        $purchase->purchase_type=$request->purchase_type;
        $purchase->user_id=Auth::id();
        $purchase->remark=$request->remark;
        $purchase->save();

        if ($purchase->purchase_type == "Direct" || $purchase->purchase_type == "Order") {
            return redirect()->route('purchaseitem.add', ['id'=>$purchase->id]);
        }
        else {
            return redirect()->route('purchase.return', ['id' => $purchase->id]);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::find($id);
        $purchaseItem = PurchaseItem::where('purchase_id', $id)->get();
        $totalamount = PurchaseItem::where('purchase_id', $id)->sum('amount');
        $discountamount = PurchaseItem::where('purchase_id', $id)->sum('discount_amount');
        return view('purchase.bill', [
            'purchaseitem'=>$purchaseItem,
            'purchase'=>$purchase, 
            'totalamount'=>$totalamount, 
            'discountamount'=>$discountamount,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::find($id);
        $supplier = Supplier::all();
        return view('purchase.edit', ['purchase'=>$purchase, 'supplier'=>$supplier]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase, $id)
    {
        $purchase = Purchase::find($id);
        $purchase->date=$request->date;
        $purchase->invoice_no=$request->invoice_no;
        $purchase->supplier_id=$request->supplier_id;
        $purchase->purchase_type=$request->purchase_type;
        $purchase->user_id=Auth::id();
        $purchase->remark=$request->remark;
        $purchase->save();

        return redirect()->route('purchase.index')->with('success', 'Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }

    public function completePurchase(Request $request, $id)
    {
        $purchase = Purchase::find($id);
        $purchase->shipping_cost = $request->shipping_cost;
        $purchase->adjustable_discount = $request->adjustable_discount;
        $purchase->status = "Completed";
        $purchase->save();
        return redirect()->route('purchase.bill', ['id'=>$purchase->id]);
    }

    public function return($id) {  //1
        $purchase = Purchase::find($id);
        $purchaseItem = Purchase::where('supplier_id', $purchase->supplier_id)
                                ->with('purchaseItem.product')->get();
        // return $purchaseItem;
        $returnPurchase = PurchaseItem::where('purchase_id', $id)->get();
        
        return view('purchase.return', ['purchase' => $purchase, 'purchaseItem' => $purchaseItem, 'return' => $returnPurchase]);
    }
}
