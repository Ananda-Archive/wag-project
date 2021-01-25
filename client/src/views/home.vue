<template>
    <v-main>
        <v-container fluid class="pa-0">
            <carousel></carousel>
            <products></products>
            <homefooter></homefooter>
        </v-container>
        <footerlayout></footerlayout>
    </v-main>
</template>

<script>

// importing stuff
import carousel from "@/components/home/carousel"
import products from "@/components/home/products"
import homefooter from "@/components/home/homefooter"
import footerlayout from "@/components/footer"

export default {

    components: {
        carousel,products,homefooter,footerlayout
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