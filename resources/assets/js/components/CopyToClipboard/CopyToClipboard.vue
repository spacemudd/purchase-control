<template>
    <span @click="copy" ref="toCopy" :data-clipboard-text="text">
        <button class="button is-text">{{ buttonText }}</button>
    </span>
</template>

<script>
    import clipboard from 'clipboard';

    export default {
        props: {
            text: {
                type: String,
                required: true,
            },
            buttonText: {
                type: String,
                default: 'Copy',
            },
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
        methods: {
            copy() {
                new clipboard(this.$refs.toCopy, {
                    text: function(trigger) {
                        return trigger.getAttribute('data-clipboard-text');
                    }
                });
            },
        }
    }
</script>
