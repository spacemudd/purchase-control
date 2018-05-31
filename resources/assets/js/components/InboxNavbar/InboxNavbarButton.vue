<template>
    <div v-click-outside="closeInbox">
        <button @click="toggleInbox()" class="button has-icon is-badge-left" :class="{'badge is-badge-danger': unreadCounter > 0}" :data-badge="unreadCounter">
            <span class="icon"><i class="fa fa-inbox"></i></span>
        </button>
        <div class="block inbox-container has-text-left" v-if="viewInbox">
            <div class="box">
                <div class="modal-content">
                    <loading-screen v-if="$isLoading('LOADING_INBOX_MESSAGES')"></loading-screen>
                    <ul v-else>
                        <p v-if="messages.length === 0" class="has-text-centered"><i>No messages</i></p>
                        <template v-for="message in messages">
                            <inbox-navbar-item
                                               :item-seen-at="message.seen_at"
                                               :item-link="message.link"
                                               :item-type="message.type"
                                               :item-content="message.content"
                                               :item-created-at="message.created_at">
                            </inbox-navbar-item>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                viewInbox: false,
                messages: [],
                unreadCounter: 0,

                didntLoadMessagesOnce: true,
            }
        },
        mounted() {
            this.getUnreadMessagesCounter();
        },
        methods: {
            getUnreadMessagesCounter() {
                axios.get(this.apiUrl() + '/profile/inbox/get-unread-messages-counts').then(response => {
                    this.unreadCounter = response.data;
                })
            },
            toggleInbox() {
                this.viewInbox = ! this.viewInbox;
                this.$emit('toggled-inbox', this.viewInbox);
                // To lessen the amount of load.
                if(this.didntLoadMessagesOnce) {
                    this.$emit('loading-message');
                    this.getMessages();
                    this.didntLoadMessagesOnce = false;
                }
            },
            getMessages() {
                this.$startLoading('LOADING_INBOX_MESSAGES');
                axios.get(this.apiUrl() + '/profile/inbox').then(response => {
                   this.messages = response.data;
                    this.$endLoading('LOADING_INBOX_MESSAGES');
                })
            },
            clearMessagesCounter() {
                axios.get(this.apiUrl() + '/profile/inbox/clear-unread-messages-counts').then(() => {
                    this.unreadCounter = 0;
                });
            },
            closeInbox(event) {
                if(this.viewInbox) {
                    this.$emit('closed-inbox');
                    this.viewInbox = false;

                    if(this.unreadCounter > 0) {
                        this.clearMessagesCounter();
                    }
                }
            },
        }
    }
</script>
