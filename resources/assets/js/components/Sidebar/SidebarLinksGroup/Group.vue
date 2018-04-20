<template>
    <div class="sidebar-group">
        <div class="group-link">
            <a :href="link">
                <span class="icon"><i class="fa" :class="'fa-' + icon"></i></span>
                <span>{{ title }}</span>
            </a>

            <div class="expand-button" @click="toggleExpand" v-if="hasSubLinks">
                <span v-if="expanded">-</span>
                <span v-else>+</span>
            </div>
        </div>

        <template v-if="hasSubLinks">
            <div class="sidebar-subgroup" v-if="expanded">
                <slot>

                </slot>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            /** Laravel's toHTML transforms Booleans to 0 or 1. **/
            selected: {

            },
            title: {
                type: String,
                required: true,
            },
            icon: {
                type: String,
                required: false,
            },
            link: {
                type: String,
            },
            iconPack: {
                type: String,
                default: 'fa',
            }
        },
        data() {
            return {
                expanded: false,
            }
        },
        mounted() {
            if(this.hasSubLinks) {
                let expandedCookie = (this.$cookie.get('expanded-' + this.title) == 'true');

                if(expandedCookie) {
                    this.expanded = expandedCookie;
                } else {
                    this.$cookie.set('expanded-' + this.title, false);
                }
            }
        },
        computed: {
            hasSubLinks() {
                return !! this.$slots.default;
            },
        },
        methods: {
            toggleExpand() {
                this.expanded = ! this.expanded;
                this.$cookie.set('expanded-' + this.title, this.expanded);
            }
        }
    }
</script>
