<template>
    <v-form>
        <v-col cols="12" md="6">
            <v-row>
                <v-col cols="12">
                    <tag-combobox
                        :model.sync="form.issuer"
                        :disabled="disabled"
                        :errorMsg="form.errors.issuer"
                        :label="trans('Invoice issuer')"
                        :outlined="false"
                        hide-details="auto"
                        type="issuer"
                        filled
                    />
                </v-col>

                <v-col cols="12">
                    <v-text-field
                        v-model="form.id_number"
                        :disabled="disabled"
                        :error-messages="form.errors.id_number"
                        :label="trans('Invoice ID')"
                        name="id_number"
                        hide-details="auto"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.subject"
                        :disabled="disabled"
                        :error-messages="form.errors.subject"
                        :label="trans('Invoice subject')"
                        name="subject"
                        hide-details="auto"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.gross"
                        :disabled="disabled"
                        :error-messages="form.errors.gross"
                        :label="trans('Gross')"
                        name="gross"
                        hide-details="auto"
                        type="number"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.net"
                        :disabled="disabled"
                        :error-messages="form.errors.net"
                        :label="trans('Net')"
                        name="net"
                        hide-details="auto"
                        type="number"
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-autocomplete
                        v-model="form.payment_mode"
                        :items="payment_modes"
                        :label="trans('Payment mode')"
                        :error-messages="form.errors.payment_mode"
                        :disabled="disabled"
                        hide-details="auto"
                        clearable
                        filled />
                </v-col>
    
                <v-col cols="12">
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
                                :label="trans('Fulfillment date')"
                                :error-messages="form.errors.fulfillment_date"
                                :disabled="disabled"
                                filled
                                hide-details='auto'
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
    
                <v-col cols="12">
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
                                :label="trans('Expiration date')"
                                :error-messages="form.errors.expiration_date"
                                :disabled="disabled"
                                filled
                                hide-details="auto"
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
    
                <v-col cols="12">
                    <tag-combobox
                        :model.sync="form.type"
                        :disabled="disabled"
                        :errorMsg="form.errors.type"
                        :label="trans('Type')"
                        type="issuer"
                        hide-details="auto"
                        filled
                        :outlined="false"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <v-btn
                        color="primary"
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$emit('submit')"
                        :disabled="disabled"
                        :loading="form.processing"
                    >
                        <span v-if="isNew">{{ trans('Create new incoming invoice') }}</span>
                        <span v-else>{{ trans('Save changes') }}</span>
                    </v-btn>
                        <v-btn
                            color="secondary"
                            filled
                            elevation="0"
                            class="me-3 mt-4"
                            @click="$inertia.visit(route('incoming.index'))"
                        >
                            {{ trans('Cancel') }}
                        </v-btn>
    
                <span v-if="restoreOrDeleteForm">
                    <confirms-modal v-if="!form.trashed" @confirmed="$emit('destroy')">
                        <v-btn
                            color="error"
                            filled
                            elevation="0"
                            class="mt-4"
                            :disabled="disabled"
                            :loading="restoreOrDeleteForm.processing"
                        >
                            {{ trans('Delete') }}
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
    name: "IncomingInvoicesForm",

    components: {
        ConfirmsModal,
        TagCombobox
    },

    data() {
        return {
            activePicker: null,
            date: null,
            menu1: false,
            menu2: false,
        }
    },

    methods: {
        save (date) {
            this.$refs.menu.save(date)
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
        isNew: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        }
    },

    emits: ['submit', 'destroy', 'restore'],
}
</script>