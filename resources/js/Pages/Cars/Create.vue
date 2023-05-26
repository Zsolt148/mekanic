<template>
<app-layout>
    <app-head title="Add new car" />

    <v-col class="page--header">
        <Link :href="route('cars.index')">
            {{ trans("Back to cars") }}
        </Link>

        <h1>
            {{ trans('Add new car') }}
        </h1>
    </v-col>
    
    <cars-form
        :is-new="true"
        :form="form"
        :partners="partners"
        @submit="submit"
    />
</app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import CarsForm from "@/Pages/Cars/CarsForm";

export default {
    name: "Create",
    components: {
        AppLayout,
        CarsForm
    },
    props: ['partners'],
    data() {
        return {
            form: this.$inertia.form({
                created_by: this.$page.props.auth.user.id,
                partner_id: null,
                name: null,
                zip: null,
                city: null,
                address: null,
                tax_number: null,
                communal_tax_number: null,
                brand: null,
                type: null,
                license_plate: null,
                mileage: 0,
                chassis_number: null,
                motor_number: null,
                vintage: 1950,
                ti_validity: null,
            }),
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('cars.store'))
        }
    }
}
</script>