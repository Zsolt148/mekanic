<template>
    <v-form>
        <v-col cols="12" md="6">
            <v-row>
                <v-col class="form--title" cols="12">
                    <h3 class="mt-4">{{ trans("Customer") }}</h3>
                </v-col>
    
                <v-col cols="12">
                    <v-combobox
                        v-model="selectedPartner"
                        :items="activePartners"
                        :search-input.sync="partnerSearch"
                        :disabled="disabled"
                        :hide-no-data="!partnerSearch"
                        :outlined="false"
                        :loading="isPartnerLoading"
                        :error-messages="form.errors.name"
                        :placeholder="trans('Start typing to search')"
                        :label="trans('Name') + '*'"
                        @change="onPartnerChange()"
                        item-text="name"
                        item-value="id"
                        persistent-hint
                        filled
                        hide-details="auto"
                        small-chips
                        clearable>
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
                    <v-text-field
                        v-model="form.tax_number"
                        :disabled="disabled"
                        :error-messages="form.errors.tax_number"
                        :label="trans('Tax number')"
                        type="text"
                        hide-details="auto"
                        name="tax_number"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.communal_tax_number"
                        :disabled="disabled"
                        :error-messages="form.errors.communal_tax_number"
                        :label="trans('Communal tax number')"
                        type="text"
                        hide-details="auto"
                        name="communal_tax_number"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.zip"
                        :disabled="disabled"
                        :error-messages="form.errors.zip"
                        :label="trans('Zip') + '*'"
                        :min="1000"
                        :max="9999"
                        type="number"
                        hide-details="auto"
                        name="zip"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-select
                        v-model="form.city"
                        :disabled="disabled || isCititesDisabled"
                        :error-messages="form.errors.city"
                        :label="trans('City') + '*'"
                        :items="cities"
                        item-text="name"
                        hide-details="auto"
                        dense
                        name="city"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.address"
                        :disabled="disabled"
                        :error-messages="form.errors.address"
                        :label="trans('Address') + '*'"
                        type="text"
                        hide-details="auto"
                        name="address"
                        filled />
                </v-col>

                <v-col class="form--title" cols="12">
                    <v-divider></v-divider>
                    <h3 class="mt-4">{{ trans("Basic data") }}</h3>
                </v-col>
    
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.order_number"
                        :disabled="disabled"
                        :error-messages="form.errors.order_number"
                        :label="trans('Order number') + '*'"
                        name="order_number"
                        hide-details="auto"
                        filled />
                </v-col>

                <v-col cols="12" md="6">
                    <v-autocomplete
                        v-model="form.payment_mode"
                        :items="payment_modes"
                        :label="trans('Payment mode') + '*'"
                        :error-messages="form.errors.payment_mode"
                        :disabled="disabled"
                        hide-details="auto"
                        clearable
                        filled />
                </v-col>
    
                <v-col cols="12" md="6">
                    <v-menu
                        ref="menu"
                        v-model="menu3"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="form.issue_date"
                                :label="trans('Issue date') + '*'"
                                :error-messages="form.errors.issue_date"
                                :disabled="disabled"
                                filled
                                readonly
                                hide-details="auto"
                                v-bind="attrs"
                                v-on="on" />
                        </template>
                        <v-date-picker
                            v-model="form.issue_date"
                            :active-picker.sync="activePicker"
                            min="1950-01-01"
                            locale="hu-hu"
                            @change="save"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
    
                <v-col cols="12" md="6">
                    <v-menu
                        ref="menu"
                        v-model="menu1"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="form.fulfillment_date"
                                :label="trans('Fulfillment date') + '*'"
                                :error-messages="form.errors.fulfillment_date"
                                :disabled="disabled"
                                filled
                                hide-details="auto"
                                readonly
                                v-bind="attrs"
                                v-on="on" />
                        </template>
                        <v-date-picker
                            v-model="form.fulfillment_date"
                            :active-picker.sync="activePicker"
                            min="1950-01-01"
                            locale="hu-hu"
                            @change="save" />
                    </v-menu>
                </v-col>

                <v-col cols="12" md="6">
                    <v-menu
                        ref="menu"
                        v-model="menu2"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="form.expiration_date"
                                :label="trans('Expiration date') + '*'"
                                :error-messages="form.errors.expiration_date"
                                :disabled="disabled"
                                hide-details="auto"
                                filled
                                readonly
                                v-bind="attrs"
                                v-on="on" />
                        </template>
                        <v-date-picker
                            v-model="form.expiration_date"
                            :active-picker.sync="activePicker"
                            min="1950-01-01"
                            locale="hu-hu"
                            @change="save" />
                    </v-menu>
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.comment"
                        :disabled="disabled"
                        :error-messages="form.errors.disabled"
                        :label="trans('Comment')"
                        type="text"
                        name="disabled"
                        hide-details="auto"
                        filled />
                </v-col>

                <v-col class="form--title" cols="12">
                    <v-divider></v-divider>
                    <h3 class="mt-4">{{ trans("Invoice items") }}</h3>
                    <v-btn
                        color="primary"
                        filled
                        elevation="0"
                        class="me-3 mt-4"
                        @click="addNewInvoiceItem"
                    >
                        {{trans("Add invoice item")}}
                    </v-btn>
                </v-col>
    
                <v-col cols="12" v-if="form.errors != undefined && form.errors.invoice_items != undefined">
                    <div class="v-text-field__details">
                        <div class="v-messages theme--light error--text" role="alert">
                            <div class="v-messages__wrapper">
                                <div class="v-messages__message">
                                    {{ form.errors.invoice_items }}
                                </div>
                            </div>
                        </div>
                    </div>
                </v-col>
    
                <v-row class="w-full mt-5 mx-1" v-for="(item, index) in form.invoice_items" :key="index">
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.invoice_items[index].name"
                            :disabled="disabled"
                            :error-messages="form.errors['invoice_items.' + index + '.name']"
                            :label="trans('Name') + '*'"
                            name="invoice_items_name[]"
                            hide-details="auto"
                            filled />
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.invoice_items[index].quantity"
                            name="invoice_items_quantity[]"
                            :disabled="disabled"
                            :error-messages="form.errors['invoice_items.' + index + '.quantity']"
                            :label="trans('Quantity') + '*'"
                            @input="changeItemSummary(index)"
                            type="number"
                            hide-details="auto"
                            filled />
                    </v-col>

                    <v-col cols="12" md="6">
                        <tag-combobox
                            :model.sync="form.invoice_items[index].quantity_unit"
                            :disabled="disabled"
                            :errorMsg="form.errors['invoice_items.' + index + '.quantity_unit']"
                            :label="trans('Quantity unit') + '*'"
                            type="quantity_unit"
                            hide-details="auto"
                            filled
                            :outlined="false" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.invoice_items[index].unit_price"
                            :disabled="disabled"
                            @input="changeItemSummary(index)"
                            :error-messages="form.errors['invoice_items.' + index + '.unit_price']"
                            :label="trans('Unit price') + '*'"
                            name="unit_price"
                            hide-details="auto"
                            type="number"
                            filled />
                    </v-col>

                    <v-col cols="12" md="12">
                        <v-text-field
                            :disabled="disabled"
                            v-model="form.invoice_items[index].tax_percent"
                            :error-messages="form.errors['invoice_items.' + index + '.tax_percent']"
                            :label="trans('VAT key') + '*'"
                            @input="changeItemSummary(index)"
                            name="order_number"
                            hide-details="auto"
                            type="number"
                            filled />
                    </v-col>

                    <v-col class="form--title" cols="12">
                        <h3>
                            {{trans("Net")}} :
                            {{formatNumber(form.invoice_items[index].net)}} Ft
                        </h3>
                    </v-col>

                    <v-col class="form--title" cols="12">
                        <h3>
                            {{trans("Net")}} :
                            {{formatNumber(form.invoice_items[index].net)}} Ft
                        </h3>
                    </v-col>

                    <v-col class="form--title" cols="12">
                        <h3>
                            {{trans("VAT")}} :
                            {{formatNumber(form.invoice_items[index].tax)}} Ft
                        </h3>
                    </v-col>

                    <v-col class="form--title" cols="12">
                        <h3>
                            {{trans("Gross")}} :
                            {{formatNumber(form.invoice_items[index].gross)}} Ft
                        </h3>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-btn
                            color="error"
                            filled
                            elevation="0"
                            @click="deleteInvoiceItem(index)"
                        >
                            {{trans("Delete")}}
                        </v-btn>
                    </v-col>

                    <v-col cols="12">
                        <v-divider></v-divider>
                    </v-col>
                </v-row>
            
                <v-col class="form--title" cols="12">
                    <h3 class="mt-4">{{ trans("Invoice summary") }}</h3>
                </v-col>

                <v-row class="ms-3 mt-2">
                    <v-col cols="4" class="ms-0 ps-0">
                        <h5>{{ trans("Net") }} : {{formatNumber(form.net)}} Ft</h5>
                    </v-col>
    
                    <v-col cols="4">
                        <h5>{{trans("VAT")}} : {{formatNumber(form.tax)}} Ft</h5>
                    </v-col>
    
                    <v-col cols="4">
                        <h5>
                            {{trans("Gross")}} : {{formatNumber(form.gross)}} Ft
                        </h5>
                    </v-col>
                </v-row>

                <v-col cols="12">
                    <v-btn
                        color="primary"
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$emit('submit')"
                        :disabled="disabled"
                        :loading="form.processing"
                    >
                        <span v-if="isNew">{{trans("Create new invoice")}}</span>
                        <span v-else>{{trans("Save changes")}}</span>
                    </v-btn>
                    <v-btn
                        color="secondary"
                        filled
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$inertia.visit(route('invoices.index'))"
                    >
                        {{trans("Cancel")}}
                    </v-btn>
    
                    <span v-if="restoreOrDeleteForm">
            <confirms-modal
                v-if="!form.trashed"
                @confirmed="$emit('destroy')"
            >
            <v-btn
                color="error"
                filled
                elevation="0"
                class="mt-4"
                :disabled="disabled"
                :loading="restoreOrDeleteForm.processing"
            >
                {{trans("Delete")}}
            </v-btn>
            </confirms-modal>
            <confirms-modal v-else @confirmed="$emit('restore')">
            <v-btn
                color="error"
                filled
                elevation="0"
                class="mt-4"
                :disabled="disabled"
                :loading="restoreOrDeleteForm.processing"
            >
                {{trans("Restore")}}
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
    name: "InvoicesForm",

    components: {
        ConfirmsModal,
        TagCombobox,
    },

    data() {
        return {
            timeout: null,
            partnerSearch: null,
            isCititesDisabled: this.isNew,
            selectedPartner: null,
            activePartners: null,
            isPartnerLoading: false,
            activePicker: null,
            date: null,
            menu1: false,
            menu2: false,
            menu3: false,
            cities: [],
        };
    },

    methods: {
        save(date) {
            this.$refs.menu.save(date);
        },

        onPartnerChange() {
            if (this.selectedPartner && this.selectedPartner !== null) {
                if (
                    typeof this.selectedPartner === "string" ||
                    this.selectedPartner instanceof String
                ) {
                    this.form.name = this.selectedPartner;
                    this.form.partner_id = null;
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

        addNewInvoiceItem() {
            this.form.invoice_items.push({
                name: null,
                quantity: null,
                quantity_unit: {},
                unit_price: null,
                tax_percent: null,
                net: 0,
                tax: 0,
                gross: 0,
            });
        },

        changeItemSummary(index) {
            if (
                this.form.invoice_items[index].quantity === null ||
                this.form.invoice_items[index].unit_price === null
            ) {
                this.form.invoice_items[index].net = 0;
                this.form.invoice_items[index].tax = 0;
                this.form.invoice_items[index].gross = 0;
            } else {
                this.form.invoice_items[index].net =
                    this.form.invoice_items[index].quantity *
                    this.form.invoice_items[index].unit_price;
                if (this.form.invoice_items[index].tax_percent !== null) {
                    this.form.invoice_items[index].tax = Math.round(
                        this.form.invoice_items[index].net *
                        (this.form.invoice_items[index].tax_percent / 100)
                    );
                    this.form.invoice_items[index].gross =
                        this.form.invoice_items[index].tax +
                        this.form.invoice_items[index].net;
                }
            }
            this.calculateAllSummary();
        },

        formatNumber(number) {
            let result = Math.round(number).toLocaleString("en-Us");
            result = result.replace(/,/g, ".");
            return result;
        },

        deleteInvoiceItem(index) {
            this.form.invoice_items.splice(index, 1);
            this.calculateAllSummary();
        },

        calculateAllSummary() {
            let net = 0;
            let tax = 0;
            let gross = 0;
            this.form.invoice_items.forEach(function (item) {
                net += item.net;
                tax += item.tax;
                gross += item.gross;
            });
            this.form.net = net;
            this.form.tax = tax;
            this.form.gross = gross;
        },
        async getCities(zip) {
            this.isCititesDisabled = true;
            axios.get(`/api/cities/${zip}`)
                .then((response) => {
                    this.cities = response.data.cities;
                })
                .catch((error) => {
                    console.error(error);
                })
                .finally(() => {
                    this.isCititesDisabled = false;
            });
        }
    },
    watch: {
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
        "form.zip": function (newZip) {
            if (newZip.length === 0) {
                this.form.city = "";
            }
            if (newZip.length > 3) {
                this.getCities(newZip);
            }
        },
    },
    props: {
        form: {
            type: Object,
            required: true,
        },
        partners: {
            type: Array,
            required: true,
        },
        partner: {
            type: Object,
            required: null,
        },
        restoreOrDeleteForm: {
            type: Object,
            required: false,
        },
        payment_modes: {
            type: Array,
            required: false,
        },
        isNew: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },

    mounted() {
        this.activePartners = this.partners;
        this.selectedPartner = this.partner;
        if (this.form.zip != null) {
            this.getCities(this.form.zip);
        }
        this.onPartnerChange();
    },

    emits: ["submit", "destroy", "restore"],
};
</script>
