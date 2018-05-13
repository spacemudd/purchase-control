<template>
    <form @submit.prevent="save">
        <div class="modal-card" style="width: auto;height:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">New PO Item</p>
            </header>
            <section class="modal-card-body">

                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>An error occurred.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    errors: [],
                }
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
