<template>
    <app-layout>
        <app-head title="Incoming invoices" />

        <incoming-invoices-table />
    </app-layout>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout";
import IncomingInvoicesTable from "@/Components/Tables/IncomingInvoicesTable";

export default {
    name: "IncomingInvoicesIndex",
    components: { AppLayout, IncomingInvoicesTable },
    data(){
        return{
            tab: null,
        }
    },
};
</script>
