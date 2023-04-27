<template>
    <app-layout>
        <app-head title="Create new checkout" />

        <v-col class="page--header">
            <Link :href="route('checkout.index')">
                {{ trans("Back to checkouts") }}
            </Link>

            <h1>
                {{ trans('Create new checkout') }}
            </h1>
        </v-col>

        <checkouts-form
            :is-new="true"
            :form="form"
            :types="types"
            @submit="submit"
        />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import CheckoutsForm from "@/Pages/Controlling/Checkout/CheckoutsForm";
export default {
    name: "Create",
    components: {
        AppLayout,
        CheckoutsForm
    },
    props: ['types'],
    data() {
        return {
        form: this.$inertia.form({
            created_by: this.$page.props.auth.user.id,
            amount: null,
            claimant: null,
            receipt_number: null,
            date: null,
            type: null,
            title: {},
        }),
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('checkout.store'))
        }
    }
}
</script>