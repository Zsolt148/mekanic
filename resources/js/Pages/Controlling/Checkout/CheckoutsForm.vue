<template>
    <v-form>
        <v-col cols="12" md="6">
            <v-row>
                <v-col cols="12">
                    <v-autocomplete
                        v-model="form.type"
                        :items="types"
                        :label="trans('Direction')+'*'"
                        :error-messages="form.errors.type"
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
                            v-model="form.date"
                            :label="trans('Date')+'*'"
                            :error-messages="form.errors.date"
                            :disabled="disabled"
                            filled
                            readonly
                            hide-details="auto"
                            v-bind="attrs"
                            v-on="on" />
                    </template>

                    <v-date-picker
                        v-model="form.date"
                        :active-picker.sync="activePicker"
                        min="1950-01-01"
                        locale="hu-hu"
                        @change="save" />
                    </v-menu>
                </v-col>
        
                <v-col cols="12">
                    <v-text-field
                        v-model="form.receipt_number"
                        :disabled="disabled"
                        :error-messages="form.errors.receipt_number"
                        :label="trans('Receipt number')+'*'"
                        name="receipt_number"
                        hide-details="auto"
                        filled />
                </v-col>
        
                <v-col cols="12">
                    <v-text-field
                        v-model="form.claimant"
                        :disabled="disabled"
                        :error-messages="form.errors.claimant"
                        :label="trans('Claimant')+'*'"
                        name="claimant"
                        hide-details="auto"
                        filled />
                </v-col>
        
                <v-col cols="12">
                    <v-text-field
                        v-model="form.amount"
                        :disabled="disabled"
                        :error-messages="form.errors.amount"
                        :label="trans('Amount')+'*'"
                        name="amount"
                        type="number"
                        hide-details="auto"
                        filled />
                </v-col>
        
                <v-col cols="12">
                    <tag-combobox
                        :model.sync="form.title"
                        :disabled="disabled"
                        :errorMsg="form.errors.title"
                        :label="trans('Title2')+'*'"
                        type="title"
                        hide-details="auto"
                        filled
                        :outlined="false"
                    />
                </v-col>
                    
                <v-col cols="12">
                    <v-btn
                        color="primary"
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$emit('submit')"
                        :disabled="disabled"
                        :loading="form.processing">
                        <span v-if="isNew">{{ trans('Create new checkout') }}</span>
                        <span v-else>{{ trans('Save changes') }}</span>
                    </v-btn>

                    <v-btn
                        color="secondary"
                        filled
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$inertia.visit(route('checkout.index'))">
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
                                :loading="restoreOrDeleteForm.processing">
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
    name: "CheckoutsForm",

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

        types: {
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
        }
    },

    emits: ['submit', 'destroy', 'restore'],
}
</script>