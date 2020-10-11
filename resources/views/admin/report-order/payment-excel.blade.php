<table>
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Order Number</th>
                <th>Fee Vendor</th>
                <th>Fee Company</th>
                <th>Fee Dropshipper</th>
                <th>Payment Vendor</th>
                <th>Payment Dropshipper</th>
                <th>Payment Vendor Date</th>
                <th>Payment Dropshipper Date</th>
                <th>Payment Vendor Reference</th>
                <th>Payment Dropshipper Reference</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @php
                                    $feeSeller = 0;
                                    $feeCompany = 0;
                                    $feeDropshipper = 0;    
        @endphp
        @foreach($orders as $order)
            @foreach ( unserialize(bzdecompress(utf8_decode($order->cart)))->items as $product)
                @php
    
                                        if ($product['is_dropship'] === true) {
                                            $feeDropshipper += feeDropshipper($product['item']['publish_price']);                                     
                                            $feeCompany += (feeCompany($product['item']['publish_price']) - feeDropshipper($product['item']['publish_price']) );
                                        }else{
                                            $feeCompany += feeCompany($product['item']['publish_price']);
                                        }
    
                                        $feeSeller += feeVendor($product['item']['publish_price']);
    
                @endphp
            @endforeach
            <tr>
                <td>{{ $order->created_at }}</td>
                <td>#{{ $order->order_number }}</td>
                <td>{{ $feeSeller + $order->shipping_cost }}</td>
                <td>{{ $feeCompany }}</td>
                <td>{{ $feeDropshipper }}</td>
                <td>{{ $order->vendor_payment === '1' ? 'Paid' : 'Unpaid' }}</td>
                <td>{{ $order->dropshipper_payment === '1' ? 'Paid' : 'Unpaid' }}</td>
                <td>{{ $order->payment_vendor_date }}</td>
                <td>{{ $order->payment_dropshipper_date }}</td>
                <td>{{ $order->payment_vendor_reference }}</td>
                <td>{{ $order->payment_dropshipper_reference }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>