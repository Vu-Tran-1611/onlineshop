@extends('vendor.layout.master')

@section('content')
    <section class="section">

        <div class="section-header">

            <h1>Invoice</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Invoice</div>
            </div>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Invoice</h2>
                                <div class="invoice-number">Order #{{ $order->invoice_id }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Billed To:</strong> <br>

                                        <b>Address:&emsp;</b>{{ $address->address }}<br>
                                        <b>Zip:&emsp;</b>{{ $address->zip }}<br>
                                        <b>Country:&emsp;</b>{{ $address->state }} - {{ $address->country }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Order Date:</strong><br>

                                        {{ date('F d,Y, h:i A', strToTime($order->created_at)) }}<br><br>
                                    </address>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- <address style="text-transform: capitalize">
                                        <strong>Payment Method:</strong><br>
                                        <b>Method:&emsp;</b>{{ $transaction->payment_method }}<br>
                                        <b>Transaction ID:&emsp;</b>{{ $transaction->transaction_id }} <br>

                                    </address> --}}
                                </div>

                            </div>
                        </div>
                    </div>

                    <form action="{{ route('vendor.orders.update.status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="section-title">Order Summary</div>
                                <p class="section-lead">All items here cannot be deleted.</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-md">
                                        <tbody>
                                            <tr>
                                                <th data-width="40" style="width: 40px;">#</th>
                                                <th>Item</th>
                                                <th class="text-center">Variant</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Totals</th>
                                                <th class="text-right">Status</th>

                                            </tr>
                                            @foreach ($orderProducts as $key => $product)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>
                                                        @if (empty($product->variants))
                                                            None
                                                        @else
                                                            @foreach (json_decode($product->variants, true) as $key => $item)
                                                                <div>
                                                                    <b> {{ $key }}: </b>
                                                                    {{ $item }}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </td>

                                                    </td>
                                                    <td class="text-center">{{ $product->qty }}</td>
                                                    <td class="text-right">$ {{ $product->unit_price }}

                                                    </td>
                                                    <td class="text-right ">
                                                        {{-- Select Status --}}
                                                        <select class="form-control status-select"
                                                            data-id="{{ $product->id }}"
                                                            name="status_{{ $product->id }}">
                                                            <option value="delivered"
                                                                {{ $product->status == 'delivered' ? 'selected' : '' }}>
                                                                Delivered
                                                            </option>
                                                            <option value="pending"
                                                                {{ $product->status == 'pending' ? 'selected' : '' }}>
                                                                Pending
                                                            </option>
                                                            <option value="cancelled"
                                                                {{ $product->status == 'cancelled' ? 'selected' : '' }}>
                                                                Cancelled
                                                            </option>
                                                            <option value="confirmed"
                                                                {{ $product->status == 'confirmed' ? 'selected' : '' }}>
                                                                Confirmed
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-right">
                                        {{-- Update Status --}}
                                        <button type="submit" class="btn btn-primary mt-3 update-status"
                                            data-id="{{ $order->id }}">Update Status</button>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-8">
                                        <div class="col-5">
                                            {{-- Show Payment Status --}}
                                            <div class=' form-group'>
                                                <label>Payment Status</label>
                                                <div>
                                                    @if ($order->payment_status == '1')
                                                        <span class="badge badge-success">Paid</span>
                                                    @else
                                                        <span class="badge badge-danger">Unpaid</span>
                                                    @endif
                                                </div>
                                                <br />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <hr class="mt-2 mb-2">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Total</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                $ {{ $total }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
                {{-- Back button --}}
                <div class="section-header-back">

                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i>
                        Back to Orders
                    </a>

                </div>
                <hr>

            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        $(".print_btn").on("click", function() {
            $content = $(".invoice-print").html();
            $original = $("body").html();
            $("body").html($content);
            window.print();
            $("body").html($original);
        });
    </script>
@endpush
