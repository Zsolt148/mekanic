<template>
<app-layout>
    <app-head title="Create new incoming invoice" />

    <v-col class="page--header">
        <Link :href="route('incoming.index')">
            {{ trans("Back to incoming invoices") }}
        </Link>

        <h1>
            {{ trans('Create new incoming invoice') }}
        </h1>
    </v-col>
    
    <incoming-invoices-form
        :is-new="true"
        :form="form"
        :payment_modes="payment_modes"
        @submit="submit"
    />
</app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import IncomingInvoicesForm from "@/Pages/Controlling/IncomingInvoices/IncomingInvocesForm";

export default {
    name: "Create",
    components: {
        AppLayout,
        IncomingInvoicesForm
    },
    props: ['payment_modes'],
    data() {
        return {
            form: this.$inertia.form({
                created_by: this.$page.props.auth.user.id,
                id_number: null,
                subject: null,
                net: null,
                gross: null,
                payment_mode: null,
                fulfillment_date: null,
                expiration_date: null,
                issuer: {},
                type: {},
            }),
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('incoming.store'))
        }
    }
}
</script>