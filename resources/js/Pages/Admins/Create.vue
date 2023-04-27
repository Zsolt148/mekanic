<template>
    <app-layout>
        <app-head title="Create User" />

        <v-col class="page--header">
            <Link :href="route('admins.index')">
                {{ trans("Back to users") }}
            </Link>

            <h1 class="mt-2">
                {{ trans('Create User') }}
            </h1>
        </v-col>

        <admin-form 
            :form="form" 
            :roles="roles"
            :permissions="permissions" 
            :is-new="true"
            @submit="submit"
        />

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import AdminForm from "@/Pages/Admins/AdminForm";

export default {
    name: "Index",
    components: {
        AdminForm,
        AppLayout,
    },
    props: [
        'roles', 
        'permissions', 
    ],
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                email: "",
                roles: "",
                permissions: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("admins.store"));
        },
    },
};
</script>
