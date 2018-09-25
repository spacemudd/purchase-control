<template>
    <a href="#" v-if="sidebarLink" @click="toggleModal">
        <i class="fa fa-building"></i>
        <span><strong>{{ $t('words.department') }}</strong></span>
    </a>
    <button class="button is-primary"
            @click="toggleModal"
            :class="extraClasses" v-else>
        {{ $t('words.new-department') }}
    </button>
</template>

<script>
    export default {
        props: {
            extraClasses: {
                type: String,
                required: false,
            },
            sidebarLink: {
                type: Boolean,
                required: false,
                default: false,
            }
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
            toggleModal() {
                this.$emit('openedModal');
                this.$store.commit('Department/setNewModal', true);
            },
        }
    }
</script>
