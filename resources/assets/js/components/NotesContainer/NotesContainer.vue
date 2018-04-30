<template>
    <div>
        <div class="resources-sidebar">
            <h3 class="title is-4">{{ $t('words.notes') }}</h3>
            <div>
                <button class="button" @click="noteBox=true">{{ $t('words.new-note') }}</button>
            </div>
        </div>

        <!-- Notes form -->
        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
            <div v-if="noteBox">

                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>Something went wrong.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <!-- Create note form -->
                <form @submit.prevent="save">
                    <div class="field">
                        <b-input type="textarea" v-model="form.body" maxlength="225" has-counter required autofocus></b-input>
                    </div>
                    <div class="field">
                        <div class="control has-text-right">
                            <button type="button" class="button is-text is-small" @click="noteBox=false">Close</button>
                            <button type="submit"
                                    class="button is-primary is-small"
                                    :class="{'is-loading': $isLoading('SAVING_NOTE')}">
                                Save Note
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </transition>

         <!-- Where the notes are displayed -->
        <div class="notes-container">
            <transition-group name="notes-container" enter-active-class="animated fadeInDown" leave-active-class="animated fadeOutUp" mode="in-out" v-if="notes.length > 0">
                <div v-bind:key="note.id" class="media-content" v-for="note in notes">
                    <div class="content">
                        <p>
                            {{ note.body }}
                            <br>
                            <small>
                                <strong :title="note.user.username">
                                    {{ note.user.name }}
                                </strong>
                                &#8226;
                                <time :title="note.created_rss"
                                      :datetime="note.created_w3c"
                                      class="has-text-grey-light">
                                    {{ note.created_at_human }}
                                </time>
                            </small>
                        </p>
                        <hr>
                    </div>
                </div>
            </transition-group>
            <div style="min-height: 100px;" class="is-flex columns is-vcentered" v-else>
                <p class="column has-text-centered"><i>No notes</i></p>
            </div>
        </div>
    </div>
</template>

<style>
    .notes-container-move {
        transition: transform 1s;
    }
</style>

<script>
    export default {
        props: {
            /**
             * The ID of the notable resource.
             */
            resourceId: {
                type: {
                    type: Number,
                    required: false,
                }
            },
            /**
             * The same GET/POST routes to save notes for a given resource.Damn
             */
            url: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                notes: [],

                noteBox: false,

                form: {
                    id: null,
                    body: '',
                    errors: [],
                },
            }
        },
        mounted() {
            this.getNotes();
        },
        methods: {
            /**
             * Get all of the notes for resource.
             */
            getNotes() {
                axios.get(this.url)
                    .then(response => {
                        this.notes = response.data.reverse();
                    });
            },
            clearBoxNote() {
                this.form.body = '';
                // this.noteBox = false;
            },
            /**
             * Save a new note.
             */
            save() {
                this.form.errors = [];
                this.form.id = this.resourceId;

                this.$startLoading('SAVING_NOTE');

                axios.post(this.url, this.form)
                    .then(response => {
                        this.form.errors = [];
                        this.form.body = '';

                        response.data['new'] = true;

                        let originalNotes = this.notes.reverse();
                        originalNotes.push(response.data);
                        this.notes = originalNotes.reverse();

                        this.$endLoading('SAVING_NOTE');

                        this.clearBoxNote();
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_NOTE');

                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },
        }
    }
</script>
