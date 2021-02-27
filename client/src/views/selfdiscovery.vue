<template>
    <v-main>
        <v-container fluid  class="pt-16 px-md-16">
            <v-row justify-center>
                <v-col cols="12">
                    <v-row no-gutters>
                        <v-col cols="12" md="4" v-for="(item,idx) in selfDiscoveries" :key="idx" class="pb-8" :class="[$vuetify.breakpoint.smAndDown? null : 'pr-6']">
                            <v-card width="600px" align="left" @click="goTo('/selfdiscovery/'+item.id)">
                                <v-img
                                    aspect-ratio="1"
                                    height="400px" 
                                    :lazy-src="item.image" 
                                    :src="item.image"
                                    gradient="to bottom, rgba(0,0,0,.4), rgba(0,0,0,.8)"
                                    class="white--text align-end"
                                >
                                    <template v-slot:placeholder>
                                        <v-row
                                            class="fill-height ma-0"
                                            align="center"
                                            justify="center"
                                        >
                                            <v-progress-circular indeterminate color="black"></v-progress-circular>
                                        </v-row>
                                    </template>
                                    <v-card-title ><div class="headerClass">{{item.title}}</div></v-card-title>
                                </v-img>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</template>

<script>
// importing stuff
import Selfdiscovery from "@/services/Selfdiscovery"


export default {
    data() {
        return {
            selfDiscoveries:[]
        }
    },

    created() {
        this.get()
    },
    
    methods: {
        async getAll() {
            await Selfdiscovery.getAll()
                .then((response) => {
                    this.selfDiscoveries = response
                })
        },

        async get() {
            await this.getAll()
        },

        goTo(path) {
            this.$router.push(path)
            window.scroll(0,0)
        },

    },

    computed: {
        
    }
}
</script>
<style>
.headerClass{
    white-space: nowrap ;
    word-break: normal;
    overflow: hidden ;
    text-overflow: ellipsis;
}
</style>