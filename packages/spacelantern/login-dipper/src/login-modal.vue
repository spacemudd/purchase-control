<template>
    <form :on-submit.prevent="sendLoginRequest">
        <div class="modal-card" style="width:auto;">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $t('words.session-expired') }}</p>
            </header>
            <section class="modal-card-body">
                <p>{{ $t('messages.your-session-expired-please-re-login') }}.</p>
                <!--
                <b-field label="Username">
                    <b-input
                            type="text"
                            v-model="username"
                            required>
                    </b-input>
                </b-field>

                <b-field label="Password">
                    <b-input
                            type="password"
                            v-model="password"
                            required>
                    </b-input>
                </b-field>

                <b-checkbox>{{ $t('auth.remember-me') }}</b-checkbox>
                -->
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary" @click.prevent="sendLoginRequest">{{ $t('auth.login') }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                username: '',
                password: '',
            }
        },
        mounted() {
            //
        },
        methods: {
            sendLoginRequest()
            {
                window.location.href = this.baseUrl() + '/login';
            }
        }
    }
</script>
