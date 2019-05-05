{{-- TODO: Remove all of this section or somehow hide it from the view? --}}
<create-department-modal></create-department-modal>
<create-employee-modal></create-employee-modal>
<new-purchase-order></new-purchase-order>
<new-vendor></new-vendor>
<new-manufacturer></new-manufacturer>
<projects-create-modal></projects-create-modal>
<login-dipper :timeout.number="{{ config('session.lifetime', 120) }}"></login-dipper>
<new-po-item-modal></new-po-item-modal>
@can('create-item-templates')
    <item-catalog-create-modal action="{{ route('api.item-templates.store') }}"
                                :categories="{{ \App\Models\Category::get()->toTree() }}"
                                :manufacturers="{{ \App\Models\Manufacturer::get() }}"
    ></item-catalog-create-modal>
@endcan
