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
        <div class="center" style="width: 225px;">
            <img style="width: 150px;" src="{{ asset('img/brand/brand_pdf_logo.png') }}">
        </div>
    </div>
    <div class="col-8-sm" style="text-align: center">
        <h3><span style="border:2px solid black;padding:10px;border-radius: 2px;">PURCHASE ORDER</span></h3>
    </div>
</div>

<div class="row">
    <div class="col-6-sm">
        <strong>Date: {{ optional($data->date)->format('d-m-y') }}</strong>
    </div>
    <div class="col-6-sm" style="text-align: right">
        <strong>Purchase Order Number:</strong> {{ $data->number ? $data->number : 'DRAFT' }}
    </div>
</div>

</body>
</html>
