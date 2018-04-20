<template>
    <li>
        <a :href="link">
            <span>{{ title }}</span>
        </a>
    </li>
</template>

<script>
    export default {
        props: {
            link: {
                type: String,
                required: true,
            },
            icon: {
                type: String,
                required: false,
            },
            title: {
                type: String,
                required: true,
            }
        },
    }
</script>
