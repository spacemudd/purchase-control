<template>
    <div class="modal-card" style="width:auto;">
        <header class="modal-card-head">
            <p class="modal-card-title">
                Associate manufacturer to vendor
            </p>
        </header>
        <section class="modal-card-body">
            <div class="columns is-multiline has-text-left">
                <div class="column is-6">
                    <div class="field">
                        <div class="buttons">
                            <button v-for="manufacturer in availableManufacturers"
                                    class="button is-small cursor"
                                    :class="{
                                        'is-success': isManufacturerAttached(manufacturer),
                                        }"
                                    @click="toggleManufacturer(manufacturer)">
                                {{ manufacturer.name }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot">
            <button class="button" type="button" @click="close()">{{ $t('words.close') }}</button>
            <button class="button is-success" @click="save()">{{ $t('words.save') }}</button>
        </footer>
    </div>
</template>

<script>
    export default {
        props: {
            vendorId: {
                type: Number,
                required: true,
            },
            vendor: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                availableManufacturers: [],
            }
        },
        mounted() {
            this.getManufacturers();
        },
        methods: {
            getManufacturers() {
                axios.get(this.apiUrl() + '/manufacturers').then(response => {
                    this.availableManufacturers = response.data;
                }).catch(error => {
                    alert(error.response.data.message);
                })
            },
            toggleManufacturer(manufacturer) {
                let isRemovingTag = this.isManufacturerAttached(manufacturer);
                if (isRemovingTag) {
                    this.removeManufacturerFromVendor(manufacturer);
                } else {
                    this.addManufacturer(manufacturer);
                }
            },
            isManufacturerAttached(manufacturer) {
                let isAttached = false;

                this.vendor.manufacturers.forEach((manufacturerAttached) => {
                    if (manufacturer.id === manufacturerAttached.id) {
                        isAttached = true;
                    }
                });

                return isAttached;
            },
            removeManufacturerFromVendor(manufacturerToRemove) {
                var newArray = this.vendor.manufacturers.filter(function(manufacturer) {
                    return manufacturer.id != manufacturerToRemove.id;
                });
                this.vendor.manufacturers = newArray;
            },
            addManufacturer(manufacturer) {
                this.vendor.manufacturers.push(manufacturer);
            },
            save() {
                this.$startLoading('SAVING_VENDOR_MANUFACTURERS');
                axios.post(this.apiUrl() + '/vendors/' + this.vendor.id + '/update-associated-manufacturers', {
                    vendor_id: this.vendor.id,
                    manufacturers: this.vendor.manufacturers,
                }).then(response => {
                    this.$toast.open({
                        message: 'Updated manufacturers for ' + this.vendor.name,
                        type: 'is-success',
                    });
                    this.$emit('close');
                    this.$parent.close();
                }).catch(error => {
                    this.$endLoading('SAVING_VENDOR_MANUFACTURERS');
                    alert(error.response.data.message);
                })
            },
            close() {
                this.$emit('close');
                this.$parent.close();
            }
        }
    }
</script>
