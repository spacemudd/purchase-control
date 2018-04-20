<template>
    <div id="location-codes-page">
        <div class="level">
            <div class="level-left">
                <simple-search
                        placeholder-text="Find locations"
                        :hyper-linked-results="true"
                        search-endpoint="locations">
                </simple-search>
            </div>
            <div class="level-right">
                <button class="button is-primary pull-right" @click="showNewLocationCodeModal()">
                    {{ $t('words.new-location') }}
                </button>

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                    <new-location-code v-if="showNewLocationModal" @closed-modal="closeNewLocationCodeModal()"></new-location-code>
                </transition>

            </div>
        </div>
    </div>
</template>

<script>
    import NewLocationCode from './Create/Create.vue';

    export default {
        components: {
            NewLocationCode,
        },
        data() {
            return {
                showNewLocationModal: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            showNewLocationCodeModal() {
                this.showNewLocationModal = true;
            },
            closeNewLocationCodeModal() {
                this.showNewLocationModal = false;
            }
        }
    }
</script>

<style lang="sass">
    //
</style>
