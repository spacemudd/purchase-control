<template>
    <div>
        <transition name="awesome-fade">
            <div v-if="show">
                <div width="narrow" class="navigation">
                    <div class="sidebar-narrow-wrapper">
                        <div class="top-container">
                            <span>
                                <!--<svg width="24" height="24" viewBox="0 0 24 24" focusable="false" role="img" label="ANB" aria-labelledby="title-3b4hxol" style="height: 32px max-height: 100% max-width: 100% overflow: hidden vertical-align: bottom width: 32px"><title id="title-3b4hxol">ANB</title><defs><linearGradient x1="97.526%" y1="25.488%" x2="46.927%" y2="78.776%" id="bitbucket-a"><stop offset="0%" stop-color="#172B4D" stop-opacity=".4"></stop><stop offset="100%" stop-color="currentColor" stop-opacity="0"></stop></linearGradient></defs><path d="M3.844 4a.563.563 0 0 0-.563.651L5.67 19.12a.765.765 0 0 0 .748.637h11.456a.562.562 0 0 0 .562-.472l2.388-14.63A.56.56 0 0 0 20.269 4H3.844zm10.054 10.456h-3.656l-.99-5.16h5.532l-.886 5.16z" fill="currentColor" role="presentation"></path><path d="M17.063 5.297h-5.279l-.886 5.16H7.242l-4.317 5.116a.763.763 0 0 0 .492.186h11.458a.562.562 0 0 0 .563-.472l1.625-9.99z" fill="url(#bitbucket-a)" transform="translate(3 4)" role="presentation"></path></svg>-->
                                <svg version="1.1" width="24" height="24" x="0px" y="0px"
                                 viewBox="399.8 -42 620.3 661.4" xml:space="preserve">
                                    <g transform="translate(0.000000,888.000000) scale(0.100000,-0.100000)">
                                        <path class="st0" fill="#2fabef" d="M6622,9223c-131-206-296-516-631-1183c-283-563-646-1310-951-1955c-21-44-61-129-90-190
                                            c-102-214-140-294-332-705c-246-524-616-1321-620-1336c-2-7,53-91,121-189l124-176l32,73c18,40,66,147,107,238s148,329,238,530
                                            l163,365l116,102c581,512,1254,933,1983,1242c179,75,518,205,523,199c3-3,759-1628,860-1848c29-63,68-149,88-190
                                            c19-41,108-232,197-425c89-192,176-379,193-415c16-36,93-200,169-365s143-304,147-308c10-10,216,123,356,228
                                            c317,239,592,508,741,725l45,66l-132,284c-73,157-372,804-665,1439c-294,635-539,1158-545,1161c-6,4,4,10,23,13
                                            c140,25,530,66,652,68c66,2,63,3-39,11c-60,5-236,7-390,5l-280-5l-604,1309l-605,1309h-472h-472L6622,9223z M6891,7350
                                            c198-426,368-792,378-812c10-21,16-38,14-38s-77-16-166-35c-494-108-980-255-1397-423c-155-62-321-136-376-166l-42-23l23,51
                                            c363,813,978,2173,985,2180c11,11,176,46,203,43C6527,8125,6619,7934,6891,7350z"/>
                                    </g>
                                    <g transform="translate(0.000000,199.000000) scale(0.100000,-0.100000)">
                                        <path fill="#E04248" class="st1" d="M8765-207c-1050-49-2123-272-3019-627c-142-57-423-180-433-190c-8-8-520-1156-516-1156c2,0,75,61,163,136
                                            c629,539,1323,956,2119,1270c733,290,1614,495,2351,546c171,12,180,14,115,20C9483-202,8894-201,8765-207z"/>
                                    </g>
                                </svg>
                            </span>
                        </div>

                        <div class="bottom-container">
                            <div class="go-back-container">
                                <button @click="closeSidebar()">
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 24 24" focusable="false" role="img" label="Close" aria-labelledby="title-beqfob5" style="height: 24px; max-height: 100%; max-width: 100%; overflow: hidden; vertical-align: bottom; width: 24px;"><title id="title-beqfob5">Close</title><path d="M8.414 11.5H18a1 1 0 0 1 0 2H8.414l3.793 3.793a1 1 0 0 1-1.414 1.414l-5.5-5.5a1 1 0 0 1 0-1.414l5.5-5.5a1 1 0 0 1 1.414 1.414L8.414 11.5z" fill="currentColor" fill-rule="evenodd" role="presentation"></path></svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-wide-wrapper">
                        <div class="logo">
                            <span>Arab National Bank</span>
                        </div>

                        <div class="sidebar-content">
                            <div>
                                <div class="small-title">
                                    <span class="tag is-danger">Settings</span>
                                </div>
                                <a :href="this.baseUrl() + '/locations'">
                                    <i class="fa fa-map-marker"></i>
                                    <span><strong>Locations</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/asset-templates'">
                                    <i class="fa fa-bars"></i>
                                    <span><strong>Asset Templates</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/asset-issuances'">
                                    <i class="fa fa-file-text"></i>
                                    <span><strong>Asset Issuances</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/departments'">
                                    <i class="fa fa-building"></i>
                                    <span><strong>Departments</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/department-groups'">
                                    <i class="fa fa-object-group"></i>
                                    <span><strong>Department Groups</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/employees'">
                                    <i class="fa fa-user"></i>
                                    <span><strong>{{ $t('words.employees') }}</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/vendors'">
                                    <i class="fa fa-truck"></i>
                                    <span><strong>{{ $t('words.vendors') }}</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/manufacturers'">
                                    <i class="fa fa-cubes"></i>
                                    <span><strong>{{ $t('words.manufacturers') }}</strong></span>
                                </a>
                            </div>

                            <div style="margin-top: 30px;">
                                <div class="small-title">
                                    <span class="tag is-danger">{{ $t('words.security-settings') }}</span>
                                </div>
                                <a :href="this.baseUrl() + '/settings/active-directory'">
                                    <i class="fa fa-id-card"></i>
                                    <span><strong>{{ $t('words.active-directory') }}</strong></span>
                                </a>
                                <a :href="this.baseUrl() + '/settings/roles'">
                                    <i class="fa fa-user-circle-o"></i>
                                    <span><strong>{{ $t('words.roles') }}</strong></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        computed: {
            show() {
              return this.$store.getters['ResourceSidebar/showSettingsSidebar'];
            }
        },
        mounted() {

        },
        methods: {
            closeSidebar() {
                this.$store.commit('ResourceSidebar/setShowSettings', false);
            }
        }
    }
