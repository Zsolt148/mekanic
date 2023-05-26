<template>
    <v-form>
        <v-col cols="12" md="6">
            <v-row>

                <v-col cols="12">
                    <v-combobox v-model="selectedPartner" :items="activePartners" :search-input.sync="partnerSearch"
                        :disabled="disabled" :hide-no-data="!partnerSearch" :outlined="false" :loading="isPartnerLoading"
                        :error-messages="form.errors.name" :placeholder="trans('Start typing to search')"
                        :label="trans('Ügyfél név') + '*'" @change="onPartnerChange()" item-text="name" item-value="id"
                        persistent-hint filled hide-details="auto" small-chips clearable>
                        <template v-slot:no-data>
                            <v-list-item>
                                <span class="mr-2">{{ trans("Create") }}</span>
                                <v-chip pill small>
                                    {{ partnerSearch }}
                                </v-chip>
                            </v-list-item>
                        </template>
                    </v-combobox>
                </v-col>

                <v-col cols="12">
                    <v-btn color="primary" filled elevation="0" class="mt-4" :disabled="disabled" type="button"
                        @click="toggle = !toggle">
                        {{ trans("Show/hide partner's data") }}
                    </v-btn>
                </v-col>
                <v-col v-show="toggle">
                    <v-col cols="12">
                        <v-text-field v-model="form.tax_number" :disabled="disabled"
                            :error-messages="form.errors.tax_number" :label="trans('Tax number')" type="text"
                            hide-details="auto" name="tax_number" filled />
                    </v-col>

                    <v-col cols="12">
                        <v-text-field v-model="form.communal_tax_number" :disabled="disabled"
                            :error-messages="form.errors.communal_tax_number" :label="trans('Communal tax number')"
                            type="text" hide-details="auto" name="communal_tax_number" filled />
                    </v-col>

                    <v-col cols="12">
                        <v-text-field v-model="form.zip" :disabled="disabled" :error-messages="form.errors.zip"
                            :label="trans('Zip') + '*'" :min="1000" :max="9999" type="number" hide-details="auto" name="zip"
                            filled />
                    </v-col>

                    <v-col cols="12">
                        <v-text-field v-model="form.city" :disabled="disabled"
                            :error-messages="form.errors.city" :label="trans('City') + '*'" hide-details="auto" name="city"
                            filled />
                    </v-col>

                    <v-col cols="12">
                        <v-text-field v-model="form.address" :disabled="disabled" :error-messages="form.errors.address"
                            :label="trans('Address') + '*'" type="text" hide-details="auto" name="address" filled />
                    </v-col>

                </v-col>

                <v-col class="form--title" cols="12">
                    <v-divider></v-divider>
                    <h3 class="mt-4">{{ trans("Basic data") }}</h3>
                </v-col>

                <v-col cols="6">
                    <v-text-field v-model="form.brand" :disabled="disabled" :error-messages="form.errors.brand"
                        :label="trans('Brand') + '*'" name="brand" hide-details="auto" filled />
                </v-col>

                <v-col cols="6">
                    <v-text-field v-model="form.type" :disabled="disabled" :error-messages="form.errors.type"
                        :label="trans('Type') + '*'" name="type" hide-details="auto" filled />
                </v-col>

                <v-col cols="6">
                    <v-text-field v-model="form.license_plate" :disabled="disabled"
                        :error-messages="form.errors.license_plate" :label="trans('License plate') + '*'"
                        name="license_plate" hide-details="auto" filled />
                </v-col>

                <v-col cols="6">
                    <v-text-field v-model="form.mileage" :disabled="disabled" :error-messages="form.errors.mileage"
                        :label="trans('Mileage') + '*'" name="mileage" hide-details="auto" type="number" filled />
                </v-col>

                <v-col cols="12">
                    <v-text-field v-model="form.chassis_number" :disabled="disabled"
                        :error-messages="form.errors.chassis_number" :label="trans('Chassis number') + '*'"
                        name="chassis_number" hide-details="auto" filled />
                </v-col>

                <v-col cols="12">
                    <v-text-field v-model="form.motor_number" :disabled="disabled"
                        :error-messages="form.errors.motor_number" :label="trans('Motor number') + '*'" name="motor_number"
                        hide-details="auto" filled />
                </v-col>

                <v-col cols="6">
                    <v-text-field v-model="form.vintage" :disabled="disabled" :error-messages="form.errors.vintage"
                        :label="trans('Vintage') + '*'" name="vintage" min="1950" max="2023" hide-details="auto"
                        type="number" filled />
                </v-col>

                <v-col cols="6">
                    <v-menu ref="menu" v-model="menu1" :close-on-content-click="false" transition="scale-transition"
                        offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-model="form.ti_validity" :label="trans('Technical inspection validity') + '*'"
                                :error-messages="form.errors.ti_validity" :disabled="disabled" filled hide-details='auto'
                                readonly v-bind="attrs" v-on="on" />
                        </template>
                        <v-date-picker v-model="form.ti_validity" min="1950-01-01" locale="hu-hu" @change="save" />
                    </v-menu>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <v-btn color="primary" elevation="0" class="me-3 mt-4" @click="$emit('submit')" :disabled="disabled"
                        :loading="form.processing">
                        <span v-if="isNew">{{ trans('Add new car') }}</span>
                        <span v-else>{{ trans('Save changes') }}</span>
                    </v-btn>
                    <v-btn color="secondary" filled elevation="0" class="me-3 mt-4"
                        @click="$inertia.visit(route('cars.index'))">
                        {{ trans('Cancel') }}
                    </v-btn>

                    <span v-if="restoreOrDeleteForm">
                        <confirms-modal v-if="!form.trashed" @confirmed="$emit('destroy')">
                            <v-btn color="error" filled elevation="0" class="mt-4" :disabled="disabled"
                                :loading="restoreOrDeleteForm.processing">
                                {{ trans('Delete') }}
                            </v-btn>
                        </confirms-modal>

                        <confirms-modal v-else @confirmed="$emit('restore')">
                            <v-btn color="error" filled elevation="0" class="mt-4" :disabled="disabled"
                                :loading="restoreOrDeleteForm.processing">
                                {{ trans('Restore') }}
                            </v-btn>
                        </confirms-modal>
                    </span>
                </v-col>
            </v-row>

        </v-col>
    </v-form>
