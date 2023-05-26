<template>
  <v-dialog
    v-model="isVisible"
    max-width="800px"
    persistent
    @click:outside="close"
  >
    <v-card v-if="service">
      <v-card-title>
        <span>
          {{ service.name }}
        </span>
        <v-spacer></v-spacer>
        <div>
          <v-btn icon light @click="close">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </div>
      </v-card-title>
      <v-card-subtitle>
        {{ dateFormat(service.created_at) }}
      </v-card-subtitle>
      <v-card-text class="p-4">
        <v-row>
            <v-col>
          <div
            style="font-size: 14px; color: black; width: 100%; margin-left: 1em"
          >
            {{ trans(service.description) }}
          </div>
        </v-col>
        <v-col sm="3" style="font-size: 16px; color: black; width: 100%; margin-left: 1em">
            {{ trans("Price") + ": " }}
            {{ service.price }}
        </v-col>
        </v-row>
        <v-row>
          <v-col sm="2"><v-btn color="orange" @click="edit"> Szerkesztés </v-btn></v-col>
          <v-col sm="2">
            <v-btn color="red"> Törlés </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ServicesModal",
  props: {
    service: Object,
    isVisible: Boolean,
  },
  methods: {
    close() {
      this.$emit("close");
    },
    edit(invoice) {
            this.$inertia.visit(this.route("services.edit", {service: this.service.id}));
        },
  },
};
</script>

<style scoped>
.changes__table {
  width: 100%;
  justify-content: space-between;
  margin-top: 30px;
}

.changes__table div {
  width: 100%;
}

.changes__table span {
  padding: 10px;
  width: 100%;
  border-bottom: thin solid rgba(0, 0, 0, 0.12);
}
</style>
