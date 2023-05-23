<template>
    <app-layout>
        <app-head title="Edit worksheet" />

        <v-col class="page--header">
            <Link :href="route('worksheets.index')">
                {{ trans("Back to worksheets") }}
            </Link>

            <h1 class="mt-2">
                {{ trans('Edit worksheet') }}
            </h1>
        </v-col>

        <worksheet-form
            :form="form"
            :restore-or-delete-form="restoreOrDeleteForm"
            :types="types"
            :cars="cars"
            :partners="partners"
            @submit="submit"
            @destroy="destroy"
        />

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import WorksheetForm from "@/Pages/Worksheets/WorksheetForm.vue";

export default {
    name: "Edit",
    components: {
        AppLayout,
        WorksheetForm
    },
    props: [
        'worksheet',
        'types',
        'cars',
        'partners',
    ],
    data() {
        return {
            form: this.$inertia.form({
                id: this.worksheet.id,
                type: this.worksheet.type,
                partner_id: this.worksheet.partner_id,
                car_id: this.worksheet.car_id,
                done_at: this.worksheet.done_at,
            }),
            restoreOrDeleteForm: this.$inertia.form({}),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("worksheets.store"));
        },
        destroy(sheetId) {
            this.restoreOrDeleteForm.delete(this.route("worksheets.destroy", sheetId))
        }
    },
};
</script>
