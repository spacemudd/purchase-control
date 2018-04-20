<template>
    <form class="form-horizontal">
        <template v-if="!completed">
            <template v-if="!waitingForCode">
                <div class="form-group">
                    <label for="mobile_number" class="col-md-4 control-label">{{ $t('words.mobile-number') }}</label>

                    <div class="col-md-6">
                        <input id="mobile_number" class="form-control" name="mobile_number"
                               v-model="mobileNumberRequest" required>

                        <span class="help-block danger">
                            <strong>{{ errorMessage }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button class="btn btn-primary"
                                :class="{ 'disabled is-loading' : isLoading }"
                                :disable="isLoading"
                                @click.prevent="startVerification()">
                            {{ $t('auth.start-verification-process') }}
                        </button>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="form-group">
                    <label for="sms-code" class="col-md-4 control-label">{{ $t('auth.sms-code') }}</label>

                    <div class="col-md-6">
                        <input id="sms-code" class="form-control" name="sms-code"
                               v-model="smsCode" required>

                        <span class="help-block danger">
                            <strong>{{ errorMessage }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button class="btn btn-primary"
                                :class="{ 'disabled is-loading' : isLoading }"
                                :disable="isLoading"
                                @click.prevent="checkCode()">
                            {{ $t('words.send') }}
                        </button>
                    </div>
                </div>
            </template>
        </template>


        <div v-show="completed">
            <div class="block text-center">
                <img :src="this.baseUrl() + '/img/assets/lighthouse.svg'" class="lighthouse">
            </div>

            <div class="block text-center">
                <a :href="baseUrl()" class="btn btn-primary">{{ $t('words.dashboard') }}</a>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                isLoading: false,
                requestId: '',
                waitingForCode: false,
                smsCode: '',
                errorMessage: '',
                mobileNumberRequest: '',
                completed: false,
            }
        },
        props: {
          mobileNumber: {
              required: true,
              type: String,
          }
        },
        mounted() {
            this.mobileNumberRequest = this.mobileNumber;
        },
        methods: {
            /**
             * Starts the SMS verification process.
             * Returns the request ID for the Nexmo service.
             *
             */
            startVerification() {
                this.isLoading = true;

                if(!this.mobileNumberRequest) {
                    this.errorMessage = this.getTrans('messages.all-fields-are-required');
                    this.isLoading = false;
                    return false;
                }

                axios.post(this.apiUrl() + '/verify/mobile-number', {
                    mobile_number: this.mobileNumberRequest + '', // To send it as string, laral/axios request appends .0 if int?
                }).then(response => {
                    console.log(response.data);
                    this.requestId = response.data;
                    this.isLoading = false;
                    this.waitingForCode = true;
                    this.errorMessage = '';
                }).catch(error => {
                    console.log(error.response.data);
                    this.errorMessage = "Error occurred. Please check your phone number again";
                    this.isLoading = false;
                });
            },
            checkCode() {
                this.isLoading = true;

                if(!this.smsCode) {
                    this.errorMessage = this.getTrans('messages.all-fields-are-required');
                    this.isLoading = false;
                    return false;
                }

                axios.post(this.apiUrl() + '/verify/mobile-number/check', {
                    request_id: this.requestId,
                    sms_code: this.smsCode,
                }).then(response => {
                    console.log(response.data);
                    this.isLoading = false;
                    this.completed = true;
                }).catch(error => {
                    console.log(error.response.data.message);
                    this.errorMessage = this.getTrans('messages.wrong-code');
                    this.isLoading = false;
                });

                this.errorMessage = '';
            }
        }
    }
</script>

<style lang="sass">
    .lighthouse
        width: 250px
        margin-bottom: 50px
</style>
