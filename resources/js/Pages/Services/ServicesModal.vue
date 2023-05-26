<template>
    <v-dialog v-model="isVisible" max-width="800px" persistent @click:outside="close">
        <v-card v-if=service>
            <v-card-title>
                <span>
                    {{service.name}} - {{trans(service.description)}}
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
                <v-row class="d-flex flex-column">
                    <div></div>
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
        getValue(value, name) {
            if (value) {

                if (['created_at', 'updated_at', 'deleted_at', 'blocked_at', 'last_login_at'].includes(name)) {
                    var timestamp = Date.parse(value)

                    if (isNaN(timestamp) == false) {
                        return this.dateFormat(value)
                    }
                }

                return value
            }

            return '-'
        }
    },
};
</script>


<style scoped>
    .changes__table{
        width: 100%;
        justify-content: space-between;
        margin-top: 30px;
    }

    .changes__table div{
        width: 100%;
    }

    .changes__table span{
        padding: 10px;
        width: 100%;
        border-bottom:  thin solid rgba(0, 0, 0, 0.12);
    }
</style>