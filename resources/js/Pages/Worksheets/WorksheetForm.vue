<template>
    <v-form class="multi-col-validation mt-1">
        <v-col cols="12" md="6">
            <v-row>
                <v-col cols="12">
                    <v-select
                        v-model="form.type"
                        :items="types"
                        :label="trans('Type')"
                        filled
                        hide-details="auto"
                    />
                </v-col>
                <v-col cols="12">
                    <v-autocomplete
                        v-model="form.partner_id"
                        :items="partners"
                        :error-messages="form.errors.partner_id"
                        :placeholder="trans('Start typing to search')"
                        :label="trans('Partner') + '*'"
                        item-text="name"
                        item-value="id"
                        persistent-hint
                        filled
                        hide-details="auto"
                        small-chips
                        clearable
                    />
                </v-col>

                <v-col cols="12">
                    <v-autocomplete
                        v-model="form.car_id"
                        :items="cars"
                        :error-messages="form.errors.car_id"
                        :placeholder="trans('Start typing to search')"
                        :label="trans('Car') + '*'"
                        item-text="license_plate"
                        item-value="id"
                        persistent-hint
                        filled
                        hide-details="auto"
                        small-chips
                        clearable
                    />
                </v-col>

                <v-col cols="12">
                    <v-autocomplete
                        v-model="form.services"
                        :items="services"
                        :error-messages="form.errors.services"
                        :placeholder="trans('Start typing to search')"
                        :label="trans('Szolgáltatás') + '*'"
                        item-text="name"
                        item-value="id"
                        multiple
                        chips
                        deletable-chips
                        persistent-hint
                        filled
                        hide-details="auto"
                        small-chips
                        clearable
                    />
                </v-col>

                <v-col cols="12" v-if="sumPrice">
                    Összesen: {{ sumPrice }} Ft
                </v-col>

                <v-col cols="12" class="d-flex justify-space-between">
                    <v-btn
                        color="primary"
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$emit('submit')"
                        :loading="form.processing">
                        <span v-if="isNew">{{ trans('Create worksheet') }}</span>
                        <span v-else>{{ trans('Save changes') }}</span>
                    </v-btn>
    
                    <span v-if="!isNew">
                        <confirms-modal @confirmed="$emit('destroy', form.id)">
                            <v-btn
                                color="error"
                                elevation="0"
                                class="mt-4"
                                :loading="restoreOrDeleteForm.processing">
                                {{ trans('Delete worksheet') }}
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

export default {
    name: "WorksheetForm",

    components: {
        ConfirmsModal,
    },

    data() {
        return {
            activePicker: null,
            date: null,
        }
    },

    methods: {

    },

    props: {
        form: {
            type: Object,
            required: true,
        },
        types: Array,
        partners: Array,
        services: Array,
        cars: Array,
        restoreOrDeleteForm: {
            type: Object,
            required: false,
        },
        isNew: {
            type: Boolean,
            default: false,
        },
    },

    emits: ['submit', 'destroy', 'restore'],

    computed: {
        sumPrice() {
            let services = this.form.services

            return this.services.filter(f => services.indexOf(f.id) > -1).reduce(function (res, item) {
                return res + item.price;
            }, 0);
        }
    }
}
</script>