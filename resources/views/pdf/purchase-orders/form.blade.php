<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data->code }}</title>
    <style>
        @include('pdf.style')
    </style>
</head>
<body>

{{-- Purchase and Supplier Information --}}
<div class="row">
    <div class="col-6-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;">
            <thead>
            <tr>
                <th style="line-height: 5px;">Purchaser</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Arab National Bank<br/>
                    Premises &amp; Admin Service Department<br/>
                    ANB New Tower - King Faisal St. - Riyadh<br/>
                    Tel (011) 402 9000 Ext. 4326<br/>
                    Fax (011) 207 0366<br/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-6-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;">
            <thead>
            <tr>
                <th style="line-height: 5px;">Supplier</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    {{ $data->vendor->name }}<br/>
                    {{ $data->vendor->telephone_number }}<br/>
                    <br/>
                    {{ $data->vendor->address }}<br/>
                    Email: {{ $data->vendor->email }}<br/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Shipment and Billing addresses --}}
<div class="row">
    <div class="col-2-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;margin-top:20px;">
            <tbody>
                <tr><td>Location</td></tr>
                <tr><td>Department</td></tr>
                <tr><td>Contact name</td></tr>
                <tr><td>Phone</td></tr>
                <tr><td>Email</td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-3-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;">
            <tbody>
            <tr><td><strong>Ship To</strong></td></tr>
            <tr><td>{{ $data->shipping_address_json->location }}</td></tr>
            <tr><td>{{ $data->shipping_address_json->department }}</td></tr>
            <tr><td>{{ $data->shipping_address_json->contact_name }}</td></tr>
            <tr><td>{{ $data->shipping_address_json->phone }}</td></tr>
            <tr><td>{{ $data->shipping_address_json->email }}</td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-3-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;">
            <tbody>
            <tr><td><strong>Bill To</strong></td></tr>
            <tr><td>{{ $data->billing_address_json->location }}</td></tr>
            <tr><td>{{ $data->billing_address_json->department }}</td></tr>
            <tr><td>{{ $data->billing_address_json->contact_name }}</td></tr>
            <tr><td>{{ $data->billing_address_json->phone }}</td></tr>
            <tr><td>{{ $data->billing_address_json->email }}</td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-4-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;">
            <tbody>
            <tr><td><strong>Project Code</strong></td></tr>
            <tr style="color:white;"><td style="color:white;">.</td></tr>
            <tr style="color:white;"><td style="color:white;">.</td></tr>
            <tr style="color:white;"><td style="color:white;">.</td></tr>
            <tr style="color:white;"><td style="color:white;">.</td></tr>
            <tr style="color:white;"><td style="color:white;">.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-12-sm">
        <p>Reference to your Quotation, you are authorized to provide / supply the following not later than <u>{{ optional($data->delivery_date)->format('d-m-Y') }}</u>.</p>
    </div>
</div>

{{-- Items --}}
<div>
    <table class="pure-table pure-table-bordered tight-table">
        <colgroup>
            <col style='width:1%;'>
            <col style='width:50%;'>
            <col style='width:10%;'>
            <col style='width:10%;'>
        </colgroup>
        <thead>
        <tr>
            <th class="center">Item</th>
            <th class="center">Description of items to be supplied</th>
            <th class="center">Qty</th>
            <th class="center">
                Currency: <u>SR</u><br/>
                Total Price
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($data->items as $counter => $item)
            <tr>
                <td class="center" rowspan="2">{{ ++$counter }}</td>
                <td rowspan="2">{{ $item->description }}</td>
                <td class="center">{{ $item->qty }}</td>
                <td class="right">
                    {{ $item->unit_price }}<br/>
                </td>
            </tr>
            <tr>
                <td class="center">VAT</td>
                <td class="right">{{ $item->tax_amount_1 }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="right"><strong>Total after VAT</strong></td>
            <td class="right"><strong>{{ $data->total }}</strong></td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
