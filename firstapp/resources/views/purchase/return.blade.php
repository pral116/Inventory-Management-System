@extends('layouts.app')
@section('content')
    <div class="container">
        <h4 class="text-center text-primary mb-4">RETURN PURCHASE</h4>
        <div class="card card-body">
            <form action="{{ route('purchaseitem.return', ['id' => $purchase->id]) }}" class="row" method="POST">
                @csrf
                <div class="col">
                    <select name="product_id" id="" class="form-control">
                        @foreach ($purchaseItem as $item)
                        @foreach ($item->PurchaseItem as $col)
                        @if ($col->purchase_item_type != 'return')
                        <option value="{{ $col->id }}">{{ $col->product->name }} / {{ $col->quantity }}</option> 
                        @endif
                        @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" name="quantity" placeholder="Quantity" class="form-control">
                </div>
                <div class="col">
                    <input value="RETURN" class="btn btn-md btn-outline-primary" type="submit">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-borderd">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Discount %</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($return as $row)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $row->product->name }}</td>
                                <td>{{ $row->quantity }}</td>
                                <td>{{ $row->product->unit }}</td>
                                <td>{{ $row->discount_percent }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection