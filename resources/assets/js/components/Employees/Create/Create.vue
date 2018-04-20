<template>
    <a href="#" v-if="sidebarLink" @click="toggleNewEmployeeModal">
        <i class="fa fa-user"></i>
        <span><strong>{{ $t('words.employee') }}</strong></span>
    </a>
    <button class="button is-primary"
            @click="toggleNewEmployeeModal"
            :class="extraClasses" v-else>
        {{ $t('words.new-employee') }}
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
            toggleNewEmployeeModal() {
                this.$emit('openedModal');
                this.$store.commit('Employee/setNewEmployeeModal', true);
            },
        }
    }
</script>
