<template>
    <Head :title="title ? `${trans(title)} - Mekanic` : 'Mekanic'">
        <slot />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="revisit-after" content="18 days">
    </Head>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue'

export default {
    name: "AppHead",
    components: {
        Head,
    },
    props: {
        title: String,
    },
}
</script>
