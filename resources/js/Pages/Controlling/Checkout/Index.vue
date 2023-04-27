<template>
    <app-layout>
        <app-head title="Checkouts" />

        <checkouts-table />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import CheckoutsTable from "@/Components/Tables/CheckoutsTable";

export default {
    name: "CheckoutsIndex",
    components: { AppLayout, CheckoutsTable },
    data(){
        return{
            tab: null,
        }
    },
};
</script>
