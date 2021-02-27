<template>
    <v-main>
        <v-container fluid class="pa-0">
            <carousel></carousel>
            <products></products>
        </v-container>
        
    </v-main>
</template>

<script>

// importing stuff
import carousel from "@/components/home/carousel"
import products from "@/components/home/products"

export default {

    components: {
        carousel,products
    },

    data() {
        return {

        }
    },

    created() {
        
    },
    
    methods: {

    },

    computed: {

    }
}
</script>