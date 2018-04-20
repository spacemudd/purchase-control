<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <h1 class="title is-3 has-text-success has-text-centered">{{ $t('words.success') }}</h1>
            <table class="table is-fullwidth is-hoverable">
                <thead><tr><td style="width: 50%;"></td><td></td></tr></thead>
                <tbody>
                <tr>
                    <td>{{ $t('words.manufacturer-code') }}</td>
                    <td>{{ manufacturer.code }}</td>
                </tr>
                <tr>
                    <td>{{ $t('words.description') }}</td>
                    <td>{{ manufacturer.description }}</td>
                </tr>
                <tr>
                    <td>{{ $t('words.system-generated-date') }}</td>
                    <td>{{ manufacturer.created_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="column is-12 has-text-centered">
            <div class="field is-grouped is-grouped-centered">
                <p class="control">
                    <a :href="this.baseUrl() + '/settings/manufacturers/' + manufacturer.id" class="button is-primary">
                        <strong>{{ manufacturer.code }}</strong>
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            manufacturer: {
                required: true,
            }
        },
    }
</script>
