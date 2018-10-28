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
    <div class="col-12-sm" style="margin-top:-10px;">
        <table class="pure-table pure-table-bordered tight-table" style="border-bottom:0;width:100%;margin-top:20px;line-height:1px;">
            <tbody>
                <tr>
                    <td style="border:1px solid white;border-bottom:1px solid #cbcbcb;"></td>
                    <td style="border-top:1px solid white;border-bottom:1px solid white;"></td>
                    <td style="border-bottom:1px solid #cbcbcb;">
                        <b>Ship To</b>
                    </td>
                    <td style="border-top:1px solid white;border-bottom:1px solid white;"></td>
                    <td style="border-bottom:1px solid #cbcbcb;">
                        <b>Bill To</b>
                    </td>
                    <td style="border-top:1px solid white;border-bottom:1px solid white;"></td>
                    <td style="border-bottom:1px solid #cbcbcb;">
                        <b>Project Code</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:90px;">Location</td>
                    <td style="border-bottom:0px;"></td>
                    <td>{{ optional($data->shipping_address_json)->location }}</td>
                    <td style="border-bottom:0px;"></td>
                    <td>{{ optional($data->billing_address_json)->location }}</td>
                    <td style="border-bottom:0px;"></td>
                    <td rowspan="5" style="width:120px;vertical-align: top;">
                        {{-- TODO: Project code --}}
                    </td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td style="border-bottom:0px;"/>
                    <td>{{ optional($data->shipping_address_json)->department }}</td>
                    <td style="border-bottom:0px;"></td>
                    <td>{{ optional($data->billing_address_json)->department }}</td>
                    <td style="border-bottom:0px;"></td>
                </tr>
                <tr>
                    <td>Contact name</td>
                    <td style="border-bottom:0px;"/>
                    <td>{{ optional($data->shipping_address_json)->contact_name }}</td>
                    <td style="border-bottom:0px;"></td>
                    <td>{{ optional($data->billing_address_json)->contact_name }}</td>
                    <td style="border-bottom:0px;"></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td style="border-bottom:0px;"/>
                    <td>{{ optional($data->shipping_address_json)->phone }}</td>
                    <td style="border-bottom:0px;"></td>
                    <td>{{ optional($data->billing_address_json)->phone }}</td>
                    <td style="border-bottom:0px;"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td style="border-bottom:0px;"/>
                    <td>{{ optional($data->shipping_address_json)->email }}</td>
                    <td style="border-bottom:0px;"></td>
                    <td>{{ optional($data->billing_address_json)->email }}</td>
                    <td style="border-bottom:0px;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if($data->delivery_date)
<div class="row">
    <div class="col-12-sm" style="margin-top:-10px;">
        <p style="font-size:12px;">Reference to your {!! $data->quote_reference_number ? '<u>Quote Ref. No. '.$data->quote_reference_number.'</u>' : 'Quotation' !!}
            {!! $data->quote_date_string ? ' dated <u>'.$data->quote_date_string.'</u>' : '' !!},
            you are authorized to provide / supply the following not later than
            <u>{{ $data->delivery_date->format('d-m-Y') }}</u> or at your earliest.</p>
    </div>
</div>
@endif

@component('pdf.purchase-orders.lines', ['data' => $data])
@endcomponent

<div style="page-break-inside: avoid !important;">
    <div class="row">
        <div class="col-12-sm" style="margin-top:-10px;">
            {{-- Terms --}}
            @component('pdf.purchase-orders.terms', ['data' => $data])
            @endcomponent

            {{-- Signatures block --}}
            @component('pdf.purchase-orders.signatures-block', ['data' => $data])
            @endcomponent
        </div>
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

{{-- End of page-break-inside: avoid --}}
</div>

</body>
</html>
