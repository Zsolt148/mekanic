<template>
    <v-footer inset height="56" class="px-0" app>
        <div class="boxed-container w-full">
            <div class="mx-6 d-flex justify-center">
                <span class="ml-2">
                    {{ getVersion() }}
                </span>
                <span class="ms-2">
                    &copy; {{ new Date().getFullYear() }} -
                </span>
            </div>
        </div>
    </v-footer>
</template>