<template>
    <v-main class="ma-md-10 pb-10">
        <v-container fluid>
            <v-row justify="center">
                <v-col cols="12" md="8">
                    <v-img @click="index=0" :height="[$vuetify.breakpoint.xl? '600px' : '400px']" :src="data.image"></v-img>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col cols="12" class="text-center mt-4">
                    <div class="display-1">{{data.title}}</div>
                </v-col>
                <v-col cols="12" class="text-justify">
                    <div v-html="data.content"></div>
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
            id:this.$route.params.id,
            data:{
                
            }
        }
    },

    created() {
        this.getData()
    },
    
    methods: {
        async getData() {
            await Selfdiscovery.getById(this.id)
                .then((res) => this.data = res)
        }
    },

    computed: {
        
    }
}
</script>