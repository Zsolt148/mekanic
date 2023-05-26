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

  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout.vue";

export default {
  components: {
    AppLayout,
  },

  props:['service']
};
</script>
