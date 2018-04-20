<template>
    <div>
        <div class="cursor"
             v-click-outside="closeList"
            @click="toggleList()">
            <avatar :size.number="35"
                    background-color="#FFFFFF"
                    color="#067cda"
                    :username="username">
            </avatar>
        </div>

        <div id="list" v-if="showList">
            <ul>
                <li>
                    <a class="button"
                       :class="{'is-loading': loggingOutLoading}"
                       @click="logOut()">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
           username: {
               type: String,
               required: true
           }
        },
        data() {
            return {
                showList: false,

                loggingOutLoading: false,
            }
        },
        methods: {
            toggleList() {
                this.showList = ! this.showList;
            },
            closeList() {
                this.showList = false;
            },
            logOut() {
                if(this.loggingOutLoading === false) {

                    this.loggingOutLoading = true;

                    axios.post(this.baseUrl() + '/logout').then(response => {
                        window.location.href = this.baseUrl();
                    }).catch(error => {
                        window.location.href = this.baseUrl();
                    })
                }
            },
        }
    }
</script>

<style lang="sass">
    #list
        z-index: 999999
        background-color: white
        color: black
        position: absolute
        bottom: 20px
        left: 65px
        padding: 20px
        border-radius: 5px
</style>
