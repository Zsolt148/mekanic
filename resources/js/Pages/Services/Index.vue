<template>
    <app-layout>
        <app-head title="Services" />
        
        <services-table />

    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import ServicesTable from '../../Components/Tables/ServicesTable.vue';

export default{
    name: "Index",
    components: {
        AppLayout,
        ServicesTable
    },

}

</script>