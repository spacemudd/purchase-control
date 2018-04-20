{{-- TODO: Remove all of this section or somehow hide it from the view? --}}
<create-employee-modal></create-employee-modal>
<new-purchase-order></new-purchase-order>
<new-vendor></new-vendor>
<new-manufacturer></new-manufacturer>
<login-dipper :timeout.number="{{ config('session.lifetime', 120) }}"></login-dipper>
