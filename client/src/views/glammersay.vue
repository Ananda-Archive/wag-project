<template>
    <v-main>
        <v-container fluid class="mt-md-16 mt-8 mb-16">
            <v-row justify="center">
                <v-col cols="12" class="text-center">
                    <div class="display-2">
                        What They Said About Our Products?
                    </div>
                </v-col>
                <v-col cols="8" class="text-center my-2"><v-divider></v-divider></v-col>
                <v-col cols="12" md="10">
                    <v-row no-gutters>
                        <v-col cols="12" md="6" v-for="(item,idx) in data" :key="idx" class="pr-8 pb-8">
                            <v-card style="border-left: 5px solid #819C4B; border-bottom: 5px solid #819C4B">
                                <v-card-title class="headline">{{item.customer.name}}</v-card-title>
                                <v-card-text>
                                    <v-rating
                                        empty-icon="mdi-star-outline"
                                        full-icon="mdi-star"
                                        half-icon="mdi-star-half-full"
                                        hover
                                        length="5"
                                        v-model="item.review"
                                        color="secondary"
                                        background-color="grey"
                                        half-increments
                                        readonly
                                        size="18"
                                        class="mt-n4 ml-n1"
                                    ></v-rating>
                                </v-card-text>
                                <v-card-subtitle class="font-italic mt-n6">On Product <span class="secondary--text font-weight-bold">{{item.product.name}}</span></v-card-subtitle>
                                <v-card-text class="font-italic mt-n2 text-justify">
                                    <div><v-icon>mdi-format-quote-open</v-icon></div>
                                    <div class="body-2">{{item.review_message}}</div>
                                    <div class="text-right"><v-icon>mdi-format-quote-close</v-icon></div>
                                </v-card-text>
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
import Itemorder from "@/services/Itemorder"

export default {
    data() {
        return {
            data:[]
        }
    },

    created() {
        this.getAll()
    },
    
    methods: {
        async getAll() {
            await Itemorder.getAll()
                .then((res) => this.data = res)
        }
    },

    computed: {
        
    }
}
</script>