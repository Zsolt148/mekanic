<template>
  <v-form>
    <v-row>
      <v-col lg="4">
        <v-text-field
          :disabled="disabled"
          v-model="form.name"
          :error-messages="form.errors.name"
          :label="trans('Name') + ' *'"
          name="name"
          hide-details="auto"
          type="text"
          filled
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col lg="4">
        <v-text-field
          :disabled="disabled"
          v-model="form.description"
          :error-messages="form.errors.description"
          :label="trans('Description')"
          name="description"
          hide-details="auto"
          type="text"
          filled
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col lg="4">
        <v-text-field
          :disabled="disabled"
          v-model="form.comment"
          :error-messages="form.errors.comment"
          :label="trans('Comment')"
          name="comment"
          hide-details="auto"
          type="text"
          filled
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col lg="4">
        <v-text-field
          :disabled="disabled"
          v-model="form.price"
          :error-messages="form.errors.price"
          :label="trans('Price') + ' *'"
          suffix="Ft"
          name="price"
          hide-details="auto"
          type="number"
          filled
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-btn
          color="primary"
          @click="$emit('submit')"
          elevation="0"
          class="me-3 mt-4"
          :disabled="disabled"
          :loading="form.processing"
        >
          <span v-if="isNew">{{ trans("New service") }}</span>
          <span v-else>{{ trans("Save changes") }}</span>
        </v-btn>
        <v-btn
            color="secondary"
            filled
            elevation="0"
            class="me-3 mt-4"
            @click="$inertia.visit(route('services.index'))"
        >
          {{ trans("Cancel") }}
        </v-btn>
      </v-col>
    </v-row>
  </v-form>
</template>

<script>
export default {
  name: "ServicesForm",

  props: {
    form: {
      type: Object,
      required: true,
    },
    isNew: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
