<template>
    <v-main>
        <v-container fluid class="pt-10 px-md-16">
            <v-row no-gutters>
                <v-col cols="3"></v-col>
                <v-col cols="12" md="9" class="pl-md-16 mb-6"><div class="display-1 mb-3">Produk</div><v-divider></v-divider></v-col>
                <v-col cols="3" v-if="!$vuetify.breakpoint.smAndDown">
                    <v-card elevation="2" width="100%" class="pt-4 toolbar pb-n4">
                        <h3 class="ml-4 mb-2">Filter</h3>
                        <v-navigation-drawer width="100%" floating permanent>
                            <v-col cols="12">
                                <v-text-field class="mb-n5" color="secondary" outlined dense label="Search Here" v-model="search.search" :clearable="true" @click:clear="search.search=null">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-magnify</v-icon></template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="mb-4">
                                <v-select :items="skinTypes" item-text="name" item-value="id"  class="mb-n5" color="secondary" outlined dense label="Skin Type" v-model="search.skinType" :clearable="true" @click:clear="search.skinType=null">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-face-woman</v-icon></template>
                                </v-select>
                            </v-col>
                            <!-- <v-col cols="12">
                                <v-select color="secondary" outlined dense label="Urutkan Berdasarkan">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-sort</v-icon></template>
                                </v-select>
                            </v-col> -->
                        </v-navigation-drawer>
                    </v-card>
                </v-col>
                <v-btn v-else fab dark large color="secondary" fixed right bottom class="mr-3 mb-2" @click="filterMenu = !filterMenu">
                    <v-icon>mdi-filter-menu</v-icon>
                </v-btn>
                <v-dialog max-width="1000px" v-model="filterMenu">
                    <v-card>
                        <v-toolbar dense flat dark color="secondary">
                            <span class="title font-weight-light">Cari Sesuai Kebutuhanmu</span>
                            <v-btn absolute right icon @click="filterMenu = !filterMenu"><v-icon>mdi-close</v-icon></v-btn>
                        </v-toolbar>
                        <v-card-text class="my-7">
                            <v-col cols="12">
                                <v-text-field class="" color="secondary" outlined dense label="Search Here" v-model="search.search" :clearable="true" @click:clear="search.search=null">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-magnify</v-icon></template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="">
                                <v-select :items="skinTypes" item-text="name" item-value="id"  class="mb-n5" color="secondary" outlined dense label="Skin Type" v-model="search.skinType" :clearable="true" @click:clear="search.skinType=null">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-face-woman</v-icon></template>
                                </v-select>
                            </v-col>
                        </v-card-text>
                        <v-card-actions>
                            <v-container>
                                <v-row justify="center">
                                    <v-btn large class="mt-n8" color="secondary darken-1 white--text" @click="filterMenu = !filterMenu">
                                        <span>Close</span>
                                    </v-btn>
                                </v-row>
                            </v-container>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-col cols="12" sm="12" md="9" class="pl-md-16">
                    <v-data-table
                        :headers="header"
                        :items="products"
                        :disable-sort="true"
                        :hide-default-header="true"
                        :hide-default-footer="true"
                        :page.sync="pagination.page"
                        :loading="loadingState"
                        items-per-page="12"
                        @page-count="pagination.pageCount = $event"
                    >
                        <template v-slot:body="{items}">
                            <v-container fluid class="pt-0">
                                <v-row>
                                    <v-col class="text-left" cols="12" sm="12" md="4" xl="3" v-for="(product,idx) in items" :key="idx">
                                        <v-card :disabled="product.stock<=0"  :class="[$vuetify.breakpoint.smAndDown? 'mx-auto': '']" :width="[$vuetify.breakpoint.smAndDown? '80vw' : '600px']" @click.stop="goTo(product.id)" style="cursor:pointer">
                                            <v-img v-if="product.imageLead.length > 0" aspect-ratio="1"  height="300px" :lazy-src="product.imageLead[0].image" :src="product.imageLead[0].image">
                                                <v-col cols="12" class="text-right" v-if="product.discountStatus == '1'">
                                                    <v-chip
                                                        text-color="white"
                                                        color="red"
                                                    >
                                                        {{product.discount}}% OFF
                                                    </v-chip>
                                                </v-col>
                                                <template v-slot:placeholder>
                                                    <v-row
                                                        class="fill-height ma-0"
                                                        align="center"
                                                        justify="center"
                                                    >
                                                        <v-progress-circular indeterminate color="black"></v-progress-circular>
                                                    </v-row>
                                                </template>
                                            </v-img>
                                            <v-img v-else aspect-ratio="1"  height="300px" src="https://firebasestorage.googleapis.com/v0/b/carirumah-45009.appspot.com/o/no-image.jpg?alt=media&token=447162cf-bd0c-48e3-b57d-a68d24bcd2b7">
                                                <v-col cols="12" class="text-right" v-if="product.discountStatus == 1">
                                                    <v-chip
                                                        text-color="white"
                                                        color="red"
                                                    >
                                                        {{product.discount}}% OFF
                                                    </v-chip>
                                                </v-col>
                                            </v-img>
                                            <v-card-title>
                                                <div class="headerClass">{{product.name}}</div>
                                            </v-card-title>
                                            <v-card-subtitle>{{skinTypes[product.skinType-1].name}}</v-card-subtitle>
                                            <v-card-text>
                                                <div v-if="!$vuetify.breakpoint.smAndDown" class="d-flex flex-no-wrap justify-space-between align-center">
                                                    <div>
                                                        <v-card-text :class="[product.discountStatus=='1'?'text-decoration-line-through':null]"  class="pl-0 py-0">Rp{{changeCurr(product.price)}}</v-card-text>
                                                        <v-card-text v-if="product.discountStatus=='1'" class="pl-0 py-0 mb-n2 font-weight-bold red--text">Rp{{changeCurr(discountCurr(product.price,product.discount))}}</v-card-text>
                                                    </div>
                                                    <div>
                                                        <v-btn elevation="1" class="secondary" v-if="product.stock>0" @click.stop="addToCart(product)"><v-icon>mdi-cart</v-icon></v-btn>
                                                        <v-btn class="black--text pr-0" text v-else>Stock Habis</v-btn>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <div>
                                                        <v-card-text :class="[product.discountStatus=='1'?'text-decoration-line-through':null]"  class="pl-0 py-0">Rp{{changeCurr(product.price)}}</v-card-text>
                                                        <v-card-text v-if="product.discountStatus=='1'" class="pl-0 py-0 mb-md-n2 font-weight-bold red--text">Rp{{changeCurr(discountCurr(product.price,product.discount))}}</v-card-text>
                                                    </div>
                                                    <div class="mt-4">
                                                        <v-btn width="100%" elevation="1" class="secondary" v-if="product.stock>0" @click.stop="addToCart(product)"><v-icon>mdi-cart</v-icon></v-btn>
                                                        <v-btn width="100%" class="black--text px-0" text v-else>Stock Habis</v-btn>
                                                    </div>
                                                </div>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </template>
                    </v-data-table>
                    <div class="text-center mt-10">
                        <v-pagination v-model="pagination.page" :length="pagination.pageCount" :total-visible="7" color="secondary"></v-pagination>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</template>

