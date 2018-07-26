{{-- TODO: Remove all of this section or somehow hide it from the view? --}}
<create-employee-modal></create-employee-modal>
<new-purchase-order></new-purchase-order>
<new-vendor></new-vendor>
<new-manufacturer></new-manufacturer>
<login-dipper :timeout.number="{{ config('session.lifetime', 120) }}"></login-dipper>
<new-po-item-modal></new-po-item-modal>
@can('create-item-templates')
    <item-catalog-create-modal action="{{ route('api.item-templates.store') }}"
                                :categories="{{ \App\Models\Category::get()->toTree() }}"
                                :manufacturers="{{ \App\Models\Manufacturer::get() }}"
    ></item-catalog-create-modal>
@endcan
