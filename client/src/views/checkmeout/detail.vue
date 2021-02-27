<template>
    <v-main>
        <v-container fluid class="pt-10 px-md-16">
            <v-row justify-center class="px-md-16 py-md-10">
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="5" xl="4">
                            <v-col cols="12">
                                <v-card width="100%">
                                    <v-img style="cursor: pointer" :height="[$vuetify.breakpoint.xl? '500px' : '500px']" :src=product.imageLead[0].image :lazy-src=product.images[0].image @click="index = 0">
                                        <template v-slot:placeholder>
                                            <v-row
                                                class="fill-height ma-0"
                                                align="center"
                                                justify="center"
                                            >
                                                <v-progress-circular indeterminate color="secondary"></v-progress-circular>
                                            </v-row>
                                        </template>
                                    </v-img>
                                </v-card>
                            </v-col>
                            <v-col cols="12">
                                <v-row v-if="product.images.length>0">
                                    <v-col cols="12" class="">
                                        <v-row v-if="product.images.length>3">
                                            <v-col cols="4" v-for="(n,idx) in 3" :key="idx">
                                                <v-card width="100%">
                                                    <v-img style="cursor:pointer" :height="[$vuetify.breakpoint.xl? '150px' : '100px']" :src=product.images[n-1].image :lazy-src=product.images[n-1] @click="index = n">
                                                        <v-container fluid fill-height style="background-color:rgba(0,0,0,.4)" v-if="n == 3">
                                                            <v-row justify-center align-center >
                                                                <v-col cols="12" class="text-center white--text">+{{product.images.length-3}}</v-col>
                                                            </v-row>
                                                        </v-container>
                                                        <template v-slot:placeholder>
                                                            <v-row
                                                                class="fill-height ma-0"
                                                                align="center"
                                                                justify="center"
                                                            >
                                                                <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                                            </v-row>
                                                        </template>
                                                    </v-img>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                        <v-row v-else>
                                            <v-col cols="4" v-for="(n,idx) in product.images.length" :key="idx">
                                                <v-card width="100%">
                                                    <v-img style="cursor:pointer" :height="[$vuetify.breakpoint.xl? '150px' : '100px']" :src=product.images[n-1].image :lazy-src=product.images[n-1] @click="index = n">
                                                        <template v-slot:placeholder>
                                                            <v-row
                                                                class="fill-height ma-0"
                                                                align="center"
                                                                justify="center"
                                                            >
                                                                <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                                            </v-row>
                                                        </template>
                                                    </v-img>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-col>
                        <v-col class="pl-md-8 pl-5" cols="12" md="7" xl="8">
                            <div class="display-1">{{product.name}}</div>
                            <div class="d-flex flex-no-wrap justify-start align-center ml-n1 mt-2">
                                <div>
                                    <v-rating
                                        empty-icon="mdi-star-outline"
                                        full-icon="mdi-star"
                                        half-icon="mdi-star-half-full"
                                        hover
                                        length="5"
                                        v-model="calculateRating"
                                        color="secondary"
                                        background-color="grey"
                                        half-increments
                                        readonly
                                        size="18"
                                    ></v-rating>
                                </div>
                                <div class="caption">({{product.review.length}} Customer Review)</div>
                            </div>
                            <div class="mt-6 ml-1 mt-md-2">
                                <span class="headline" :class="[product.discountStatus == 1?'secondary--text':null]">Rp </span>
                                <span class="display-1 secondary--text" v-if="product.discountStatus == 1">{{changeCurr(discountCurr(product.price,product.discount))}}</span>
                                <span class="display-1" v-else>{{changeCurr(product.price)}}</span>
                                <span class="body-1 ml-2 text-decoration-line-through" v-if="product.discountStatus == 1">{{changeCurr(product.price)}}</span>
                            </div>
                            <div class="mt-8">
                                <span v-if="product.stock>0">In-stock </span>
                                <span v-else class="red--text">Out of Stock</span>
                                <span class="secondary--text" v-if="product.stock>0">({{product.stock}})</span>
                                <span class="mx-4">|</span>
                                <span>Weight </span>
                                <span class="secondary--text">{{product.weight}} Gram</span>
                                <span class="mx-4">|</span>
                                <span>Skin Type </span>
                                <span class="secondary--text">{{skinType(product.skinType)}}</span>
                            </div>
                            <div class="ml-0 pl-0">
                                <v-btn class="mt-6 secondary" dark @click="addToCart(product)">Add To Cart</v-btn>
                            </div>
                            <div class="mt-6 text-justify"  v-html="product.ingredients"></div>
                        </v-col>
                    </v-row>
                    
                </v-col>
            </v-row>
        </v-container>
        <Tinybox
            v-model="index"
            :images="productImages"
            loop
        />
    </v-main>
</template>

<script>
// importing stuff
import ProductService from '@/services/Product'
import Tinybox from "vue-tinybox"
import _ from "lodash"

export default {
    components: { Tinybox },
    data() {
        return {
            index:null,
            product: {
                discount:"",
                discountStatus:"",
                id:"",
                imageLead:[],
                images:[],
                ingredients:"",
                name:"",
                price:"",
                skinType:"",
                stock:"",
                weight:""
            },
            productImages:[],
            skinTypes: [
                {id:"1", name:"Have my own glam! - Normal skin"},
                {id:"2", name:"Bye bye shiny! - Oily skin"},
                {id:"3", name:"Make me dewy! - Dry skin"},
                {id:"4", name:"Acne go away! - Acne prone"}
            ],
            selectedProducts:this.$store.state.product.products
        }
    },

    created() {
        this.get()
    },
    
    methods: {
        addToCart(product) {
            let item = {
                id: product.id,
            }
            if(_.find(this.selectedProducts, ['id',item.id]) != undefined) {
                if(_.find(this.selectedProducts, ['id',item.id]).quantity<product.stock) {
                    this.$store.dispatch("addToCart",item)
                } else {
                    alert("Your Cart Exceed the Stock")
                }
            } else {
                this.$store.dispatch("addToCart",item)
            }
        },
        async getProduct() {
            await ProductService.getById(this.$route.params.id)
                .then((response) => {
                    this.product = response
                    this.productImages.push(response.imageLead[0].image)
                    this.product.images.forEach(e => {
                        this.productImages.push(e.image)
                    });
                })
        },
        async get() {
            await this.getProduct()
        },
        changeCurr(val) {
            let temp = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'IDR' }).format(val)
            return temp.slice(0, -4)
        },
        discountCurr(val,disc) {
            return val-(val*disc/100)
        },
        skinType(val) {
            return _.find(this.skinTypes, ["id",val]).name
        }
    },

    computed: {
        calculateRating() {
            let total = 0.0
            this.product.review.forEach(e => {
                total+=+e.review
            });
            total = total/this.product.review.length
            return total
        },
    },

    watch: {
        'this.$store.state.product.products': function() {
            this.selectedProducts = this.$store.state.product.products
        }
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