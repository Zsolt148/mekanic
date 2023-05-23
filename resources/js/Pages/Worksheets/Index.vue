<template>
    <app-layout>
        <app-head title="Users" />

        <worksheets-table :worksheets="worksheets" :types="types" />

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import AdminsTable from "@/Components/Tables/AdminsTable.vue";
import WorksheetsTable from "@/Components/Tables/WorksheetsTable.vue";

export default {
    name: "Index",
    props: ['worksheets', 'types', 'cars', 'partners'],
    components: {WorksheetsTable, AppLayout, AdminsTable },
    data() {
        return {
        }
    },
};
</script>
