<template>
    <v-container fluid fill-height class="primary pa-0 pt-0 pb-0">
        <v-carousel cycle :height="$vuetify.breakpoint.smAndDown ? '30vh' : 'calc(100vh - ' + $vuetify.application.top + 'px)'" hide-delimiter-background :show-arrows-on-hover="$vuetify.breakpoint.smAndDown ? true : false" delimiter-icon="mdi-minus">
            <v-carousel-item
                v-for="(data,index) in datas"
                :key="index"
                :src="data"
                style="cursor:pointer"
            ></v-carousel-item>
        </v-carousel>
    </v-container>
</template>

<script>
// importing stuff


export default {
    data() {
        return {
            datas:[],

        }
    },

    created() {
        this.get()
    },
    
    methods: {
        get() {
            for(let i=0; i<3; i++) {
                this.datas.push("https://picsum.photos/seed/picsum/1920/1080")
            }
        }
    },

    computed: {
        
    }
}
</script>