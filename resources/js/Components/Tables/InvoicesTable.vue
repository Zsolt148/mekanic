<template>
    <div>
        <v-row class="mt-7">
            <v-col cols="12">
                <v-row class="pa-0">
                    <v-col cols="12" class="pt-0">
                        <div class="table--header">
                            <h1>
                                {{ trans('Invoices') }}
                            </h1>

                            <div>
                                <v-btn
                                    @click="$inertia.visit(route('invoices.create'))"
                                    elevation="0"
                                    color="primary">
                                    {{ trans("Create new invoice") }}
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="orderNumber"
                            :disabled="false"
                            :label="trans('Order number')"
                            :placeholder="trans('Search')"
                            @input="handleSearch"
                            name="id_number"
                            clearable
                            hide-details="auto"
                            filled
                            dense
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="partner"
                            :disabled="false"
                            :placeholder="trans('Customer')"
                            @input="handleSearch"
                            name="id_number"
                            clearable
                            filled
                            hide-details="auto"
                            dense />
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-menu
                            ref="menu"
                            v-model="menu11"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="fulfillmentFromDate"
                                    :placeholder="trans('From fulfillment date')"
                                    @click:clear="clearFulfillmentFromDate()"
                                    :disabled="false"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    clearable
                                    hide-details="auto"
                                    dense
                                    filled />
                            </template>

                            <v-date-picker
                                v-model="fulfillmentFromDate"
                                :active-picker.sync="activePicker"
                                :min="minFulfillmentDate"
                                @change="changeFulfillmentFromDate"
                                locale="hu-hu" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-menu
                            ref="menu"
                            v-model="menu12"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="fulfillmentToDate"
                                    :placeholder="trans('To fulfillment date')"
                                    :disabled="false"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    clearable
                                    @click:clear="clearFulfillmentToDate()"
                                    hide-details="auto"
                                    dense
                                    filled />
                            </template>
                            <v-date-picker
                                v-model="fulfillmentToDate"
                                :active-picker.sync="activePicker"
                                :max="maxFulfillmentDate"
                                locale="hu-hu"
                                @change="changeFulfillmentToDate" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-menu
                            ref="menu"
                            v-model="menu21"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="expirationFromDate"
                                    :placeholder="trans('From expiration date')"
                                    :disabled="false"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    clearable
                                    @click:clear="clearExpirationFromDate()"
                                    hide-details="auto"
                                    dense
                                    filled />
                            </template>
                            <v-date-picker
                                v-model="expirationFromDate"
                                :active-picker.sync="activePicker"
                                :min="minExpirationDate"
                                @change="changeExpirationFromDate"
                                locale="hu-hu" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-menu
                            ref="menu"
                            v-model="menu22"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="expirationToDate"
                                    :placeholder="trans('To expiration date')"
                                    :disabled="false"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    @click:clear="clearExpirationToDate"
                                    clearable
                                    hide-details="auto"
                                    dense
                                    filled />
                            </template>
                            <v-date-picker
                                v-model="expirationToDate"
                                :active-picker.sync="activePicker"
                                :max="maxExpirationDate"
                                locale="hu-hu"
                                @change="changeExpirationToDate" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="fromGross"
                            :disabled="false"
                            :placeholder="trans('From gross')"
                            name="fgross"
                            type="number"
                            @input="handleSearch"
                            clearable
                            hide-details="auto"
                            dense
                            filled />
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="toGross"
                            :disabled="false"
                            :placeholder="trans('To gross')"
                            @input="handleSearch"
                            name="tgross"
                            type="number"
                            clearable
                            hide-details="auto"
                            dense
                            filled />
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-data-table
                    :headers="headers"
                    :loading="loading"
                    :items="invoices"
                    :options="options"
                    item-key="id"
                >
                    <template v-slot:item.order_number="{ item }">

                        <span class="cursor-pointer" @click="edit(item)">
                            <b class="table--bold">{{ item.order_number }}</b>
                            <small v-if="item.trashed">({{trans('Deleted')}})</small>
                        </span>
                    </template>

                    <template v-slot:item.partner_name="{ item }">
                        <span class="table--field">{{ item.partner_name }}</span>
                    </template>

                    <template v-slot:item.fulfillment_date="{ item }">
                        <span class="table--field">{{ item.fulfillment_date }}</span>
                    </template>

                    <template v-slot:item.expiration_date="{ item }">
                        <span class="table--field">{{ item.expiration_date }}</span>
                    </template>

                    <template v-slot:item.gross_text="{ item }">
                        <span class="table--field">{{ item.gross_text }}</span>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-menu rounded
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>
                            </template>

                            <v-list dense>
                                <v-list-item v-if="!item.trashed" @click="edit(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-pencil-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{trans('Edit')}}
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-if="!item.trashed" @click="deleteItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-delete-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{trans('Delete')}}
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-else-if="item.trashed" @click="restoreItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-restore</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{trans('Restore')}}
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-if="item.trashed" @click="forceDeleteItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-delete-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{trans('Force delete')}}
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </v-data-table>

                <!--
                <div class="d-flex justify-end py-4">
                    <v-select
                        :label="trans('Items per page')"
                        class="pe-4 pt-2"
                        v-model="options.invoicesPerPage"
                        @change="getInvoices"
                        :items="[5, 10, 15, 20]"
                        style="max-width: 100px"
                        dense
                    />
                    <v-pagination
                        class=""
                        v-model="page"
                        @input="getInvoices"
                        :total-visible="options.invoicesPerPage"
                        :length="totalInvoices"
                    />
                </div>
                -->
            </v-col>
        </v-row>
        <confirms-modal ref="confirm"/>
    </div>
