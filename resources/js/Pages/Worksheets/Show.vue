<template>
    <app-layout>
        <app-head :title="user.name" />

        <v-col class="page--header">
            <Link :href="route('admins.index')">
                {{ trans("Back to users") }}
            </Link>

            <h1 class="mt-2">
                {{ trans('Show user') }}
            </h1>
        </v-col>

        <v-tabs v-model="tab" class="tabs">
            <v-tab>
                <span>{{ trans("Account") }}</span>
            </v-tab>
            <v-tab>
                <span>{{ trans("History") }}</span>
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item>
                <admin-form :form="form" :disabled="true" />
            </v-tab-item>
            
            <v-tab-item>
                <logs-table :subject="user" />
            </v-tab-item>
        </v-tabs-items>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import AdminForm from "@/Pages/Admins/AdminForm";
import LogsTable from '@/Components/Tables/LogsTable';

export default {
    components: {
        AdminForm,
        AppLayout,
        LogsTable
    },
    props: ["user"],

    data() {
        return {
            tab: null,

            form: this.$inertia.form({
                method: "_PUT",
                id: this.user.id,
                photo: this.user.photo,
                trashed: this.user.trashed,
                name: this.user.name,
                email: this.user.email,
                status: this.user.status,
                roles: this.user.roleText,
                permissions: this.user.permissionText,
            }),
        };
    },
};
</script>
