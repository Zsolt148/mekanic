<template>
    <v-dialog v-model="isVisible" max-width="800px" persistent @click:outside="close">
        <v-card v-if=log>
            <v-card-title>
                <span>
                    {{log.name}} - {{trans(log.description)}}
                    <span v-if="log.causer && log.event != 'login'">({{log.causer}})</span>
                </span>
                <v-spacer></v-spacer>
                <div>
                    <v-btn icon light @click="close">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </div>
            </v-card-title>
            <v-card-subtitle>
                {{ dateFormat(log.created_at) }}
            </v-card-subtitle>
            <v-card-text class="p-4">
                <v-row class="d-flex flex-column">
                    <div v-if="log.old_values">
                        <div class="d-flex changes__table">
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('Field')}}</b></span>
                                <span v-for="(item, name) in log.old_values" v-if="name != 'data'">
                                    {{trans(name) ? trans(name) : '-'}}
                                </span>
                            </div>
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('Old data')}}</b></span>
                                <span v-for="(item, name) in log.old_values" v-if="name != 'data'">
                                    {{ getValue(item, name) }}
                                </span>
                            </div>
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('New data')}}</b></span>
                                <span v-for="(item, name) in log.new_values" v-if="name != 'data'">
                                    {{ getValue(item, name) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="log.properties.attributes">
                        <div class="d-flex changes__table">
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('Field')}}</b></span>
                                <span v-for="(item, name) in log.properties.attributes" v-if="name != 'data'">
                                    {{trans(name) ? trans(name) : '-'}}
                                </span>
                            </div>
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('Value')}}</b></span>
                                <span v-for="(item, name) in log.properties.attributes" v-if="name != 'data'">
                                    {{ getValue(item, name) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="log.properties">
                        <div class="d-flex changes__table">
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('Field')}}</b></span>
                                <span v-for="(item, name) in log.properties">
                                    {{trans(name) ? trans(name) : '-'}}
                                </span>
                            </div>
                            <div class="d-flex flex-column" cols="4">
                                <span><b>{{trans('Value')}}</b></span>
                                <span v-for="(item, name) in log.properties">
                                    {{ getValue(item, name) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </v-row>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "LogsModal",
    props: {
        log: Object,
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