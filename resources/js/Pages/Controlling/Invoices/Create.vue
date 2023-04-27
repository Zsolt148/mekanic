<template>
    <app-layout>
        <app-head title="Create new invoice" />

        <v-col class="page--header">
            <Link :href="route('invoices.index')">
                {{ trans("Back to invoices") }}
            </Link>
    
            <h1>
                {{ trans('Create new invoice') }}
            </h1>
        </v-col>

        <invoices-form
            :is-new="true"
            :form="form"
            :payment_modes="payment_modes"
            :partners="partners"
            @submit="submit"
        />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import InvoicesForm from "@/Pages/Controlling/Invoices/InvoicesForm";

export default {
    name: "Create",
    components: {
        AppLayout,
        InvoicesForm
    },
    props: ['payment_modes', 'partners'],
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
                payment_mode: null,
                issue_date: null,
                fulfillment_date: null,
                expiration_date: null,
                invoice_items: [],
                order_number: null,
                items: null,
                net: 0,
                tax: 0,
                gross: 0,
                comment: null,
            }),
        }
    },
    methods: {
        submit() {
        this.form.post(this.route('invoices.store'))
        }
    }
}
</script>