<template>
    <app-layout>
        <app-head title="Create worksheet" />

        <v-col class="page--header">
            <Link :href="route('worksheets.index')">
                {{ trans("Back to worksheets") }}
            </Link>

            <h1 class="mt-2">
                {{ trans('Create worksheet') }}
            </h1>
        </v-col>

        <worksheet-form
            :form="form"
            :types="types"
            :cars="cars"
            :partners="partners"
            :is-new="true"
            @submit="submit"
        />

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import WorksheetForm from "@/Pages/Worksheets/WorksheetForm.vue";

export default {
    name: "Create",
    components: {
        AppLayout,
        WorksheetForm
    },
    props: [
        'types',
        'cars',
        'partners',
    ],
    data() {
        return {
            form: this.$inertia.form({
                type: "",
                partner_id: "",
                car_id: "",
                done_at: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("worksheets.store"));
        },
    },
};
</script>
