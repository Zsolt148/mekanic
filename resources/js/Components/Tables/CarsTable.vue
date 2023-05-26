<template>
    <div>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-row class="mt-0">
                    <v-col cols="12">
                        <div class="table--header">
                            <h1>
                                {{ trans('Cars') }}
                            </h1>
                            
                            <div>
                                <v-btn 
                                    @click="$inertia.visit(route('cars.create'))" 
                                    elevation="0" 
                                    color="primary">
                                    {{ trans("Add new car") }}
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field 
                            v-model="brand" 
                            :disabled="false" 
                            :placeholder="trans('Brand')" 
                            @input="handleSearch" 
                            name="brand" 
                            filled
                            dense
                            clearable
                            hide-details="auto" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-text-field 
                            v-model="type" 
                            :disabled="false" 
                            :placeholder="trans('Type')" 
                            @input="handleSearch" 
                            name="type" 
                            filled
                            dense
                            clearable
                            hide-details="auto" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <v-text-field 
                            v-model="license_plate" 
                            :disabled="false" 
                            :placeholder="trans('License plate')" 
                            @input="handleSearch" 
                            name="license_plate" 
                            filled
                            dense
                            clearable
                            hide-details="auto" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field 
                            v-model="chassis_number" 
                            :disabled="false" 
                            :placeholder="trans('Chassis number')" 
                            @input="handleSearch" 
                            name="chassis_number" 
                            filled
                            dense
                            clearable
                            hide-details="auto" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field 
                            v-model="motor_number" 
                            :disabled="false" 
                            :placeholder="trans('Motor number')" 
                            @input="handleSearch" 
                            name="motor_number" 
                            filled
                            dense
                            clearable
                            hide-details="auto" />
                    </v-col>


                </v-row>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-data-table 
                    @click:row="showCarModal"
                    :headers="headers" 
                    :loading="loading" 
                    :items="cars" 
                    :options="options"
                    item-key="id">

                    <template v-slot:item.brand="{ item }">
                        <span class="table--field">{{ item.brand }}</span>
                    </template>

                    <template v-slot:item.type="{ item }">
                        <span class="table--field">{{ item.type }}</span>
                    </template>

                    <template v-slot:item.license_plate="{ item }">
                        <span class="table--field">{{ item.license_plate }}</span>
                    </template>

                    <template v-slot:item.chassis_number="{ item }">
                        <span class="table--field">{{ item.chassis_number }}</span>
                    </template>

                    <template v-slot:item.motor_number="{ item }">
                        <span class="table--field">{{ item.motor_number }}</span>
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
                
            </v-col>
        </v-row>
        <confirms-modal ref="confirm" />

        <cars-modal
            :isVisible="isCarModalVisible"
            :car="selectedCar"
            @close="hideCarModal"
        />

    </div>
</template>

<script>
import ConfirmsModal from "@/Components/Shared/ConfirmsModal";
import { flash } from "@/Use/helpers"
import CarsModal from "@/Pages/Cars/CarsModal";

export default {
    name: "CarsTable",
    components: {
        ConfirmsModal,
        CarsModal
    },
    props: {
        subject: {
            required: false,
            default: null
        }
    },
    data: function () {
        return {
            search: "",
            selectedCar: null,
            chassis_number: null,
            motor_number: null,
            isCarModalVisible: false,
            cars: [],
            page: 1,
            loading: true,
            totalcars: null,
            options: {
                page: 1,
                carsPerPage: 10,
            },
            headers: [
                { text: this.trans("Brand"), align: "start", value: "brand" },
                { text: this.trans("Type"), align: "start", value: "type" },
                { text: this.trans("License plate"), align: "start", value: "license_plate" },
                { text: this.trans("Chassis number"), align: "start", value: "chassis_number" },
                { text: this.trans("Motor number"), align: "start", value: "motor_number" },
                { text: this.trans("Actions"), value: 'actions', sortable: false, align: 'end'},
            ],
            activePicker: null,
            date: null,
            brand: null,
            type: null,
            license_plate: null,
        };
    },

    methods: {
        save(date) {
            this.$refs.menu.save(date)
        },

        edit(car) {
            this.$inertia.visit(this.route("cars.edit", { car: car.id }));
        },

        async deleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("cars.destroy", {car: item.id}));
                flash(this, resp.data)
                await this.getcars();
            }
        },

        async forceDeleteItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.delete(this.route("cars.force-delete", {car: item.id}));
                flash(this, resp.data)
                await this.getcars();
            }
        },

        async restoreItem(item) {
            if (await this.$refs.confirm.open()) {
                const resp = await axios.patch(this.route("cars.restore", {car: item.id}));
                flash(this, resp.data)
                await this.getcars();
            }
        },

        showCarModal(car) {
            this.selectedCar = car;
            this.isCarModalVisible = true;
        },
        hideCarModal() {
            this.isCarModalVisible = false;
        },

        handleSearch: _.debounce(function (e) {
            this.getcars();
        }, 500),

        async getcars() {
            this.loading = true;
            this.options.page = this.page;
            await axios.get(this.route('api:car.table'), {
                params: {
                    page: this.options.page,
                    per_page: this.options.carsPerPage,
                    brand: this.brand,
                    type: this.type,
                    license_plate: this.license_plate,
                    motor_number: this.motor_number,
                    chassis_number: this.chassis_number,
                }
            }).then((response) => {
                this.cars = response.data.data;
                this.totalcars = response.data.total;
            })
            .catch((error) => {
                console.log(error.response.data);
            }).finally(() => {
                this.loading = false;
            });
        },
    },

    created() {
        this.getcars();
    },
};
</script>