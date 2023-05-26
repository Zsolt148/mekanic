<template>
  <app-layout>
    <app-head :title="service.name" />

    <v-col class="page--header">
        <Link :href="route('services.index')">
            {{ trans("Back to services") }}
        </Link>

        <h1>
            {{ trans('Edit service') }}
        </h1>
    </v-col>

    <services-form
        :form="form"
        @submit="submit"
    />

  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout.vue";
import ServicesForm from './ServicesForm.vue';

export default {
  components: {
    AppLayout,
    ServicesForm
  },

  props:['service'],

  data() {
    return {
      form: this.$inertia.form({
        created_by: this.service.created_by,
        name: this.service.name,
        description: this.service.description,
        comment: this.service.comment,
        price: this.service.price,
      }),
    }
  },

  methods: {
    submit() {
      this.form.patch(this.route('services.update', this.service))
    }
  }

};
</script>