<script>
// importing stuff
import productService from '@/services/Product'
import _ from "lodash"

export default {
    data() {
        return {
            skinTypes: [
                {id:"1", name:"Have my own glam! - Normal skin"},
                {id:"2", name:"Bye bye shiny! - Oily skin"},
                {id:"3", name:"Make me dewy! - Dry skin"},
                {id:"4", name:"Acne go away! - Acne prone"}
            ],
            filterMenu: false,
            products:[],
            pagination: {
                page:1,
                pageCount:0,
                itemsPerPage:12
            },
            search: {
                search:null,
                skinType:null
            },
            selectedProducts:this.$store.state.product.products

        }
    },

    created() {
        this.get()
        if(this.$store.state.search.searchBarang.length>0 || this.$store.state.search.searchBarang.length != null || this.$store.state.search.searchBarang.length != undefined) {
            this.search.search = this.$store.state.search.searchBarang
        }
    },
    
    methods: {
        advanceSearchSearch(val) {
            if(!this.search.search) {
                return true
            } return val.toLowerCase().includes(this.search.search.toLowerCase())
        },
        advanceSearchSkin(val) {
            if(!this.search.skinType) {
                return true
            } return val == this.search.skinType
        },
        get() {
            this.getAllProduct()
        },
        getAllProduct() {
            this.products = []
            productService.getAllProducts()
                .then((data) => {
                    data.forEach(e => {
                        this.products.push(e)
                    });
                })
        },
        changeCurr(val) {
            let temp = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'IDR' }).format(val)
            return temp.slice(0, -4)
        },
        discountCurr(val,disc) {
            return val-(val*disc/100)
        },
        addToCart(product) {
            let item = {
                id: product.id,
            }
            if(_.find(this.selectedProducts, ['id',item.id]) != undefined) {
                if(_.find(this.selectedProducts, ['id',item.id]).quantity<product.stock) {
                    this.$store.dispatch("addToCart",item)
                } else {
                    alert("Your Cart Item Exceed the Stock")
                }
            } else {
                this.$store.dispatch("addToCart",item)
            }
        },
        goTo(path) {
            this.$router.push("checkmeout/"+path)
            window.scroll(0,0)
        }
    },

    computed: {
        header() {
            return [
                {value:"name", filter:this.advanceSearchSearch},
                {value:"skinType", filter:this.advanceSearchSkin}
            ]
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
.toolbar {
  position: -webkit-sticky!important;
  position: sticky!important;
  top: 15%!important;
  z-index: 1!important;
  overflow-y:auto!important;
}
.toolbar::-webkit-scrollbar{
  display: none!important;
}
.v-dialog > .v-card > .v-toolbar {
  position: sticky;
  top: 0;
  z-index: 999;
}
.headerClass{
    white-space: nowrap ;
    word-break: normal;
    overflow: hidden ;
    text-overflow: ellipsis;
}
</style>