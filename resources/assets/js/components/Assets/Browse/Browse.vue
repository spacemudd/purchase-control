<template>
    <section class="section">
        <div class="columns">
            <div class="column is-6">
                <simple-search
                        :placeholder-text="$t('words.find-assets')"
                        :hyper-linked-results="true"
                        search-endpoint="all-assets">
                </simple-search>
            </div>
            <div class="column is-6">
                <a :href="baseUrl() + '/settings/assets/create'" class="btn btn-lg btn-primary pull-right">{{ $t('words.create') }}</a>
            </div>
        </div>
    </section>
</template>
