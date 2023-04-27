<template>
    <AppLayout>
        <app-head title="Dashboard" />

        <v-row>
            <v-col class="mt-0" cols="12">
                <v-card outlined class="pa-4">
                    <v-row class="mt-0">
                        <v-col cols="12" class="pt-0">
                            <v-card-title class="pt-1 ps-2">
                                {{ trans("Dashboard") }}
                            </v-card-title>

                            <v-card-subtitle class="ps-2 pb-0">
                                Welcome!
                            </v-card-subtitle>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
    </AppLayout>
</template>
<script>

import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Dashboard",
    components: {
        AppLayout,
    },
}
</script>
