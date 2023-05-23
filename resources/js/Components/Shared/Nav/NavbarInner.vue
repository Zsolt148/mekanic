<template>
    <nav>
        <v-navigation-drawer class="navbar--drawer" floating mini-variant-width="70" width="256" v-model="drawer"
                             :mini-variant="Boolean(mini)" app>
            <v-list-item class="navbar--logo" @click="toggleMini()">
                <a class="navbar-logo nav-link" aria-current="page">
                    <v-icon large dark>mdi-car-cog</v-icon>
                </a>

                <v-list-item-title class="navbar--title">Mekanic +</v-list-item-title>

                <v-btn dark class="ms-auto" icon @click.stop="mini ? maximise() : minify()">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider></v-divider>

            <v-list>
                <v-list-item
                    :class="{'active': isUrl(item.url)}"
                    @click="$inertia.visit(item.route)"
                    v-for="item in items"
                    v-show="item.show"
                    :key="item.title"
                    link>
                    <v-list-item-icon>
                        <v-icon>{{item.icon}}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title class="navbar--link">{{trans(item.title)}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-list class="navbar--profile">
                <v-list-item style="background: #DFDFDF;"
                             @click="$inertia.visit(route('profile.show', {tab: 'overview'}))" link>
                    <v-list-item-icon>
                        <v-icon>mdi-account-circle</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title class="navbar--link">{{trans('Profile')}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app flat sticky>
            <div class="boxed-container w-full">
                <div class="d-flex align-center">
                    <v-app-bar-nav-icon class="d-block d-lg-none me-2" @click="drawer = !drawer"></v-app-bar-nav-icon>
                    <app-search v-if="!$vuetify.breakpoint.mdAndDown"/>
                    <v-spacer></v-spacer>
                    <v-fade-transition mode="out-in" v-if="$page.props.hasAdminProxy">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon @click="$inertia.visit(route('admin-proxy.exit'))"
                                        class="mx-2"
                                        v-bind="attrs"
                                        v-on="on">
                                    mdi-undo
                                </v-icon>
                            </template>
                            <span>{{trans('Return to my user')}}</span>
                        </v-tooltip>
                    </v-fade-transition>
                    <user-menu/>
                </div>
            </div>
        </v-app-bar>
    </nav>
</template>

<script>
import UserMenu from '@/Components/Shared/Nav/UserMenu.vue'
import AppSearch from "@/Components/Shared/Nav/AppSearch.vue";

export default {
    name: 'NavbarInnerTest',
    components: {
        AppSearch,
        UserMenu
    },
    data() {
        return {
            drawer: this.$vuetify.breakpoint.mdAndDown ? false : true,
            mini: localStorage.getItem('mini') == '1',
            items: [
                {
                    title: 'Users',
                    icon: 'mdi-account-multiple',
                    route: this.route('admins.index'),
                    url: '/admins',
                    show: true
                },
                // {
                //     title: 'Inc. invoices',
                //     icon: 'mdi-receipt',
                //     route: this.route('incoming.index'),
                //     url: '/incoming',
                //     show: true
                // },
                {
                    title: 'Munkalapok',
                    icon: 'mdi-clipboard-text-outline',
                    route: this.route('worksheets.index'),
                    url: '/worksheets',
                    show: true
                },
                {
                    title: 'Számlák',
                    icon: 'mdi-receipt',
                    route: this.route('invoices.index'),
                    url: '/invoices',
                    show: true
                },
                {
                    title: 'Autók',
                    icon: 'mdi-car',
                    route: this.route('cars.index'),
                    url: '/cars',
                    show: true
                },
                {
                    title: 'Logs',
                    icon: 'mdi-note-multiple',
                    route: this.route('logs.index'),
                    url: '/logs',
                    show: this.hasRole('superadmin')
                },
            ],
        }
    },
    mounted() {
        if (localStorage.getItem('mini') !== null) {
            this.mini = localStorage.getItem('mini') === '1';
        }
    },
    methods: {
        minify() {
            this.mini = true;
            localStorage.setItem('mini', "1");
        },
        maximise() {
            this.mini = false;
            localStorage.setItem('mini', "0");
        },
        toggleMini() {
            this.mini = false; // set mini to false
            localStorage.setItem('mini', '0');
        },
    },
};

</script>