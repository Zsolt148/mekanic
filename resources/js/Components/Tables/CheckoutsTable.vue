<template>
    <div>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-row class="mt-0">
                    <v-col cols="12">
                        <div class="table--header">
                            <h1>
                                {{ trans('Checkouts') }}
                            </h1>

                            <div>
                                <v-btn
                                    @click="$inertia.visit(route('checkout.create'))"
                                    elevation="0"
                                    color="primary">
                                    {{trans("Create new checkout")}}
                                </v-btn>

                                <v-btn
                                    :disabled="checkouts.length < 1"
                                    color="secondary"
                                    filled
                                    elevation="0"
                                    class="ms-5"
                                    @click="exportTable">
                                    {{trans('Export')}}
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-card outlined>
                            <v-col cols="4" class="center">
                                <p class="mb-0">{{trans('Inpayment')}}: {{plusCheckout}}</p>
                            </v-col>
                            <v-col cols="4" class="center">
                                <p class="mb-0">{{trans('Disbursement')}}: {{minusCheckout}}</p>
                            </v-col>
                            <v-col cols="4" class="center">
                                <p class="mb-0">{{trans('Total')}}: {{allCheckout}}</p>
                            </v-col>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="2">
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
                                    v-model="fromDate"
                                    @click:clear="clearFromDate()"
                                    :placeholder="trans('From date')"
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
                                v-model="fromDate"
                                :active-picker.sync="activePicker"
                                :type="'month'"
                                :format="'YYYY-MM'"
                                :min="minDate"
                                locale="hu-hu"
                                @change="changeFromDate" />
                        </v-menu>
                    </v-col>
                    <v-col cols="12" md="2">
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
                                    v-model="toDate"
                                    :placeholder="trans('To date')"
                                    :disabled="false"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    @click:clear="clearToDate"
                                    clearable
                                    hide-details="auto"
                                    dense
                                    filled />
                            </template>

                            <v-date-picker
                                v-model="toDate"
                                :active-picker.sync="activePicker"
                                :type="'month'"
                                :format="'YYYY-MM'"
                                :max="maxDate"
                                locale="hu-hu"
                                @change="changeToDate" />
                        </v-menu>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                    <v-data-table
                        :headers="headers"
                        :loading="loading"
                        :items="checkouts"
                        :options="options"
                        :hide-default-footer="false"
                        item-key="id"
                    >

                        <template v-slot:item.receipt_number="{ item }">
                            <span>
                                <b class="table--bold">{{ item.receipt_number }}</b>
                                <small v-if="item.trashed">({{trans('Deleted')}})</small>
                            </span>
                        </template>

                        <template v-slot:item.date="{ item }">
                            <span class="table--field">{{item.date}}</span>
                        </template>

                        <template v-slot:item.type_name="{ item }">
                            <span class="table--field">{{item.type_name}}</span>
                        </template>

                        <template v-slot:item.amount_text="{ item }">
                            <span class="table--field">{{item.amount_text}}</span>
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
                            v-model="options.checkoutsPerPage"
                            @change="getCheckouts"
                            :items="[5, 10, 15, 20]"
                            style="max-width: 100px"
                            dense
                        />
                        <v-pagination
                            class=""
                            v-model="page"
                            @input="getCheckouts"
                            :total-visible="options.checkoutsPerPage"
                            :length="totalCheckouts"
                        />
                    </div>
                </v-card>
                -->
            </v-col>
        </v-row>
        <confirms-modal ref="confirm"/>
    </div>
</template>

<script>
import ConfirmsModal from "@/Components/Shared/ConfirmsModal";
import {flash} from "@/Use/helpers"

export default {
    name: "CheckoutsTable",
    components: {
        ConfirmsModal
    },
    props: {},

    data: function () {
        return {
            search: "",
            checkouts: [],
            page: 1,
            loading: true,
            totalCheckouts: null,
            options: {
                page: 1,
                checkoutsPerPage: 10,
            },
            headers: [
                {text: this.trans("Receipt number"), value: "receipt_number"},
                {text: this.trans("Date"), value: "date"},
                {text: this.trans("Type"), value: "type_name"},
                {text: this.trans("Amount"), value: "amount_text"},
                {text: this.trans('Actions'), value: 'actions', sortable: false, align: 'end'},
            ],
            activePicker: null,
            date: null,
            issuer: null,
            idNumber: null,
            fromDate: null,
            minDate: null,
            toDate: null,
            maxDate: null,
            fromGross: null,
            toGross: null,
            menu11: false,
            menu12: false,
            plusCheckout: null,
            minusCheckout: null,
            allCheckout: null,
        };
    },

    methods: {
        save(date) {
            this.$refs.menu.save(date)
        },

        edit(checkout) {
            this.$inertia.visit(this.route("checkout.edit", {checkout: checkout.id}));
        },

        async deleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("checkout.destroy", {checkout: item.id}));
                flash(this, resp.data)
                await this.getCheckouts();
            }
        },

        async forceDeleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("checkout.force-delete", {checkout: item.id}));
                flash(this, resp.data)
                await this.getCheckouts();
            }
        },

        async restoreItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.patch(this.route("checkout.restore", {checkout: item.id}));
                flash(this, resp.data)
                await this.getCheckouts();
            }
        },

        handleSearch: _.debounce(function (e) {
            this.getCheckouts();
        }, 500),

        changeFromDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() + 3);
                this.maxDate = date.toISOString().slice(0, 7);
            }
            this.getCheckouts();
        },

        changeToDate(val) {
            if (!this.hasRole('superadmin')) {
                const date = new Date(val);
                date.setMonth(date.getMonth() - 3);
                this.minDate = date.toISOString().slice(0, 7);
            }
            this.getCheckouts();

        },
        clearFromDate() {
            this.fromDate = null;
            this.maxDate = null;
            this.getCheckouts();
        },
        clearToDate() {
            this.toDate = null;
            this.minDate = null;
            this.getCheckouts();
        },

        exportTable() {
            window.open(this.route('checkout.export', {
                params: {
                    page: this.options.page,
                    per_page: this.options.checkoutsPerPage,
                    from_date: this.fromDate,
                    to_date: this.toDate,
                }
            }), "_self");
        },

        async getCheckouts() {
            this.loading = true;
            this.options.page = this.page;
            await axios.get(this.route('api:checkout.table'), {
                params: {
                    page: this.options.page,
                    per_page: this.options.checkoutsPerPage,
                    from_date: this.fromDate,
                    to_date: this.toDate,
                }
            })
                .then((response) => {
                    this.checkouts = response.data.data;
                    this.totalCheckouts = response.data.total;
                    this.plusCheckout = response.data.plusCheckout;
                    this.minusCheckout = response.data.minusCheckout;
                    this.allCheckout = response.data.allCheckout;
                })
                .catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.loading = false;
                });
        }
    },

    created() {
        let date = new Date();
        this.fromDate = date.toISOString().slice(0, 7);
        this.changeFromDate(this.fromDate);
    },
};
</script>