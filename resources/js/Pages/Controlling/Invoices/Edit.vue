<template>
<app-layout>
    <app-head :title="invoice.order_number" />

    <v-col class="page--header">
        <Link :href="route('invoices.index')">
            {{ trans("Back to invoices") }}
        </Link>

        <h1>
            {{ trans('Edit invoice') }}
        </h1>
    </v-col>

    <invoices-form
        :form="form"
        :payment_modes="payment_modes"
        :partners="partners"
        :partner="invoice.partner"
        :restore-or-delete-form="restoreOrDeleteForm"
        @submit="submit"
        @destroy="destroy"
        @restore="restore"
    />
</app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import InvoicesForm from "@/Pages/Controlling/Invoices/InvoicesForm";
export default {
    name: "Edit",
    components: {
        AppLayout,
        InvoicesForm
    },
    props: ['partners', 'invoice', 'payment_modes'],
    data() {
        return {
        form: this.$inertia.form({
            id: this.invoice.id,
            created_by: this.$page.props.auth.user.id,
            partner_id: this.invoice.partner_id,
            name: this.invoice.name,
            zip: this.invoice.zip,
            city: this.invoice.city,
            address: this.invoice.address,
            tax_number: this.invoice.tax_number,
            communal_tax_number: this.invoice.communal_tax_number,
            payment_mode: this.invoice.payment_mode,
            issue_date: this.invoice.issue_date,
            fulfillment_date: this.invoice.fulfillment_date,
            expiration_date: this.invoice.expiration_date,
            invoice_items: this.invoice.invoice_items,
            order_number: this.invoice.order_number,
            items: this.invoice.items,
            net: this.invoice.net,
            tax: this.invoice.tax,
            gross: this.invoice.gross,
            comment: this.invoice.comment,
            /*issuer: Object.assign({}, this.invoice.issuer),
            type: Object.assign({}, this.invoice.type),*/
        }),
        restoreOrDeleteForm: this.$inertia.form({
            id: this.invoice.id
        }),
        }
    },
    methods: {
        submit() {
            this.form.patch(this.route('invoices.update', {invoice: this.invoice.id}))
        },
        destroy() {
            this.restoreOrDeleteForm.delete(this.route('invoices.destroy', {invoice: this.invoice.id}))
        },
        restore() {
            this.restoreOrDeleteForm.patch(this.route('invoices.restore', {invoice: this.invoice.id}))
        },
    }
    }
</script>