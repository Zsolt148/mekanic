<template>
    <div>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-row class="mt-0">
                    <v-col cols="12">
                        <div class="table--header">
                            <h1>
                                {{ trans('Worksheets') }}
                            </h1>
                            
                            <div>
                                <v-btn 
                                    @click="$inertia.visit(route('worksheets.create'))"
                                    elevation="0" 
                                    color="primary">
                                    {{ trans("Add new worksheet") }}
                                </v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>

                <v-row>

                </v-row>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-data-table 
                    :headers="headers"
                    :items="worksheets"
                    item-key="id"
                >
                    <template v-slot:item.admin="{ item }">
                        <span class="table--field">{{ item.admin.name }}</span>
                    </template>

                    <template v-slot:item.type="{ item }">
                        <span class="table--field">{{ item.type_text }}</span>
                    </template>

                    <template v-slot:item.partner_id="{ item }">
                        <span class="table--field">{{ item.partner.name }}</span>
                    </template>

                    <template v-slot:item.car_id="{ item }">
                        <span class="table--field">{{ item.car.brand }}</span>
                    </template>

                    <template v-slot:item.service_names="{ item }">
                        <span class="table--field">{{ item.service_names }}</span>
                    </template>

                    <template v-slot:item.created_at="{ item }">
                        <span class="table--field">{{ item.created_at_diff }}</span>
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
                                <v-list-item @click="deleteItem(item)">
                                    <v-list-item-icon>
                                        <v-icon>mdi-delete-outline</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        {{ trans('Delete') }}
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </v-data-table>
                
            </v-col>
        </v-row>

        <confirms-modal ref="confirm" />

    </div>
</template>

<script>
import ConfirmsModal from "@/Components/Shared/ConfirmsModal";
import { flash } from "@/Use/helpers"

export default {
    name: "WorksheetsTable",
    components: {
        ConfirmsModal,
    },
    props: ['worksheets', 'types'],
    data: function () {
        return {
            headers: [
                { text: this.trans("Admin"), align: "start", value: "admin" },
                { text: this.trans("Type"), align: "start", value: "type" },
                { text: this.trans("Partner"), align: "start", value: "partner_id" },
                { text: this.trans("Car"), align: "start", value: "car_id" },
                { text: this.trans("Services"), align: "start", value: "service_names" },
                { text: this.trans("Done"), align: "start", value: "done_at" },
                { text: this.trans("Created"), align: "start", value: "created_at" },
                { text: this.trans("Actions"), value: 'actions', sortable: false, align: 'end'},
            ],
            restoreOrDeleteForm: this.$inertia.form({}),
        };
    },

    methods: {
        edit(sheet) {
            this.$inertia.visit(this.route("worksheets.edit", sheet.id));
        },

        async deleteItem(sheet) {
            if (await this.$refs.confirm.open()) {
                this.restoreOrDeleteForm.delete(this.route("worksheets.destroy", sheet.id));
            }
        },
    },
};
</script>