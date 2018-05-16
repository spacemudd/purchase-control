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
<div style="padding-top:15px;">
    <table class="pure-table pure-table-bordered tight-table">
        <colgroup>
            <col style='width:15%;'>
            <col style='width:35%;'>
            <col style='width:15%;'>
            <col style='width:35%;'>
        </colgroup>
        <thead>
        <tr style="background-color:black;">
            <th colspan="4" style="color:white;">REQUESTER INFORMATION (To be filled by the Requester all fields are mandatory and must be filled)</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" class="center"><strong>REQUESTED BY</strong></td>
                <td colspan="2" class="center"><strong>REQUESTED FOR</strong></td>
            </tr>
            <tr>
                <td><strong>NAME</strong></td>
                <td>{{ $data->requested_by->name }}</td>
                <td><strong>USER NAME</strong></td>
                <td>{{ optional($data->requested_for)->name }}</td>
            </tr>
            <tr>
                <td><strong>EMPLOYEE ID</strong></td>
                <td>{{ $data->requested_by->code }}</td>
                <td><strong>EMPLOYEE ID</strong></td>
                <td>{{ optional($data->requested_for)->code }}</td>
            </tr>
            <tr>
                <td><strong>CONTACT DETAIL</strong></td>
                <td></td>
                <td><strong>CONTACT DETAIL</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>COST CENTER</strong></td>
                <td>{{ $data->cost_center_by->code }}</td>
                <td><strong>COST CENTER</strong></td>
                <td>{{ optional($data->cost_center_for)->code }}</td>
            </tr>
            <tr>
                <td><strong>DEPARTMENT</strong></td>
                <td>{{ $data->cost_center_by->description }}</td>
                <td><strong>DEPARTMENT</strong></td>
                <td>{{ optional($data->cost_center_for)->description }}</td>
            </tr>
            <tr>
                <td><strong>LOCATION</strong></td>
                <td></td>
                <td><strong>LOCATION</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>PROJECT CODE</strong></td>
                <td></td>
                <td><strong>PROJECT NAME</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Items --}}
<div style="padding-top:15px;">
    <table class="pure-table pure-table-bordered tight-table">
        <colgroup>
            <col style='width:1%;'>
            <col style='width:99%;'>
        </colgroup>
        <thead>
            <tr style="background-color:black;">
                <th style="color:white;" class="center">S.NO.</th>
                <th style="color:white;" class="center">ITEM DESCRIPTION</th>
                <th style="color:white;" class="center">QUANTITY</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->simple_items as $counter => $item)
                <tr>
                    <td class="center">{{ ++$counter }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="center">{{ $item->qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Purpose of request --}}
    <table class="pure-table pure-table-bordered tight-table">
        <colgrop>
            <col style="width:15%;">
        </colgrop>
        <tbody>
        <tr>
            <td><b>Purpose of Request</b></td>
            <td>{{ $data->purpose }}</td>
        </tr>
        </tbody>
    </table>

    {{-- Signatures of requests --}}
    <table class="pure-table tight-table" style="margin-top:20px;">
        <colgroupd>
            <col style="width:33%;">
            <col style="width:33%;">
        </colgroupd>
    	<tbody>
            <tr>
                <td class="narrow"><strong>REQUESTED BY<br>NAME</strong></td>
                <td class="narrow"><strong>RECOMMENDED BY<br>NAME</strong></td>
                <td class="narrow"><strong>APPROVED BY</strong> (Having Financial Authority)<br><strong>NAME</strong></td>
            </tr>
            <tr style="height:80px;">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>SIGNATURE</strong></td>
                <td><strong>SIGNATURE</strong></td>
                <td><strong>SIGNATURE</strong></td>
            </tr>
    	</tbody>
    </table>
</div>

<div style="padding-top:15px;">
    <table class="pure-table pure-table-bordered tight-table">
        <colgroup>
            <col style='width:1%;'>
            <col style="width:99%">
        </colgroup>
        <thead>
        <tr style="background-color:black;">
            <th style="color:white;" class="center">S.NO.</th>
            <th style="color:white;" class="center">TO BE FILLED ONLY BY IT ASSETS MANAGEMENT SECTION <br/> ITEM DESCRIPTION</th>
            <th style="color:white;" class="center">QUANTITY</th>
        </tr>
        </thead>
        <tbody>
            @foreach($data->items as $counter => $item)
                <tr>
                    <td class="center">{{ ++$counter }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="center">{{ $item->qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Remarks if any --}}
    <table class="pure-table pure-table-bordered tight-table">
        <colgrop>
            <col style="width:15%;">
        </colgrop>
        <tbody>
        <tr>
            <td><b>Remarks (if any)</b></td>
            <td></td>
        </tr>
        </tbody>
    </table>

    {{-- Signatures of requests --}}
    <table class="pure-table tight-table" style="margin-top:20px;">
        <colgroupd>
            <col style="width:50%;">
        </colgroupd>
        <tbody>
        <tr>
            <td class="narrow"><strong>CHECKED AND REGISTERED BY</strong></td>
            <td class="narrow"><strong>VERIFIED AND APPROVED BY</strong> (Head of IT Assets Management)</td>
        </tr>
        <tr style="height:50px;">
            <td></td><td></td>
        </tr>
        <tr>
            <td><strong>SIGNATURE</strong></td>
            <td><strong>SIGNATURE</strong></td>
        </tr>
        </tbody>
    </table>
</div>

<div style="padding-top:15px;">
    <table class="pure-table tight-table">
        <colgroupd>
            <col style="width:50%;">
        </colgroupd>
        <thead>
            <tr style="background-color:black;">
                <th style="color:white;" colspan="2">TO BE USED BY PREMISES &amp; ADMIN ONLY.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="narrow"><strong>Purchase Order Prepared By</strong></td>
                <td class="narrow"><strong>IT Purchasing Head</strong></td>
            </tr>
                <tr style="height:50px;">
                    <td></td>
                    <td></td>
                </tr>
            <tr>
                <td><strong>SIGNATURE</strong></td>
                <td><strong>SIGNATURE</strong></td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
