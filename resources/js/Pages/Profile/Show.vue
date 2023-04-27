<template>
    <app-layout>
        <app-head title="Profile" />

        <v-row cols="12" class="mt-4">
            <v-col cols="12">
                <div class="table--header">
                    <h1>
                        {{ trans('Profile') }}
                    </h1>
                        
                    <div>
                        <v-btn 
                            @click="logout"
                            elevation="0"
                            outlined
                            class="ms-md-2"
                            color="primary">
                            <v-icon class="me-2">mdi-logout</v-icon> {{trans("Logout")}}
                        </v-btn>
                    </div>
                </div>
            </v-col>
        </v-row>

        <v-tabs hide-arrows v-model="activeTab">
            <v-tab id="tab--overview">{{ trans('Account data') }}</v-tab>
        </v-tabs>

        <v-tabs-items v-model="activeTab">
            <v-tab-item>
                <overview-tab :user="$page.props.auth.user" />
            </v-tab-item>
        </v-tabs-items>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import OverviewTab from "./Tabs/OverviewTab";

export default {
    name: 'Show',
    components: {
        AppLayout,
        OverviewTab,
    },
    props: ['sessions', 'tabs', 'tab'],

    mounted() {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
    },

    data() {
        return {
            activeTab: this.tab,
            logoutForm: this.$inertia.form()
        }
    },

    methods: {
        logout() {
            this.logoutForm.post(this.route('logout'))
        },
    },

    watch: {
        activeTab() {
            if (history.pushState) {
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + this.tabs.find(x => x.text == val).value;
                window.history.pushState({path:newurl},'',newurl);
            }
        }
    }
}
</script>
