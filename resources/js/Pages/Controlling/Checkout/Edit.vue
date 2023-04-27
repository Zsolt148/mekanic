<template>
    <app-layout>
        <app-head :title="checkout.name" />

        <v-col class="page--header">
            <Link :href="route('checkout.index')">
                {{ trans("Back to checkouts") }}
            </Link>

            <h1>
                {{ trans('Edit checkout') }}
            </h1>
        </v-col>

        <checkouts-form
            :form="form"
            :types="types"
            :restore-or-delete-form="restoreOrDeleteForm"
            @submit="submit"
            @destroy="destroy"
            @restore="restore"
        />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import CheckoutsForm from "@/Pages/Controlling/Checkout/CheckoutsForm";
export default {
    name: "Edit",
    components: {
        AppLayout,
        CheckoutsForm
    },
    props: ['checkout', 'types'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.checkout.id,
                created_by: this.$page.props.auth.user.id,
                amount: this.checkout.amount,
                claimant: this.checkout.claimant,
                receipt_number: this.checkout.receipt_number,
                date: this.checkout.date,
                type: this.checkout.type,
                title: Object.assign({}, this.checkout.title),
            }),
            restoreOrDeleteForm: this.$inertia.form({
                id: this.checkout.id
            }),
        }
    },
    methods: {
        submit() {
            this.form.patch(this.route('checkout.update', {checkout: this.checkout.id}))
        },

        destroy() {
            this.restoreOrDeleteForm.delete(this.route('checkout.destroy', {checkout: this.checkout.id}))
        },
        
        restore() {
            this.restoreOrDeleteForm.patch(this.route('checkout.restore', {checkout: this.checkout.id}))
        },
    }
}
</script>