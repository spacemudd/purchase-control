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
                    {{ optional($data->vendor)->name }}<br/>
                    {{ optional($data->vendor)->telephone_number }}<br/>
                    <br/>
                    {{ optional($data->vendor)->address }}<br/>
                    {{ optional($data->vendor)->email }}<br/>
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
            <tr><td>{{ optional($data->shipping_address_json)->location }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->shipping_address_json)->department }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->shipping_address_json)->contact_name }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->shipping_address_json)->phone }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->shipping_address_json)->email }}&nbsp;</td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-3-sm">
        <table class="pure-table pure-table-bordered tight-table" style="width:100%;">
            <tbody>
            <tr><td><strong>Bill To</strong></td></tr>
            <tr><td>{{ optional($data->billing_address_json)->location }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->billing_address_json)->department }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->billing_address_json)->contact_name }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->billing_address_json)->phone }}&nbsp;</td></tr>
            <tr><td>{{ optional($data->billing_address_json)->email }}&nbsp;</td></tr>
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

@if($data->delivery_date)
<div class="row">
    <div class="col-12-sm">
        <p>Reference to your Quotation, you are authorized to provide / supply the following not later than <u>{{ optional($data->delivery_date)->format('d-m-Y') }}</u>.</p>
    </div>
</div>
@endif

{{-- Items --}}
<div class="row">
    <div class="col-12-sm">
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
                    Currency: <u>{{ $data->currency }}</u><br/>
                    Total Price
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->items as $counter => $item)
                <tr>
                    <td class="center" rowspan="4">{{ ++$counter }}</td>
                    <td rowspan="4">{{ optional($item->item_catalog)->display_name }}</td>
                    <td class="center">{{ $item->qty }}</td>
                    <td class="right">
                        {{ $item->subtotal_before_discount }}<br/>
                    </td>
                </tr>
                @if($item->discount_flat)
                <tr>
                    <td class="center">Discount</td>
                    <td class="right">
                        -{{ $item->discount_flat }}<br/>
                    </td>
                </tr>
                <tr>
                    <td class="center">Subtotal</td>
                    <td class="right">{{ $item->subtotal }}</td>
                </tr>
                @else
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                <tr>
                    <td class="center">VAT</td>
                    <td class="right">{{ $item->total_taxes_amount }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="right"><strong>Total after VAT</strong></td>
                <td class="right"><strong>{{ $data->total }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Terms --}}
@if($data->terms_json)
    <div style="padding:10px;margin:20px 0;">
        @if(isset($data->terms_json->Others))
            <h4>Other Terms</h4>
            <p style="margin-left: 29px;">
                {!! nl2br($data->terms_json->Others) !!}
            </p>
        @endif
            @foreach($data->terms_json as $type => $terms)
                @if($type === 'Others')
                    @break
                @endif
                <h4>{{ $type }}</h4>
                <ul>
                    @foreach($terms as $term)
                        @if($term->value)
                            <li>{{ $term->value->value }}:
                                @if(isset($term->enabled) && $term->enabled)
                                    No ☐ <b>Yes ☒</b>
                                @else
                                    No ☒ <b>Yes ☐</b>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endforeach
    </div>
@endif

{{-- Signatures block --}}
<div>
    <strong>For and on behalf of Arab National Bank</strong>
    <div class="row" style="margin-top:100px;">
        <div class="col-4-sm center">
            <div style="border-top:2px solid black;width:100%;font-size:11px;">
                <strong>Abdullah S. Alsowayel<br/>
                    Head of Administration Services Center</strong>
            </div>
        </div>
        <div class="col-4-sm center">

        </div>
        <div class="col-4-sm center">
            <div style="border-top:2px solid black;width:100%;font-size:11px;">
                <strong>Mahmoud H. Al-Enezi<br/>
                    Procurement Manager - Presmises & Admin</strong>
            </div>
        </div>
    </div>
    <hr>
    <p class="center" style="font-size:11px;">The accompanying Terms and Conditions form an integral part of this Purchase Order</p>
    <p class="center" style="font-size:10px;">Prepared by {{ $data->created_by->username }} - {{ $data->created_by->name }}</p>
</div>

{{-- Footer --}}
{{--
<div class="row" style="font-size:11px;">
    <div class="col-6-sm">
        <strong>arab national bank Saudi Stock Co. Paid up Capital S.R. 10,000 Million</strong><br/>
        P.O. Box 56921, Riyadh 11564 - Kingdom of Saudi Arabia<br/>
        Tel +966 11 402 9000 Fax +966 11 402 7747<br/>
    </div>
    <div class="col-6-sm right" dir="rtl">
        <strong>البنك العربي الوطني شركة مساهمة سعودية رأس المال المدفوع 10,000 مليون ريال</strong><br/>
        ص.ب. 56921، الـــريـــــــــــاض 11574 - المملــكــة العــربـــيـــة الـسـعـوديـــة<br/>
        تلفون <span dir="ltr">+966 11 402 9000</span> فاكس <span dir="ltr">+966 11 402 7747</span>
    </div>
</div>
--}}

</body>
</html>
