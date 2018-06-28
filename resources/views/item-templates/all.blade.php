@extends('layouts.app', [
	'title' => 'All - Item Catalog'
])

@section('header')
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li>
                <a href="{{ route('dashboard.index') }}" aria-current="page">
                    <span class="icon is-small"><i class="fa fa-inbox"></i></span>
                    <span>{{ trans('words.dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('item-templates.index') }}">
                    <span class="icon is-small"><i class="fa fa-tags"></i></span>
                    <span>{{ trans('words.item-catalog') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    All
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title is-5">All</h1>
                <h2 class="subtitle is-7">Item Catalog</h2>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column is-12">
            <table class="table is-fullwidth is-narrow is-size-7">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Model Number</th>
                    <th>Manufacturer</th>
                    <th class="has-text-right">Price</th>
                    <th class="has-text-right">Updated at</th>
                    <th class="has-text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($itemTemplates->total() == 0)
                    <tr>
                        <td class="has-text-centered" style="line-height:50px;" colspan="6">No data to display</td>
                    </tr>
                @endif
                @foreach($itemTemplates as $itemTemplate)
                    <tr>
                        <td>{{ $itemTemplate->name }}</td>
                        <td>{{ $itemTemplate->code }}</td>
                        <td>{{ optional($itemTemplate->category)->name }}</td>
                        <td>{{ $itemTemplate->model_number }}</td>
                        <td>{{ optional($itemTemplate->manufacturer)->display_name }}</td>
                        <td class="has-text-right">{{ $itemTemplate->unit_price }}</td>
                        <td class="has-text-right">{{ optional($itemTemplate->updated_at)->format('d-m-Y') }}</td>
                        <td class="has-text-right">
                            <a href="{{ route('item-templates.show', ['id' => $itemTemplate->id]) }}" class="button is-small has-icon">
                                <span class="icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <span>View</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $itemTemplates->links('vendor.pagination.bulma') }}
        </div>
    </div>
@endsection
