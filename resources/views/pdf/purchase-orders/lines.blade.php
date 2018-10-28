{{-- Items --}}
<div class="row">
    <div class="col-12-sm">
        <table class="pure-table pure-table-bordered tight-table">
            <colgroup>
                <col style='width:60px;'>
                <col style='width:600px;'>
                <col style='width:100px;'>
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
                @if(! ($counter ===  (count($data->items) - 1) || $counter ===  (count($data->items) - 2)) )
                    <tr style="border-top:2px #cbcbcb solid;">
                        <td class="center" rowspan="3">{{ ++$counter }}</td>
                        <td rowspan="3">{{ optional($item->item_catalog)->display_name }}</td>
                        <td class="center">{{ $item->qty }}</td>
                        <td class="right">
                            {{ number_format($item->subtotal_before_discount->toFloat(), 2) }}<br/>
                        </td>
                    </tr>
                    @if($item->discount_flat)
                        <tr>
                            <td class="center">Discount</td>
                            <td class="right">
                                -{{ number_format($item->discount_flat->toFloat(), 2) }}<br/>
                            </td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td class="center">Subtotal</td>--}}
                            {{--<td class="right">{{ number_format($item->subtotal->toFloat(), 2) }}</td>--}}
                        {{--</tr>--}}
                    @else
                        <tr>
                            <td style="border:none;border-collapse: collapse"></td>
                            <td style="border:none;border-collapse: collapse"></td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td style="border:none;border-collapse: collapse"></td>--}}
                            {{--<td style="border:none;border-collapse: collapse"></td>--}}
                        {{--</tr>--}}
                    @endif
                    <tr>
                        <td class="center">VAT</td>
                        <td class="right">{{ number_format($item->total_taxes_amount->toFloat(), 2) }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        <div style="page-break-inside: avoid !important;">
            <table class="pure-table pure-table-bordered tight-table">
                <colgroup>
                    <col style='width:60px;'>
                    <col style='width:600px;'>
                    <col style='width:100px;'>
                    {{--<col style='width:10%;'>--}}
                </colgroup>
                <tbody>
                    @foreach($data->items as $counter => $item)
                        @if(($counter ===  (count($data->items) - 1) || $counter ===  (count($data->items) - 2)))
                            <tr style="border-top:2px #cbcbcb solid;">
                                <td class="center" rowspan="3">{{ ++$counter }}</td>
                                <td rowspan="3">{{ optional($item->item_catalog)->display_name }}</td>
                                <td class="center">{{ $item->qty }}</td>
                                <td class="right">
                                    {{ number_format($item->subtotal_before_discount->toFloat(), 2) }}<br/>
                                </td>
                            </tr>
                            @if($item->discount_flat)
                                <tr>
                                    <td class="center">Discount</td>
                                    <td class="right">
                                        -{{ number_format($item->discount_flat->toFloat(), 2) }}<br/>
                                    </td>
                                </tr>
                                {{--<tr>--}}
                                    {{--<td class="center">Subtotal</td>--}}
                                    {{--<td class="right">{{ number_format($item->subtotal, 2) }}</td>--}}
                                {{--</tr>--}}
                            @else
                                <tr>
                                    <td style="border:none;border-collapse: collapse"></td>
                                    <td style="border:none;border-collapse: collapse"></td>
                                </tr>
                                {{--<tr>--}}
                                    {{--<td style="border:none;border-collapse: collapse"></td>--}}
                                    {{--<td style="border:none;border-collapse: collapse"></td>--}}
                                {{--</tr>--}}
                            @endif
                            <tr>
                                <td class="center">VAT</td>
                                <td class="right">{{ number_format($item->total_taxes_amount->toFloat(), 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3" class="right"><strong>Total after VAT</strong></td>
                        <td class="right"><strong>{{ number_format($data->total->getAmount()->toFloat(), 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
