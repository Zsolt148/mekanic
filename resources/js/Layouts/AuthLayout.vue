<template>
    <v-app>
        <v-app-bar style="height:70px; padding-left:0" class="auth--toolbar ps-0" app flat sticky>
            <a @click.prevent="$inertia.visit(route('login'))" class="auth--logo ps-0" aria-current="page">
                <v-icon large dark class="ps-4">mdi-car-cog</v-icon>
            </a>
        </v-app-bar>
        <v-main class="auth--container">
            <v-container class="d-flex h-full align-center justify-center">
                <flash-messages :flash="$page.props.flash" />
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import FlashMessages from "@/Components/Shared/FlashMessages";
export default {
    name: "AuthLayout",
    components: {
        FlashMessages
    }
}
</script>
