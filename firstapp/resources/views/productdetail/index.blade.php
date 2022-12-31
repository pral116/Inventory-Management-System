@extends('layouts.app')
@section('content')

    <div class="card card-body shadow-sm mt-5 mb-5">
        <div class="table-responsive">
            <table class="table text-center table-bordered mt-3 bg-light">
                <thead class="fw-bold">
                    <tr>
                        <td>S.N.</td>
                        {{-- <td>Code</td> --}}
                        {{-- <td>Name</td> --}}
                        <td>Batch</td>
                        <td>Quantity</td>
                        <td>SP</td>
                        <td>Mrp</td>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($productDetail as $row)
                        <tr>
                            <td>{{ ++$i }}</td>
                            {{-- <td>{{ $row->product->code }}</td> --}}
                            {{-- <td>{{ $row->product->name }}</td> --}}
                            <td>{{ $row->batch }}</td>
                            <td>{{ $row->quantity }}</td>
                            <td>{{ $row->sp }}</td>
                            <td>{{ $row->mrp }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

@endsection