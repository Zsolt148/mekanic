<template>
    <v-form class="multi-col-validation mt-1">
        <v-col cols="12" md="6">
            <v-row>
                <v-col cols="12">
                    <v-text-field
                        v-model="form.name"
                        :disabled="disabled"
                        :error-messages="form.errors.name"
                        :label="trans('Name')" 
                        hide-details="auto"
                        name="name"
                        dense
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-model="form.email"
                        :disabled="disabled"
                        :error-messages="form.errors.email"
                        hide-details="auto"
                        placeholder="john@example.com"
                        label="E-mail"
                        type="email"
                        name="email"
                        dense
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-if="disabled"
                        :disabled="disabled"
                        v-model="form.roles"
                        :label="trans('Titles')"
                        hide-details="auto"
                        filled
                        dense />
                        
                    <v-autocomplete
                        v-else
                        :items="roles"
                        :error-messages="form.errors.roles"
                        :label="trans('Titles')"
                        v-model="form.roles"
                        hide-details="auto"
                        name="roles"
                        multiple
                        dense
                        filled />
                </v-col>
    
                <v-col cols="12">
                    <v-text-field
                        v-if="disabled"
                        :disabled="disabled"
                        :label="trans('Permissions')"
                        v-model="form.permissions"
                        hide-details="auto"
                        dense
                        filled />

                    <v-autocomplete
                        v-else
                        v-model="form.permissions"
                        :error-messages="form.errors.permissions"
                        :label="trans('Permissions')"
                        :items="permissions"
                        name="permissions"
                        multiple
                        hide-details="auto"
                        filled
                        dense />
                </v-col>

                <v-col class="mt-3" v-if="isNew">
                    <small class="form--alt">{{ trans('We send a registration link to the invited user by email.') }}</small>
                </v-col>

                <v-col cols="12" class="d-flex justify-space-between">
                    <v-btn
                        v-if="disabled"
                        color="primary"
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$inertia.visit(route('admins.edit', form.id))">
                        {{ trans('Edit') }}
                    </v-btn>
    
                    <v-btn
                        v-else
                        color="primary"
                        elevation="0"
                        class="me-3 mt-4"
                        @click="$emit('submit')"
                        :disabled="disabled"
                        :loading="form.processing">
                        <span v-if="isNew">{{ trans('Invite new user') }}</span>
                        <span v-else>{{ trans('Save changes') }}</span>
                    </v-btn>
    
                    <span v-if="restoreOrDeleteForm">
                        <confirms-modal v-if="!form.trashed" @confirmed="$emit('destroy')">
                            <v-btn
                                color="error"
                                elevation="0"
                                class="mt-4"
                                :disabled="disabled"
                                :loading="restoreOrDeleteForm.processing">
                                {{ trans('Delete user') }}
                            </v-btn>
                        </confirms-modal>
                        <confirms-modal v-else @confirmed="$emit('restore')">
                            <v-btn
                                color="error"
                                outlined
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
    name: "AdminForm",

    components: {
        ConfirmsModal,
        TagCombobox
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
        restoreOrDeleteForm: {
            type: Object,
            required: false,
        },
        statuses: {
            type: Array,
            required: false,
        },
        roles: {
            type: Array,
            required: false,
        },
        admins: {
            type: Array,
            required: false
        },
        permissions: {
            type: Array,
            required: false,
        },
        occupationTypes:{
            type: Array,
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