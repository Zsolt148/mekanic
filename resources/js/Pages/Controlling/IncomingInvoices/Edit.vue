<template>
    <app-layout>
        <app-head :title="incoming.name" />

        <v-col class="page--header">
            <Link :href="route('incoming.index')">
                {{ trans("Back to incoming invoices") }}
            </Link>
    
            <h1>
                {{ trans('Edit incoming invoice') }}
            </h1>
        </v-col>

        <incoming-invoices-form
            :form="form"
            :payment_modes="payment_modes"
            :restore-or-delete-form="restoreOrDeleteForm"
            @submit="submit"
            @destroy="destroy"
            @restore="restore"
        />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import IncomingInvoicesForm from "@/Pages/Controlling/IncomingInvoices/IncomingInvocesForm";
export default {
    name: "Edit",
    components: {
        AppLayout,
        IncomingInvoicesForm
    },
    props: ['client', 'incoming', 'payment_modes'],
    data() {
        return {
        form: this.$inertia.form({
            id: this.incoming.id,
            created_by: this.$page.props.auth.user.id,
            id_number: this.incoming.id_number,
            subject: this.incoming.subject,
            net: this.incoming.net,
            gross: this.incoming.gross,
            payment_mode: this.incoming.payment_mode,
            fulfillment_date: this.incoming.fulfillment_date,
            expiration_date: this.incoming.expiration_date,
            issuer: Object.assign({}, this.incoming.issuer),
            type: Object.assign({}, this.incoming.type),
        }),
        restoreOrDeleteForm: this.$inertia.form({
            id: this.incoming.id
        }),
        }
    },
    methods: {
        submit() {
            this.form.patch(this.route('incoming.update', {incoming: this.incoming.id}))
        },
        destroy() {
            this.restoreOrDeleteForm.delete(this.route('incoming.destroy', {incoming: this.incoming.id}))
        },
        restore() {
            this.restoreOrDeleteForm.patch(this.route('incoming.restore', {incoming: this.incoming.id}))
        },
    }
}
</script>
