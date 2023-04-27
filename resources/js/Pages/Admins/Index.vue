<template>
    <app-layout>
        <app-head title="Users" />

        <v-row class="mt-2">
            <v-col cols="12">
                <div class="table--header">
                    <h1>
                        {{ trans('Users') }}
                    </h1>
                        
                    <div>
                        <v-btn 
                            @click="$inertia.visit(route('admins.create'))"
                            elevation="0"
                            color="primary">
                            <v-icon class="me-2">mdi-plus</v-icon> {{ trans("Add new user") }}
                        </v-btn>
                    </div>
                </div>
            </v-col>
        </v-row>

        <v-tabs v-model="tab" >
            <v-tab>
                <span>{{ trans("Registered users") }}</span>
            </v-tab>

            <v-tab>
                <span>{{ trans("Deleted users") }}</span>
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item>
                <admins-table :admins="admins" :roles="roles" :statuses="statuses" />
            </v-tab-item>
            
            <v-tab-item>
                <admins-table :admins="deletedAdmins" :roles="roles" :statuses="statuses" />
            </v-tab-item>
        </v-tabs-items>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import AdminsTable from "@/Components/Tables/AdminsTable.vue";

export default {
    name: "Index",
    props: ['admins', 'deletedAdmins', 'roles', 'statuses'],
    components: { AppLayout, AdminsTable },
    data(){
        return{
            tab: null,
        }
    },
};
</script>
