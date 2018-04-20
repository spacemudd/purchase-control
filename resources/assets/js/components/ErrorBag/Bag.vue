<template>
    <article class="message is-danger">
        <div class="message-body">
            <!--<p>{{ errorBag.message }}</p>-->
            <ul>
                <li v-for="error in errorBag.errors">
                    {{ error[0] }}
                </li>
            </ul>
        </div>
    </article>
</template>

<script>
    export default {
        props: {
            errorBag: {
                type: Object,
                default: [],
            }
        },
    }
</script>