</template>

<script>
import ConfirmsModal from "@/Components/Shared/ConfirmsModal";
import TagCombobox from "@/Components/Shared/TagCombobox";

export default {
    name: "CarsForm",

    components: {
        ConfirmsModal,
        TagCombobox
    },
    data() {

        if (this.isNew) {
            this.partner_name_default = null;
        } else {
            //this.partner_name_default = this.partners[this.car.partner_id];
            this.partner_name_default = this.partners.find((partner) => partner.id == this.car.partner_id).name;
        }
        if (this.isNew) {
            this.toggle_status = true;
        } else {
            this.toggle_status = false;
        }
        return {
            toggle: this.toggle_status,
            timeout: null,
            partnerSearch: null,
            isCititesDisabled: this.isNew,
            selectedPartner: this.partner_name_default,
            tax_number: null,
            activePartners: null,
            isPartnerLoading: false,
            activePicker: null,
            date: null,
            menu1: false,
            menu2: false,
            menu3: false,
        };
    },
    methods: {
        save(date) {
            this.$refs.menu.save(date)
        },
        onPartnerChange() {
            if (this.selectedPartner && this.selectedPartner !== null) {
                if (
                    typeof this.selectedPartner === "string" ||
                    this.selectedPartner instanceof String
                ) {
                    this.form.name = this.selectedPartner;
                    this.form.partner_id = this.selectedPartner.id;
                } else {
                    let partner = this.partners.filter((obj) => {
                        return obj.name
                            .toLowerCase()
                            .includes(this.selectedPartner.name.toLowerCase());
                    });

                    if (partner.length) {
                        partner = partner[0];
                        this.form.partner_id = partner.id;
                        this.form.name = partner.name;
                        this.form.zip = partner.zip;
                        this.form.city = partner.city;
                        this.form.address = partner.address;
                        this.form.tax_number = partner.tax_number;
                        this.form.communal_tax_number = partner.communal_tax_number;
                    }
                }
            }
        },
        // myFunctionOnLoad: function () {
        //     this.form.partner_id = this.partners.find((partner) => partner.id == this.car.partner_id).id;
        //     this.form.name = this.partners.find((partner) => partner.id == this.car.partner_id).name;
        //     this.form.zip = this.partners.find((partner) => partner.id == this.car.partner_id).zip;
        //     this.form.address = this.partners.find((partner) => partner.id == this.car.partner_id).address;
        //     this.form.tax_number = this.partners.find((partner) => partner.id == this.car.partner_id).tax_number;
        //     this.form.communal_tax_number = this.partners.find((partner) => partner.id == this.car.partner_id).communal_tax_number;
        // }
    },
    watch: {
        // 'form.zip': function () {
        //     if (this.form.zip.length > 3) {
        //         this.lookupStartingZip();
        //     }
        // },
        partnerSearch(val) {
            // search input is empty
            if (!val) return;

            // Items have already been requested
            if (this.isPartnerLoading) return;

            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.timeout = setTimeout(() => {
                this.isLoading = true;

                this.activePartners = this.partners.filter((obj) => {
                    return obj.name.toLowerCase().includes(val.toLowerCase());
                });

                this.isLoading = false;
            }, 200);
        },
    },
    props: {
        form: {
            type: Object,
            required: true,
        },
        restoreOrDeleteForm: {
            type: Object,
            required: false,
        },
        payment_modes: {
            type: Array,
            required: false,
        },
        partners: {
            type: Array,
            required: true,
        },
        isNew: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        car: {
            type: Object,
            required: false,
        },

    },
    created: function () {
        // this.myFunctionOnLoad()
    },
    emits: ['submit', 'destroy', 'restore'],
}
</script>