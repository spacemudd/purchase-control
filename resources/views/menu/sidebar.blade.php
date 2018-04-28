<div id="sidebar-navigation">
    <div class="sidebar-wrapper" style="width: 304px;">
        <div class="sidebar-inner-wrapper">
            <div class="sidebar-small-wrapper">
                <div class="sidebar-small">
                    <div class="top-container">
                        <div class="nav-content">
                            <div class="main-item">
                                <a href="/" class="item">
                                    <span>
                                        <img src="{{ asset('img/brand/sidebar_logo.svg') }}">
                                    </span>
                                </a>
                            </div>
                            <div class="rest-of-items">
                                {{--
                                <div class="item">
                                    <button class="button-wrapper">
                                        <span>
                                            <svg width="24" height="24" role="img" label="Search" style="height: 24px; max-height: 100%; max-width: 100%; overflow: hidden; vertical-align: bottom; width: 24px;"><title id="title-q4ujxbv">Search</title><path d="M16.436 15.085l3.94 4.01a1 1 0 0 1-1.425 1.402l-3.938-4.006a7.5 7.5 0 1 1 1.423-1.406zM10.5 16a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11z" fill="currentColor" fill-rule="evenodd" role="presentation"></path></svg>
                                        </span>
                                    </button>
                                </div>
                                --}}
                                <div class="item">
                                    <button class="button-wrapper" @click="showNewResourceSidebar">
                                        <span>
                                            <svg width="24" height="24" role="img" label="Create" style="height: 24px; max-height: 100%; max-width: 100%; overflow: hidden; vertical-align: bottom; width: 24px;"><title id="title-jbxu8gy">Create</title><path d="M13 11V3.993A.997.997 0 0 0 12 3c-.556 0-1 .445-1 .993V11H3.993A.997.997 0 0 0 3 12c0 .557.445 1 .993 1H11v7.007c0 .548.448.993 1 .993.556 0 1-.445 1-.993V13h7.007A.997.997 0 0 0 21 12c0-.556-.445-1-.993-1H13z" fill="currentColor" fill-rule="evenodd" role="presentation"></path></svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-container">
                        {{--
                        <a class='button is-primary' href="{{ route('reports.index') }}">
                            <span class="icon"><i class="fa fa-bar-chart"></i></span>
                        </a>
                        <button class='button is-primary' @click="showSettingsSidebar">
                            <span class="icon"><i class="fa fa-cog"></i></span>
                        </button>
                        --}}

                        <personal-user-list username="{{ auth()->user()->name }}">
                        </personal-user-list>
                    </div>
                </div>

                <div class="sidebar-big">
                    <div class="wrapper">
                        <div class="logo">
                            <div class="logo-image">
                                <span>ANB - Purchase Department</span>
                            </div>
                        </div>
                        <div class="content-wrapper">

                            <div class="content">
                                <div class="items">
                                    <sidebar-links-group selected="{{ (request()->route()->getName() === 'dashboard.index') }}"
                                                         icon="inbox"
                                                         title="{{ __('words.dashboard') }}"
                                                         link="{{ route('dashboard.index') }}">
                                    </sidebar-links-group>

                                    {{--
                                    <sidebar-links-group selected="{{ (request()->route()->getName() === 'search.index') }}"
                                                         icon="search"
                                                         title="{{ __('words.search') }}"
                                                         link="{{ route('search.index') }}">
                                    </sidebar-links-group>
                                    --}}

                                    <sidebar-links-group selected="{{ (request()->route()->getName() === 'items.index') }}"
                                                         icon="barcode"
                                                         title="{{ __('words.items') }}"
                                                         link="{{ route('items.index') }}">
                                    </sidebar-links-group>

                                    <sidebar-links-group selected="{{ (request()->route()->getName() === 'purchase-requisitions.index') }}"
                                                         icon="file"
                                                         title="{{ __('words.purchase-requisitions') }}"
                                                         link="{{ route('purchase-requisitions.index') }}">
                                    </sidebar-links-group>

                                    @can('view-purchase-orders')
                                        <sidebar-links-group selected="{{ (request()->route()->getName() === 'purchase-orders.index') }}"
                                                             icon="shopping-cart"
                                                             title="{{ __('words.purchase-orders') }}"
                                                             link="{{ route('purchase-orders.index') }}">
                                        </sidebar-links-group>
                                    @endcan

                                    {{--
                                    <sidebar-links-group selected="{{ (request()->route()->getName() === 'reports.index') }}"
                                                         icon="bar-chart"
                                                         title="{{ __('words.report') }}"
                                                         link="{{ route('reports.index') }}">
                                    </sidebar-links-group>
                                    --}}

                                    @can('view-employees')
                                    <sidebar-links-group link="{{ route('employees.index') }}"
                                                         icon="users"
                                                         title="{{ __('words.employees') }}">
                                    </sidebar-links-group>
                                    @endcan

                                    @can('view-departments')
                                    <sidebar-links-group link="{{ route('departments.index') }}"
                                                         icon="building"
                                                         title="{{ __('words.departments') }}">
                                    </sidebar-links-group>
                                    @endcan

                                    @can('view-vendor')
                                    <sidebar-links-group link="{{ route('vendors.index') }}"
                                                         icon="truck"
                                                         title="{{ __('words.vendors') }}">
                                    </sidebar-links-group>
                                    @endcan

                                    @can('view-manufacturers')
                                        <sidebar-links-group link="{{ route('manufacturers.index') }}"
                                                             icon="industry"
                                                             title="{{ __('words.manufacturers') }}">
                                        </sidebar-links-group>
                                    @endcan

                                    <sidebar-links-group icon="cog"
                                                         title="{{ __('words.settings') }}">

                                        {{--@can('view-categories')--}}
                                            {{--<sidebar-links-group link="{{ route('categories.index') }}"--}}
                                                                 {{--icon="bookmark" title="{{ __('words.categories') }}">--}}
                                            {{--</sidebar-links-group>--}}
                                        {{--@endcan--}}

                                        @can('view-item-templates')
                                            <sidebar-links-group link="{{ route('item-templates.index') }}"
                                                                 icon="file-text-o" title="{{ __('words.item-templates') }}">
                                            </sidebar-links-group>
                                        @endcan

                                        @can('show-users')
                                            <sidebar-links-group link="{{ route('users.index') }}"
                                                                 icon="user"
                                                                 title="{{ __('words.users') }}">
                                            </sidebar-links-group>
                                        @endcan

                                        @can('edit-roles')
                                            <sidebar-links-group link="{{ route('roles.index') }}"
                                                                 icon="superpowers"
                                                                 title="{{ __('words.roles') }}">
                                            </sidebar-links-group>
                                        @endcan
                                    </sidebar-links-group>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<new-resource-sidebar></new-resource-sidebar>
<settings-sidebar-menu></settings-sidebar-menu>