</script>

<style lang="sass" scoped>
    .overlay
        background: rgba(9, 30, 66, 0.54)
        bottom: 0
        left: 0
        opacity: 1
        pointer-events: initial
        position: fixed
        right: 0
        top: 0
        transition: opacity 220ms
        -webkit-transition: opacity 220ms
        z-index: 10

    .navigation
        background-color: #FFFFFF
        color: #42526E
        display: -webkit-box
        display: -moz-box
        display: -ms-flexbox
        display: -webkit-flex
        display: flex
        height: 100%
        left: 0
        overflow: hidden
        position: fixed
        top: 0
        transform: translateX(0)
        -ms-transform: translateX(0)
        -webkit-transform: translateX(0)
        transition: -ms-transform 220ms cubic-bezier(0.15, 1, 0.3, 1),-webkit-transform 220ms cubic-bezier(0.15, 1, 0.3, 1),transform 220ms cubic-bezier(0.15, 1, 0.3, 1), width 220ms cubic-bezier(0.15, 1, 0.3, 1)
        -webkit-transition: -webkit-transform 220ms cubic-bezier(0.15, 1, 0.3, 1),transform 220ms cubic-bezier(0.15, 1, 0.3, 1), width 220ms cubic-bezier(0.15, 1, 0.3, 1)
        width: 300px
        z-index: 501

    .sidebar-narrow-wrapper // hRodWL
        align-items: center
        -webkit-box-align: center
        -ms-flex-align: center
        -webkit-align-items: center
        box-sizing: border-box
        -moz-box-sizing: border-box
        display: -webkit-box
        display: -moz-box
        display: -ms-flexbox
        display: -webkit-flex
        display: flex
        flex-direction: column
        -webkit-box-direction: normal
        -webkit-box-orient: vertical
        -ms-flex-direction: column
        -webkit-flex-direction: column
        flex: 1 0 auto
        -ms-flex: 1 0 auto
        -webkit-flex: 1 0 auto
        padding: 24px 0 24px 0
        position: relative
        width: 64px
        .top-container // kSkxl
            align-items: center
            -webkit-box-align: center
            -ms-flex-align: center
            -webkit-align-items: center
            display: -webkit-box
            display: -moz-box
            display: -ms-flexbox
            display: -webkit-flex
            display: flex
            height: 40px
            justify-content: center
            -webkit-box-pack: center
            -ms-flex-pack: center
            -webkit-justify-content: center
            width: 40px
            color: #5E6C84
            span // fbYwee
                color: currentColor
                display: inline-block
                fill: #FFFFFF
                height: 32px
                line-height: 1
                width: 32px
        .bottom-container // hJMjAE
            display: -webkit-box
            display: -moz-box
            display: -ms-flexbox
            display: -webkit-flex
            display: flex
            justify-content: center
            -webkit-box-pack: center
            -ms-flex-pack: center
            -webkit-justify-content: center
            left: 0
            position: absolute
            width: 100%
            top: 120px
            .go-back-container
                display: -webkit-box
                display: -moz-box
                display: -ms-flexbox
                display: -webkit-flex
                display: flex
                align-items: center
                -webkit-box-align: center
                -ms-flex-align: center
                -webkit-align-items: center
                justify-content: center
                -webkit-box-pack: center
                -ms-flex-pack: center
                -webkit-justify-content: center
                margin: 0
                background: none
                border: none
                border-radius: 50%
                padding: 0
                width: 40px
                height: 40px
                outline: none
                button
                    -moz-appearance: none
                    -webkit-appearance: none
                    color: #42526E
                    background-color: transparent
                    fill: #F4F5F7
                    align-items: center
                    -webkit-box-align: center
                    -ms-flex-align: center
                    -webkit-align-items: center
                    border: none
                    border-radius: 50%
                    cursor: pointer
                    display: -webkit-box
                    display: -moz-box
                    display: -ms-flexbox
                    display: -webkit-flex
                    display: flex
                    line-height: 1
                    width: 40px
                    height: 40px
                    justify-content: center
                    -webkit-box-pack: center
                    -ms-flex-pack: center
                    -webkit-justify-content: center
                    margin-top: 0px
                    padding: 0
                    outline: none
                    text-align: center
                    span
                        color: currentColor
                        display: inline-block
                        fill: #FFFFFF
                        height: 24px
                        line-height: 1
                        width: 24px

    .sidebar-wide-wrapper
        box-sizing: border-box
        -moz-box-sizing: border-box
        display: -webkit-box
        display: -moz-box
        display: -ms-flexbox
        display: -webkit-flex
        display: flex
        flex-direction: column
        -webkit-box-direction: normal
        -webkit-box-orient: vertical
        -ms-flex-direction: column
        -webkit-flex-direction: column
        padding: 16px 0 0
        overflow-y: auto
        width: 100%
        .logo
            flex-basis: 104px
            -ms-preferred-size: 104px
            -webkit-flex-basis: 104px
            flex-shrink: 0
            -ms-flex-negative: 0
            -webkit-flex-shrink: 0
            min-height: 0
            overflow: hidden
            padding: 0 16px 0 16px
            transition: -ms-flex-basis 220ms,-ms-flex-basis 220ms,-webkit-flex-basis 220ms,-webkit-flex-basis 220ms,flex-basis 220ms, padding 220ms
            -webkit-transition: -webkit-flex-basis 220ms,-webkit-flex-basis 220ms,flex-basis 220ms, padding 220ms
            font-size: 20px
            line-height: 49px
        .sidebar-content
            box-sizing: border-box
            -moz-box-sizing: border-box
            display: -webkit-box
            display: -moz-box
            display: -ms-flexbox
            display: -webkit-flex
            display: flex
            flex-direction: column
            -webkit-box-direction: normal
            -webkit-box-orient: vertical
            -ms-flex-direction: column
            -webkit-flex-direction: column
            flex-grow: 1
            -ms-flex-positive: 1
            -webkit-flex-grow: 1
            padding: 0 8px
            width: 100%
            .small-title
                font-size: 11px
                line-height: 1.4545454545454546
                font-weight: 600
                overflow-x: hidden
                text-overflow: ellipsis
                white-space: nowrap
                width: 100%
                text-transform: uppercase
            a
                align-items: center
                -webkit-box-align: center
                -ms-flex-align: center
                -webkit-align-items: center
                border-radius: 3px
                box-sizing: border-box
                -moz-box-sizing: border-box
                cursor: pointer
                display: -webkit-box
                display: -moz-box
                display: -ms-flexbox
                display: -webkit-flex
                display: flex
                flex: none
                -ms-flex: none
                -webkit-flex: none
                background-color: transparent
                color: #42526E
                text-decoration: none
                padding: 8px 12px 8px 12px
                height: 40px
                span
                    padding-left: 10px

</style>
