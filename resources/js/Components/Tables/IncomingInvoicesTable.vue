<template>
    <div>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-row class="mt-0">
                    <v-col cols="12">
                        <div class="table--header">
                            <h1>
                                {{ trans('Incoming invoices') }}
                            </h1>
                            
                            <div>
                                <v-btn 
                                    @click="$inertia.visit(route('incoming.create'))" 
                                    elevation="0" 
                                    color="primary">
                                    {{ trans("Create new incoming invoice") }}
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field 
                            v-model="idNumber" 
                            :disabled="false" 
                            :placeholder="trans('Invoice ID')" 
                            @input="handleSearch" 
                            name="id_number" 
                            filled
                            dense
                            clearable
                            hide-details="auto" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-menu 
                            ref="menu" 
                            v-model="menu11" 
                            :close-on-content-click="false"
                            transition="scale-transition" 
                            offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="fulfillmentFromDate"
                                    v-bind="attrs" 
                                    v-on="on" 
                                    :placeholder="trans('From fulfillment date')"
                                    :disabled="false"
                                    @click:clear="clearFulfillmentFromDate()" 
                                    readonly 
                                    filled
                                    clearable
                                    hide-details="auto" 
                                    dense />
                            </template>
                            <v-date-picker 
                                v-model="fulfillmentFromDate" 
                                :active-picker.sync="activePicker"
                                :min="minFulfillmentDate" 
                                locale="hu-hu"
                                @change="changeFulfillmentFromDate"></v-date-picker>
                        </v-menu>
                    </v-col>
                    
                    <v-col cols="12" md="4">
                        <v-menu 
                            ref="menu"
                            v-model="menu12" 
                            :close-on-content-click="false"
                            transition="scale-transition" 
                            offset-y 
                            min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field 
                                    v-model="fulfillmentToDate" 
                                    :placeholder="trans('To fulfillment date')"
                                    :disabled="false" readonly 
                                    v-bind="attrs" 
                                    v-on="on" 
                                    @click:clear="clearFulfillmentToDate()" 
                                    clearable
                                    filled
                                    hide-details="auto" 
                                    dense />
                            </template>
                            <v-date-picker 
                                v-model="fulfillmentToDate" 
                                :active-picker.sync="activePicker"
                                :max="maxFulfillmentDate"
                                @change="changeFulfillmentToDate" 
                                locale="hu-hu" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-menu 
                            ref="menu" 
                            v-model="menu21" 
                            :close-on-content-click="false"
                            transition="scale-transition" offset-y min-width="auto">
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
                                locale="hu-hu"
                            />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-menu 
                            ref="menu" 
                            v-model="menu22" 
                            :close-on-content-click="false"
                            transition="scale-transition" 
                            offset-y min-width="auto">
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
                                    filled
                                    dense />
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
                            filled
                            hide-details="auto" 
                            dense />
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field 
                            v-model="toGross" 
                            :disabled="false"
                            :placeholder="trans('To gross')" 
                            name="tgross"
                            type="number" 
                            @input="handleSearch" 
                            clearable 
                            filled
                            hide-details="auto" 
                            dense />
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-data-table 
                    :headers="headers" 
                    :loading="loading" 
                    :items="incomingInvoices" 
                    :options="options"
                    item-key="id">

                    <template v-slot:item.id_number="{ item }">
                        <span>
                            <b class="table--bold">{{ item.id_number }}</b>
                            <small v-if="item.trashed">({{ trans('Deleted') }})</small>
                        </span>
                    </template>

                    <template v-slot:item.issuer_name="{ item }">
                        <span class="table--field">{{ item.issuer_name }}</span>
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
                        <v-menu rounded>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on">
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>
                            </template>

                            <v-list dense>
                                <v-list-item v-if="!item.trashed" @click="edit(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-pencil-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{ trans('Edit') }}
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-if="!item.trashed" @click="deleteItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-delete-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{ trans('Delete') }}
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-else-if="item.trashed" @click="restoreItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-restore</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{ trans('Restore') }}
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-if="item.trashed" @click="forceDeleteItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-delete-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{ trans('Force delete') }}
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
                        v-model="options.incomingInvoicesPerPage" 
                        @change="getIncomingInvoices" 
                        :items="[5, 10, 15, 20]"
                        style="max-width: 100px" dense 
                    />

                    
                    <v-pagination 
                        class="" 
                        v-model="page"
                        @input="getIncomingInvoices"
                        :total-visible="options.incomingInvoicesPerPage" 
                        :length="totalIncomingInvoices" 
                    />
                </div>
                -->
            </v-col>
        </v-row>
        <confirms-modal ref="confirm" />
    </div>
