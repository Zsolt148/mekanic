<template>
    <auth-layout>
        <app-head :title="trans('Forgot Password?')" />
        <v-layout align-center justify-center>
            <v-card elevation="0" outlined class="auth--card">

                <Link class="auth--back-link" :href="route('login')">
                    {{ trans("Back to login") }}
                </Link>

                <h1 class="auth--card-header">
                    {{ trans('Forgotten password') }}
                </h1>

                <p class="auth--card-alt">
                    {{ trans('We will send you an email through which you can enter a new password.') }}
                </p>

                <v-card-text class="pa-0">
                    <v-form>
                        <v-text-field
                            v-model="form.email"
                            :error-messages="form.errors.email"
                            autocomplete="email"
                            name="email"
                            id="email"
                            filled
                            label="Email"
                            placeholder="john@example.com" />

                        <v-btn 
                            id="send--link-btn" 
                            @click="submit" 
                            v-on:keyup.enter="submit" 
                            elevation="0"
                            block 
                            color="primary" 
                            :disabled="form.processing" 
                            :loading="form.processing">
                            {{ trans("Delete old password") }}
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-layout>
    </auth-layout>
</template>

<script>
import AuthLayout from "@/Layouts/AuthLayout.vue";

export default {
    name: 'ForgotPassword',
    components: {
        AuthLayout
    },
    props: {
        status: String,
    },
    data() {
        return {
            form: this.$inertia.form({
                email: '',
            })
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('password.email'))
        }
    }
};
</script>