</template>

<script>

import ConfirmsModal from "@/Components/Shared/ConfirmsModal";
import { flash } from "@/Use/helpers"

export default {
    name: "InvoicesTable",
    components: {
        ConfirmsModal
    },
    props: {},
    data: function () {
        return {
            search: "",
            invoices: [],
            page: 1,
            loading: true,
            totalInvoices: null,
            options: {
                page: 1,
                invoicesPerPage: 10,
            },
            headers: [
                {text: this.trans("Order number"), align: "start", value: "order_number"},
                {text: this.trans("Customer"), value: "partner_name"},
                {text: this.trans("Fulfillment date"), value: "fulfillment_date"},
                {text: this.trans("Expiration date"), value: "expiration_date"},
                {text: this.trans("Gross"), value: "gross_text"},
                {text: this.trans('Actions'), value: 'actions', sortable: false, align: 'end'},
            ],
            activePicker: null,
            date: null,
            partner: null,
            orderNumber: null,
            fulfillmentFromDate: null,
            minFulfillmentDate: null,
            fulfillmentToDate: null,
            maxFulfillmentDate: null,
            expirationFromDate: null,
            minExpirationDate: null,
            expirationToDate: null,
            maxExpirationDate: null,
            expirationDates: null,
            fromGross: null,
            toGross: null,
            menu11: false,
            menu12: false,
            menu21: false,
            menu22: false,
        };
    },

    methods: {
        save(date) {
            this.$refs.menu.save(date)
        },

        edit(invoice) {
            this.$inertia.visit(this.route("invoices.edit", {invoice: invoice.id}));
        },

        async deleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("invoices.destroy", {invoice: item.id}));
                flash(this, resp.data)
                await this.getInvoices();
            }
        },

        async forceDeleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("invoices.force-delete", {invoice: item.id}));
                flash(this, resp.data)
                await this.getInvoices();
            }
        },

        async restoreItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.patch(this.route("invoices.restore", {invoice: item.id}));
                flash(this, resp.data)
                await this.getInvoices();
            }
        },

        handleSearch: _.debounce(function (e) {
            this.getInvoices();
        }, 500),

        changeFulfillmentFromDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() + 3);
                this.maxFulfillmentDate = date.toISOString().slice(0, 10);
            }
            this.getInvoices();
        },

        changeFulfillmentToDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() - 3);
                this.minFulfillmentDate = date.toISOString().slice(0, 10);
            }
            this.getInvoices();
        },

        changeExpirationFromDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() + 3);
                this.maxExpirationDate = date.toISOString().slice(0, 10);
            }
            this.getInvoices();
        },

        changeExpirationToDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() - 3);
                this.minExpirationDate = date.toISOString().slice(0, 10);
            }
            this.getInvoices();
        },

        clearFulfillmentFromDate() {
            this.fulfillmentFromDate = null;
            this.maxFulfillmentDate = null;
            this.getInvoices();
        },

        clearFulfillmentToDate() {
            this.fulfillmentToDate = null;
            this.minFulfillmentDate = null;
            this.getInvoices();
        },

        clearExpirationFromDate() {
            this.expirationFromDate = null;
            this.maxExpirationDate = null;
            this.getInvoices();
        },

        clearExpirationToDate() {
            this.expirationToDate = null;
            this.minExpirationDate = null;
            this.getInvoices();
        },

        exportTable() {
            window.open(this.route('invoices.export', {
                params: {
                    page: this.options.page,
                    per_page: this.options.invoicesPerPage,
                    order_number: this.orderNumber,
                    partner: this.partner,
                    from_gross: this.fromGross,
                    to_gross: this.toGross,
                    fulfillment_from_date: this.fulfillmentFromDate,
                    fulfillment_to_date: this.fulfillmentToDate,
                    expiration_from_date: this.expirationFromDate,
                    expiration_to_date: this.expirationToDate,
                }
            }), "_self");
        },

        async getInvoices() {
            try {
                this.loading = true;
                this.options.page = this.page;
                const response = await axios.get(this.route('api:invoices.table'), {
                    params: {
                        page: this.options.page,
                        per_page: this.options.invoicesPerPage,
                        order_number: this.orderNumber,
                        partner: this.partner,
                        from_gross: this.fromGross,
                        to_gross: this.toGross,
                        fulfillment_from_date: this.fulfillmentFromDate,
                        fulfillment_to_date: this.fulfillmentToDate,
                        expiration_from_date: this.expirationFromDate,
                        expiration_to_date: this.expirationToDate,
                    }
                });
                this.invoices = response.data.data;
                this.totalInvoices = response.data.total;
                this.loading = false;
            } catch (error) {
                console.error(error);
            }
        },
    },

    created() {
        this.getInvoices();
    },
};
</script>