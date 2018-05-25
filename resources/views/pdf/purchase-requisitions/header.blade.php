<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @include('pdf.style')
    </style>
</head>
<body>

<div class="row big-font">
    <div class="col-2-sm">
        <div class="center" style="width: 80px; height: 50px;">
            <img style="width: 80px;" src="{{ asset('img/brand/brand_pdf_logo.png') }}">
        </div>
    </div>
    <div class="col-7-sm">
        <strong>
            ITG - IT Asset Management<br/>
            HARDWARE / SOFTWARE REQUISITION FORM
        </strong>
    </div>
</div>

<table class="table tight-table">
<colgroup>
    <col style='width:50%'>
</colgroup>
<thead>
<tr style="background-color:black;color:white">
	{{--<th style="color:white;">TO BE USED BY PREMISES &amp; ADMIN ONLY.</th>--}}
    {{--<th style="color:white;">TO BE FILLED ONLY BY I.T. ASSETS MANAGEMENT SECTION</th>--}}
</tr>
</thead>
	<tbody>
			<tr>
                {{--
				<td>
                    {{-- PMA section --
                    <table class="pure-table pure-table-bordered">
                    <thead>
                    </thead>
                    	<tbody>
                            <tr>
                                <td colspan="4"><strong>Received on:</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Accepted</strong></td>
                                <td></td>
                                <td><strong>Rejected</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>PO NUMBER</strong></td>
                                <td colspan="2"></td>
                            </tr>
                    	</tbody>
                    </table>
                </td>
                --}}
                <td>

                    {{-- ITAM section --}}
                    <table class="pure-table pure-table-bordered">
                        <colgroup>
                            <col style="width:20%;">
                        </colgroup>
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2"><strong>Date</strong></td>
                            <td colspan="2">{{ $data->created_at->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>IT Reference No.</strong></td>
                            <td colspan="2">{{ $data->number }}</td>
                        </tr>
                        </tbody>
                    </table>

                </td>
			</tr>
	</tbody>
</table>

</body>
</html>
