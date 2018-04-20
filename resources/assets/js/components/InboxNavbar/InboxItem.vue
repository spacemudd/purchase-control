<template>
    <li class="inbox-item">
        <a :href="itemLink">
            <div class="item-icon">
                <icon :opacity="itemSeenAt ? '0.3' : '1'"></icon>
            </div>
            <div class="item-content">
                <div class="item-header is-size-7">
                    <span class="item-type">{{ itemType }}</span>
                    <span class="item-created-at is-pulled-right">{{ itemCreatedAt }}</span>
                </div>
                <div class="item-summary" v-html="itemContent">
                </div>
            </div>
        </a>
    </li>
</template>

<script>
    export default {
        props: {
            itemLink: {
                type: String,
            },
            itemSeenAt: {
                type: String,
            },
            itemType: {
                type: String,
            },
            itemCreatedAt: {
                type: String,
            },
            itemContent: {
                type: String,
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
            //
        }
    }
</script>

<style lang="sass">
    //
</style>
