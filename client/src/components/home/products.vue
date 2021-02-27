<template>
    <v-container fluid class="text-center px-8 px-md-0 py-6">
        <h1 :class="[$vuetify.breakpoint.smAndDown ? 'headline' : 'display-2']" class="font-weight-bold mb-3">OUR PRODUCTS</h1>
        <v-responsive class="mx-auto mb-8" width="56">
            <v-divider class="mb-1"></v-divider>
            <v-divider></v-divider>
        </v-responsive>
        <v-col cols="12" class="pa-0 px-md-12">
            <v-row no-gutters align="center">
                <v-col cols="12" md="5">
                    <v-img :src="products[0].img" :height="$vuetify.breakpoint.smAndDown?'200px':'450px'"></v-img>
                </v-col>
                <v-col cols="12" md="7" :class="[$vuetify.breakpoint.smAndDown? 'mt-2':null]" class="text-left pl-0 pl-md-16">
                    <h1 class="mb-n1">{{products[0].title}}</h1>
                    <div class="titleLine"></div>
                    <p class="text-justify">{{products[0].desc}}</p>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" class="pa-0 px-md-12 pt-10">
            <v-row no-gutters align="center">
                <v-col cols="12" md="7" :class="[$vuetify.breakpoint.smAndDown? 'text-left mt-2':'text-right']" class="pr-0 pr-md-16" :order="$vuetify.breakpoint.smAndDown ? 2:1">
                    <h1 class="mb-n1">{{products[1].title}}</h1>
                    <div :class="[$vuetify.breakpoint.smAndDown? null:'lineRight']" class="titleLine"></div>
                    <p class="text-justify">{{products[1].desc}}</p>
                </v-col>
                <v-col cols="12" md="5" :order="$vuetify.breakpoint.smAndDown ? 1:2">
                    <v-img :src="products[1].img" :height="$vuetify.breakpoint.smAndDown?'200px':'450px'"></v-img>
                </v-col>
            </v-row>
        </v-col>
    </v-container>
</template>

<script>

import _ from "lodash"

export default {
    data() {
        return {
            products:[],
            product: {
                title:'',
                desc:'',
                img:''
            },
            productDefault: {
                title:'',
                desc:'',
                img:''
            }
        }
    },

    created() {
        this.get()
    },
    
    methods: {
        get() {
            for(let i=0; i<2; i++) {
                this.product.title = "Lorem Ipsum"
                this.product.desc = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu urna pellentesque, eleifend eros id, imperdiet turpis. Suspendisse dictum nulla diam. Nulla facilisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras interdum dui turpis, sed bibendum risus vulputate eu. Maecenas aliquet justo in dapibus placerat. Quisque auctor eros eu nulla feugiat, rutrum vestibulum elit feugiat. Aliquam iaculis, tellus in facilisis iaculis, ipsum nunc congue lacus, nec pellentesque dolor metus ut ligula. Pellentesque commodo tellus venenatis, iaculis urna quis, fringilla tortor. Etiam elit tortor, viverra non tincidunt nec, interdum non nisl. In non est eros. Integer sed dolor mauris. Sed eget ornare purus, vel cursus odio."
                this.product.img = "https://picsum.photos/seed/picsum/800/800"
                this.products.push(this.product)
                this.product = _.clone(this.productDefault)
            }
        }
    },

    computed: {
        
    }
}
</script>

<style>
.titleLine {
    display: block;
    width: 130px;
    height: 5px;
    background: #819C4B;
    margin: 10px 0;
    left: 0;
}
.lineRight {
    margin-left: auto;
}
</style>