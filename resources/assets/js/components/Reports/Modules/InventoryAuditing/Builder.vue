<template>
    <div>
        <div class="block">
            <div class="panel">
                <div class="panel-heading">{{ $t('words.inventory-auditing') }}</div>
                <div class="panel-block">
                    <button class="button is-primary"
                            @click="viewModal = true">
                        {{ $t('words.build') }}
                    </button>

                    <b-modal :active.sync="viewModal">
                        <builder-modal @close="viewModal = false"></builder-modal>
                    </b-modal>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BuilderModal from './Modal.vue';

    export default {
        components: {
            BuilderModal,
        },
        data() {
            return {
                viewModal: false,
            }
        },
        mounted() {

        },
        methods: {

        }
    }
</script>
