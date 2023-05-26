<template>
  <div>
    <v-row class="mt-0">
      <v-col md="12" cols="12">
        <v-row cols="12" class="mt-0">
          <v-col cols="12">
            <div class="table--header">
              <h1>
                {{ trans("Services") }}
              </h1>
            </div>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <v-row>
      <v-col col="12" md="4">
        <v-text-field
          v-model="search"
          :placeholder="trans('Search')"
          @input="handleSearch"
          prepend-inner-icon="mdi-magnify"
          id="search"
          ref="search"
          filled
          clearable
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col col="2">
        <v-btn @click="getServices" outlined>
          <v-icon>mdi-refresh</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row>
      <v-col>
        <v-data-table
          @click:row="showServiceModal"
          :headers="headers"
          :loading="loading"
          :items="services"
          :options="options"
          :hide-default-footer="true"
          item-key="id"
          role="button"
        >
          <template v-slot:item.name="{ item }">
            <span>
              <b class="table--bold"
                >{{ item.name }}</b
              >
            </span>
          </template>

          <template v-slot:item.description="{ item }">
            <p class="table--field" v-if="item.description">
              {{ trans(item.description) }}
            </p>
          </template>

          <template v-slot:item.created_at="{ item }">
            <p class="table--field">
              {{ dateFormat(item.created_at) }}
            </p>
          </template>
        </v-data-table>

        <div class="d-flex justify-center py-4">
          <v-select
            :label="trans('Items per page')"
            class="pe-4 pt-2"
            v-model="options.servicesPerPage"
            @change="getServices"
            :items="[5, 10, 15, 20]"
            style="max-width: 100px"
            dense
          />
          <v-pagination
            class=""
            v-model="page"
            @input="getServices"
            :total-visible="options.servicesPerPage"
            :length="totalServices"
          />
        </div>
      </v-col>
    </v-row>

    <services-modal
            :isVisible="isServiceModalVisible"
            :log="selectedService"
            @close="hideServiceModal"
        />
  </div>
</template>

<script>
import ServicesModal from '../../Pages/Services/ServicesModal.vue';

export default {
  data: function () {
    return {
      search: "",
      selectedService: null,
      isServiceModalVisible: false,
      services: [],
      page: 1,
      loading: true,
      totalServices: null,
      options: {
        page: 1,
        servicesPerPage: 10,
      },
      headers: [
        { text: this.trans("Name"), align: "start", value: "name" },
        { text: this.trans("Description"), align: "start", value: "description" },
        { text: this.trans("Created At"), align: "start", value: "created_at" },
      ],
    };
  },

  components:{
    ServicesModal
  },

  methods:{
    showServiceModal(service) {
            this.selectedService = service;
            this.isServiceModalVisible = true;
        },
        hideServiceModal() {
            this.isServiceModalVisible = false;
        },
        handleSearch: _.debounce(function (e) {
            this.getServices();
        }, 500),
        async getServices() {
            this.loading = true;
            this.options.page = this.page;
            await axios.get(this.route('api:services.table'), {
                params: {
                    page: this.options.page,
                    per_page: this.options.servicesPerPage,
                    search: this.search,
                    name: this.name ? this.name : null,
                    id: this.id ? this.id : null,
                }
            }).then((response) => {
                console.log(response)
                this.services = response.data.data;
                this.totalServices = response.data.total;
            })
            .catch((error) => {
                console.log(error);
            })
            .finally(() => {
                this.loading = false;
            });
        },
  },

  created() {
        this.getServices();
    },
};
</script>
