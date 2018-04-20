<template>
    <div class="box">
        <div class="columns">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-6">
                        <slot name="resourceName"></slot>
                    </div>
                    <div class="column is-6 has-text-right">
                        <slot name="resourceOptions"></slot>
                    </div>
                </div>
                <hr>
            </div>
        </div>


        <div class="columns">

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <tbody>
                        <slot name="leftTable"></slot>
                    </tbody>
                </table>
            </div>

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <colgroup>
                        <col style="width:25%;">
                    </colgroup>
                    <tbody>
                        <slot name="rightTable"></slot>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
