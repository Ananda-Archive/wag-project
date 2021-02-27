<template>
    <v-main>
        <v-container fluid class="pa-0">
            <carousel></carousel>
            <products></products>
            <whatsapp-floating-button></whatsapp-floating-button>
        </v-container>
        
    </v-main>
</template>

<script>

// importing stuff
import carousel from "@/components/home/carousel"
import products from "@/components/home/products"
import WhatsappFloatingButton from '@/components/WhatsAppFloatingButton.vue'

export default {

    components: {
        carousel,products,WhatsappFloatingButton
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