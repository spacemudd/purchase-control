<template>
    <div>
        <button class="btn btn-primary"
            @click="toggleSelection()">
            <i class="fa fa-circle-o-notch fa-spin fa-fw"
                v-show="isLoading"></i>
            {{ $t('words.export') + ' PDF' }}
        </button>

        <div class="block"
        v-if="showSelection">
            ...
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isLoading: false,
                showSelection: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            toggleSelection() {
                this.showSelection = ! this.showSelection;
            }
        }
    }
</script>

<style lang="sass">
    //
</style>
