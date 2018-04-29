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
            <col style='width:80%;'>
        </colgroup>
        <thead>
        <tr style="background-color:black;">
            <th style="color:white;" class="center">S.NO</th>
            <th style="color:white;" class="center">ITEM DESCRIPTION</th>
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
</div>

</body>
</html>