</template>

<script>
import ConfirmsModal from "@/Components/Shared/ConfirmsModal";
import { flash } from "@/Use/helpers"

export default {
    name: "IncomingInvoicesTable",
    components: {
        ConfirmsModal
    },
    props: {

    },
    data: function () {
        return {
            search: "",
            incomingInvoices: [],
            page: 1,
            loading: true,
            totalIncomingInvoices: null,
            options: {
                page: 1,
                incomingInvoicesPerPage: 10,
            },
            headers: [
                { text: this.trans("Invoice ID"), align: "start", value: "id_number" },
                { text: this.trans("Invoice issuer"), value: "issuer_name" },
                { text: this.trans("Fulfillment date"), value: "fulfillment_date" },
                { text: this.trans("Expiration date"), value: "expiration_date" },
                { text: this.trans("Gross"), value: "gross_text" },
                { text: this.trans("Actions"), value: 'actions', sortable: false, align: 'end'},
            ],
            activePicker: null,
            date: null,
            issuer: null,
            idNumber: null,
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

        edit(incoming) {
            this.$inertia.visit(this.route("incoming.edit", { incoming: incoming.id }));
        },

        async deleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("incoming.destroy", { incoming: item.id }));
                flash(this, resp.data)
                await this.getIncomingInvoices();
            }
        },
        async forceDeleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("incoming.force-delete", { incoming: item.id }));
                flash(this, resp.data)
                await this.getIncomingInvoices();
            }
        },
        async restoreItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.patch(this.route("incoming.restore", { incoming: item.id }));
                flash(this, resp.data)
                await this.getIncomingInvoices();
            }
        },

        handleSearch: _.debounce(function (e) {
            this.getIncomingInvoices();
        }, 500),

        changeFulfillmentFromDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() + 3);
                this.maxFulfillmentDate = date.toISOString().slice(0, 10);
            }
            this.getIncomingInvoices();
        },
        changeFulfillmentToDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() - 3);
                this.minFulfillmentDate = date.toISOString().slice(0, 10);
            }
            this.getIncomingInvoices();

        },
        changeExpirationFromDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() + 3);
                this.maxExpirationDate = date.toISOString().slice(0, 10);
            }
            this.getIncomingInvoices();
        },
        changeExpirationToDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() - 3);
                this.minExpirationDate = date.toISOString().slice(0, 10);
            }
            this.getIncomingInvoices();

        },
        clearFulfillmentFromDate() {
            this.fulfillmentFromDate = null;
            this.maxFulfillmentDate = null;
            this.getIncomingInvoices();
        },
        clearFulfillmentToDate() {
            this.fulfillmentToDate = null;
            this.minFulfillmentDate = null;
            this.getIncomingInvoices();
        },
        clearExpirationFromDate() {
            this.expirationFromDate = null;
            this.maxExpirationDate = null;
            this.getIncomingInvoices();
        },
        clearExpirationToDate() {
            this.expirationToDate = null;
            this.minExpirationDate = null;
            this.getIncomingInvoices();
        },

        exportTable() {
            window.open(this.route('incoming.export', {
                params: {
                    page: this.options.page,
                    per_page: this.options.incomingInvoicesPerPage,
                    issuer: this.issuer,
                    id_number: this.idNumber,
                    from_gross: this.fromGross,
                    to_gross: this.toGross,
                    fulfillment_from_date: this.fulfillmentFromDate,
                    fulfillment_to_date: this.fulfillmentToDate,
                    expiration_from_date: this.expirationFromDate,
                    expiration_to_date: this.expirationToDate,
                }
            }), "_self");
        },

        async getIncomingInvoices() {
            this.loading = true;
            this.options.page = this.page;
            await axios.get(this.route('api:incoming.table'), {
                params: {
                    page: this.options.page,
                    per_page: this.options.incomingInvoicesPerPage,
                    issuer: this.issuer,
                    id_number: this.idNumber,
                    from_gross: this.fromGross,
                    to_gross: this.toGross,
                    fulfillment_from_date: this.fulfillmentFromDate,
                    fulfillment_to_date: this.fulfillmentToDate,
                    expiration_from_date: this.expirationFromDate,
                    expiration_to_date: this.expirationToDate,
                }
            }).then((response) => {
                this.incomingInvoices = response.data.data;
                this.totalIncomingInvoices = response.data.total;
            })
            .catch((error) => {
                console.log(error);
            }).finally(() => {
                this.loading = false;
            });
        },
    },

    created() {
        this.getIncomingInvoices();
    },
};
</script>