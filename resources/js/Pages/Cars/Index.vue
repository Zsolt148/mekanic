<template>
    <app-layout>
        <app-head title="Cars" />

        <cars-table />
    </app-layout>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout";
import CarsTable from "@/Components/Tables/CarsTable";

export default {
    name: "CarsIndex",
    components: { AppLayout, CarsTable },
    data(){
        return{
            tab: null,
        }
    },
};
</script>
