<template>
    <div class="anb">
        <svg :class="size" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="352 -98 741 746" style="enable-background:new 352 -98 741 746;" xml:space="preserve">
            <g transform="translate(0.000000,888.000000) scale(0.100000,-0.100000)">
                <path class="st0" d="M10069,3990c-73,157-372,804-665,1439c-294,635-1183,2562-1183,2562l-605,1309h-944l-50-77
                    c-131-206-296-516-631-1183c-283-563-646-1310-951-1955c-21-44-61-129-90-190c-102-214-140-294-332-705c-246-524-616-1321-620-1336
                    c-2-7,53-91,121-189l124-176l32,73c18,40,66,147,107,238c41,91,148,329,238,530l163,365l519,1158l23,51c363,813,978,2173,985,2180
                    c11,11,176,46,203,43c14-2,106-193,378-777c198-426,368-792,378-812c10-21,131-294,136-300c3-3,759-1628,860-1848
                    c29-63,68-149,88-190c19-41,108-232,197-425c89-192,176-379,193-415c16-36,93-200,169-365s143-304,147-308c10-10,216,123,356,228
                    c317,239,592,508,741,725l45,66L10069,3990z"/>
            </g>
            <g transform="translate(0.000000,199.000000) scale(0.100000,-0.100000)">
                <path class="st1" d="M8745-207c-1050-49-2123-272-3019-627c-142-57-423-180-433-190c-8-8-520-1156-516-1156c2,0,75,61,163,136
                c629,539,1323,956,2119,1270c733,290,1614,495,2351,546c171,12,180,14,115,20C9463-202,8874-201,8745-207z"/>
            </g>
        </svg>
    </div>
</template>

<script>
    export default {
        props: {
            size: {
                type: String,
                default: 'is-small',
            }
        },
        data() {
            return {
                //
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

<style lang="sass" scoped>
    .anb
        display: flex
        justify-content: center
        align-items: center
        height: 100%

    .svg
        margin: 0 auto
        display: block

    .is-small
        width: 80px

    .is-medium
        width: 110px

    .is-large
        width: 150px


    @keyframes fadeColor
        0%
            fill: #FFFFFF
        100%
            fill: #ff3c00

    .st0
        fill: #1690CC

    .st1
        fill: #E04248
        -webkit-animation: fadeColor 2s linear infinite alternate
        -moz-animation: fadeColor 2s linear infinite alternate
        animation: fadeColor 2s linear infinte alternate
</style>
