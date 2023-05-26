<template>
    <app-layout>
        <app-head :title="null" />

        <v-col class="page--header">
            <Link :href="route('cars.index')">
            {{ trans("Back to cars") }}
            </Link>

            <h1>
                {{ trans('Edit car') }}
            </h1>
        </v-col>

        <car-form :form="form" 
        :partners="partners" 
        :car="car" 
        :restore-or-delete-form="restoreOrDeleteForm"
        :isNew="false"
        @submit="submit" 
        @destroy="destroy" 
        @restore="restore" />
    </app-layout>
</template>
    
<script>
import AppLayout from "@/Layouts/AppLayout";
import carForm from "@/Pages/Cars/CarsForm";
export default {
    name: "Edit",
    components: {
        AppLayout,
        carForm
    },
    props: ['partners', 'car'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.car.id,
                created_by: this.$page.props.auth.user.id,
                partner_id: this.partners[0].id,
                name: this.partners[0].name,
                selectedPartner: this.partners[0].name,
                zip: this.partners[0].zip,
                city: this.partners[0].city,
                address: this.partners[0].address,
                tax_number: this.partners[0].tax_number,
                communal_tax_number: this.partners[0].communal_tax_number,
                brand: this.car.brand,
                type: this.car.type,
                license_plate: this.car.license_plate,
                mileage: this.car.mileage,
                chassis_number: this.car.chassis_number,
                motor_number: this.car.motor_number,
                vintage: this.car.vintage,
                ti_validity: this.car.ti_validity,
            }),
            restoreOrDeleteForm: this.$inertia.form({
                id: this.car.id,
            }),
        }
    },
    methods: {
        submit() {
            this.form.patch(this.route('cars.update', { car: this.car.id }))
        },
        destroy() {
            this.restoreOrDeleteForm.delete(this.route('cars.destroy', { car: this.car.id }))
        },
        restore() {
            this.restoreOrDeleteForm.patch(this.route('cars.restore', { car: this.car.id }))
        },
    }
}
</script>