<template>
    <div>
        <section class="modal-card-body">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="content">
                                <h1>Next steps:</h1>
                                <ul>
                                    <li>New Purchase Order</li>
                                    <li>New Service Item (for repair)</li>
                                    <li>Resolve Work Order by selecting Purchase Order</li>
                                </ul>
                            </div>
                        </div>
                    </div>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-success"
                    @click.prevent="closeModal">Ok</button>
            <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
        </footer>
    </div>
</template>

<script>
    export default {
        mounted() {
            //
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
        }
    }
</script>
