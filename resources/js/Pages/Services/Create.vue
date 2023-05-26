<template>
    <app-layout>
        <app-head title="New service"/>

        <v-col class="page--header">
            <Link :href="route('services.index')">
                {{ trans("Back to services") }}
            </Link>
    
            <h1>
                {{ trans('New service') }}
            </h1>
        </v-col>

        <services-form
            :is-new="true"
            :form="form"
            @submit="submit"
        />
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout.vue';
import ServicesForm from './ServicesForm.vue';

export default({
    components:{
        AppLayout,
        ServicesForm
    },

    data() {
        return {
            form: this.$inertia.form({
                created_by: this.$page.props.auth.user.id,
                name: null,
                description: null,
                comment: null,
            }),
        }
    },
    methods: {
        submit() {
            console.log(this.form)
        this.form.post(this.route('services.store'))
        }
    }
})

</script>