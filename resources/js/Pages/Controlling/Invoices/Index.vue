<template>
    <app-layout>
        <app-head title="Out invoices" />

        <invoices-table />
    </app-layout>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout";
import InvoicesTable from "@/Components/Tables/InvoicesTable";

export default {
    name: "InvoicesIndex",
    components: { AppLayout, InvoicesTable },
    data(){
        return{
            tab: null,
        }
    },
};
</script>
