<template>
    <div>
        <v-app-bar dark app class="absolute px-5" flat color="footer" v-if="!this.$route.meta.auth && this.$route.meta.navbar">
            <v-btn x-large icon  @click="goTo('/')"><v-img src="@/assets/wag_logo.png" contain width="90"></v-img></v-btn>
            <v-btn disabled></v-btn>
            <v-btn disabled></v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title @click="goTo('/')" :class="[this.$route.name=='home'?'selectedMenu':'hoverMenu']" class="mr-10"><span>Home</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('/about')" :class="[this.$route.name=='about'?'selectedMenu':'hoverMenu']" class="mr-10"><span>About Us</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('/checkmeout')" :class="[this.$route.name=='checkmeout'?'selectedMenu':'hoverMenu']" class="mr-10"><span>Check Me Out</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('/selfdiscovery')" :class="[this.$route.name=='selfdiscovery'?'selectedMenu':'hoverMenu']" class="mr-10"><span>Self-Discovery</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('/glammersay')" :class="[this.$route.name=='glammersay'?'selectedMenu':'hoverMenu']" class="mr-10"><span>Glammers Say</span></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon><v-icon @click="searchOverlay=!searchOverlay">mdi-magnify</v-icon></v-btn>
            <div class="searchBox"><input v-model="searchBarangComputed" v-on:keyup.enter="goTo('/checkmeout')" type="text" class="searchBoxInput" :class="[searchOverlay?'active':null]" placeholder="Search here..."></div>
            <v-badge :color="products.length>0?'red':'transparent'" :content="products.length" offset-x="20" offset-y="20">
                <v-btn icon @click="cart = !cart"><v-icon>mdi-cart</v-icon></v-btn>
            </v-badge>
            <v-menu offset-y>
                <template v-slot:activator="{ on, attrs  }">
                    <v-btn icon v-bind="attrs" v-on="on"><v-icon>mdi-account</v-icon></v-btn>
                </template>
                <v-list width="200px" v-if="id==null">
                    <v-list-item @click="loginDialog = !loginDialog">
                        <v-list-item-title>Login</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="registerDialogFunc">
                        <v-list-item-title>Register</v-list-item-title>
                    </v-list-item>
                </v-list>
                <v-list v-else width="200px">
                    <v-list-item @click="logout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <!-- Cart -->
        <v-navigation-drawer fixed temporary right v-model="cart" v-if="!this.$route.meta.auth" :width="$vuetify.breakpoint.smAndDown?'200px':'400px'" >
            <v-layout justify-space-between column fill-height class="pa-2 pt-6">
                <div>
                    <template>
                        <v-list-item><h1>Your Cart</h1></v-list-item>
                        <v-list-item class="my-n4"><v-divider></v-divider></v-list-item>
                    </template>
                    <v-list dense>
                        <v-list-item v-for="(item, idx) in products" :key="idx" class="mb-3">
                            <v-list-item-avatar size="80"><img :src="searchItem(item).imageLead[0].image"></v-list-item-avatar>
                            <v-list-item-content>
                                <div class="d-flex flex-no-wrap justify-space-between align-center">
                                    <div>
                                        <v-list-item-title>{{cut(searchItem(item).name)}}</v-list-item-title>
                                        <v-list-item-subtitle>Rp{{changeCurr(discountCurr(searchItem(item).price, searchItem(item).discount, searchItem(item).discountStatus))}} x <span class="red--text">{{item.quantity}}</span></v-list-item-subtitle>
                                    </div>
                                    <div>
                                        <v-list-item-content><v-btn x-small plain color="green" @click="addToCart(searchItem(item))"><v-icon>mdi-plus</v-icon></v-btn></v-list-item-content>
                                        <v-list-item-content><v-btn x-small plain color="red" @click="removeFromCart(searchItem(item))"><v-icon>mdi-minus</v-icon></v-btn></v-list-item-content>
                                    </div>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </div>
                <div class="mb-2 px-4">
                    <v-list-item class="px-0 d-flex flex-no-wrap justify-space-between font-weight-bold">
                        <v-list-item-title>Total</v-list-item-title>
                        <v-list-item-title class="text-right">Rp{{changeCurr(totalPrice)}}</v-list-item-title>
                    </v-list-item>
                    <v-list-item :disabled="products<=0" dense link class="pa-0 text-center">
                        <v-list-item-title><v-btn width="100%" class="secondary" dark :disabled="products<=0" @click="goTo('/checkout')">Checkout</v-btn></v-list-item-title>
                    </v-list-item>
                </div>
            </v-layout>
        </v-navigation-drawer>
        <!-- Admin -->
        <v-app-bar app class="absolute px-5" flat color="secondary" v-if="this.$route.meta.auth && this.$route.meta.navbar && $vuetify.breakpoint.smAndDown"></v-app-bar>
        <v-navigation-drawer app dark v-model="drawer" v-if="this.$route.meta.auth && this.$route.meta.navbar">
            <v-layout justify-space-between column fill-height>
                <div>
                    <template>
                        <v-list-item class="my-2">
                            <v-list-item-avatar size="60"><img src="https://image.flaticon.com/icons/svg/194/194938.svg" alt=""></v-list-item-avatar>
                            <v-list-item-content><v-list-item-title>Admin</v-list-item-title></v-list-item-content>
                        </v-list-item>
                    </template>
                    <v-divider></v-divider>
                    <v-list dense>
                        <v-list-item link @click="goTo('/admproduct')">
                            <v-list-item-icon><v-icon :class="[this.$route.name=='admProduct' ? 'secondary--text' : '']">mdi-cube-outline</v-icon></v-list-item-icon>
                            <v-list-item-title :class="[this.$route.name=='admProduct' ? 'secondary--text' : '']">Produk</v-list-item-title>
                        </v-list-item>
                        <v-list-item link @click="goTo('/admorder')">
                            <v-list-item-icon><v-icon :class="[this.$route.name=='admOrder' ? 'secondary--text' : '']">mdi-cart-arrow-down</v-icon></v-list-item-icon>
                            <v-list-item-title :class="[this.$route.name=='admOrder' ? 'secondary--text' : '']">Order</v-list-item-title>
                        </v-list-item>
                        <v-list-item link @click="goTo('/admselfdiscovery')">
                            <v-list-item-icon><v-icon :class="[this.$route.name=='admSelfDiscovery' ? 'secondary--text' : '']">mdi-newspaper-variant-outline</v-icon></v-list-item-icon>
                            <v-list-item-title :class="[this.$route.name=='admSelfDiscovery' ? 'secondary--text' : '']">Self Discovery</v-list-item-title>
                        </v-list-item>
                        <v-list-item link @click="goTo('/admpromo')">
                            <v-list-item-icon><v-icon :class="[this.$route.name=='admPromo' ? 'secondary--text' : '']">mdi-ticket-percent</v-icon></v-list-item-icon>
                            <v-list-item-title :class="[this.$route.name=='admPromo' ? 'secondary--text' : '']">Promo</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </div>
                <div class="mb-2">
                    <v-list-item dense link @click="logout">
                        <v-list-item-icon class="mr-5"><v-icon class="red--text">mdi-logout</v-icon></v-list-item-icon>
                        <v-list-item-title class="red--text">Log Out</v-list-item-title>
                    </v-list-item>
                </div>
            </v-layout>
        </v-navigation-drawer>

        <!-- Popup Login / Register -->
        <v-dialog v-model="registerDialog" max-width="1000px">
            <v-card>
                <v-card-title class="justify-center font-weight-regular text-h3 pt-8">Register</v-card-title>
                <div>
                    <v-card-text class="mt-4 px-8">
                        <v-form ref="registerForm">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        outlined
                                        v-model="user.name"
                                        label="Full Name"
                                        color="secondary"
                                        :rules="rules.name"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4 mt-md-0">
                                    <v-text-field
                                        outlined
                                        v-model="user.phone"
                                        label="Phone Number"
                                        placeholder="83151253252"
                                        color="secondary"
                                        :rules="[ phoneRule ]"
                                    ><template v-slot:prepend-inner><span class="mr-2 mt-1 secondary--text font-weight-bold">+62</span></template></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4">
                                    <v-text-field
                                        outlined
                                        v-model="user.email"
                                        label="E-mail"
                                        color="secondary"
                                        :rules="rules.email"
                                    ><template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-at</v-icon></template></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4">
                                    <v-text-field
                                        outlined
                                        v-model="user.password"
                                        label="Password"
                                        color="secondary"
                                        :type="showPasswordRegister ? 'text' : 'password'"
                                        :rules="rules.password"
                                    >
                                        <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-key</v-icon></template>
                                        <template v-slot:append>
                                            <v-icon @click="showPasswordRegister = !showPasswordRegister" v-if="showPasswordRegister">mdi-eye</v-icon>
                                            <v-icon @click="showPasswordRegister = !showPasswordRegister" v-else>mdi-eye-off</v-icon>
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4">
                                    <v-select
                                        :items="province"
                                        outlined
                                        v-model="user.provinsi"
                                        label="Provinsi"
                                        color="secondary"
                                        item-text="province"
                                        item-value="province_id"
                                        @change="getCity"
                                        :rules="rules.provinsi"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4">
                                    <v-select
                                        :items="cityComputed"
                                        outlined
                                        v-model="user.kota"
                                        label="City"
                                        color="secondary"
                                        item-text="city_name"
                                        item-value="city_id"
                                        @change="getSubDistrict"
                                        :rules="rules.kota"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4">
                                    <v-select
                                        :items="subdistrict"
                                        outlined
                                        v-model="user.kecamatan"
                                        label="SubDistrict"
                                        color="secondary"
                                        item-text="subdistrict_name"
                                        item-value="subdistrict_id"
                                        :rules="rules.kecamatan"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="6" class="mt-n4">
                                    <v-text-field
                                        outlined
                                        v-model="user.kodePos"
                                        label="Postal Code"
                                        color="secondary"
                                        :rules="rules.post"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" class="mt-n4">
                                    <v-text-field
                                        outlined
                                        v-model="user.alamat"
                                        label="Alamat Lengkap"
                                        color="secondary"
                                        :rules="rules.alamat"
                                    ><template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-home</v-icon></template></v-text-field>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                    <v-card-actions class="mt-n5 pb-8">
                        <v-container>
                            <v-row justify="center">
                                <v-btn large width="50%" color="secondary white--text" @click="createNewCustomer">Register</v-btn>
                            </v-row>
                        </v-container>
                    </v-card-actions>
                    <v-progress-linear
                        v-if="loadingState"
                        :indeterminate="loadingState"
                        color="secondary"
                        height="7"
                    ></v-progress-linear>
                </div>
            </v-card>
        </v-dialog>
        <v-dialog v-model="loginDialog" max-width="600px">
            <v-card>
                <v-card-title class="justify-center font-weight-regular text-h3 pt-8">Login</v-card-title>
                <div>
                    <v-card-text class="mt-4 px-8">
                        <v-form ref="loginForm">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        outlined
                                        dense
                                        label="E-mail"
                                        color="secondary"
                                        :rules="rules.email"
                                        v-model="userLogin.email"
                                        :disabled="loadingState"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" class="my-n6">
                                    <v-text-field
                                        outlined
                                        dense
                                        type="password"
                                        label="password"
                                        color="secondary"
                                        :rules="rules.passwordLogin"
                                        v-model="userLogin.password"
                                        :disabled="loadingState"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-btn width="100%" color="secondary" :disabled="loadingState" large @click="loginCustomer">Log in</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                </div>
                <v-progress-linear
                    v-if="loadingState"
                    :indeterminate="loadingState"
                    color="secondary"
                    height="7"
                ></v-progress-linear>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" multi-line v-bind:color="snackbarColor">
            {{ snackbarMessage }}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    <v-icon>
                        mdi-close
                    </v-icon>
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
// importing stuff
import productService from '@/services/Product'
import Rajaongkir from "@/services/Rajaongkir"
import _ from "lodash"
import Customer from "@/services/Customer"

export default {
    data() {
        return {
            searchOverlay: false,
            loginDialog: false,
            registerDialog: false,
            drawer: null,
            cart: false,
            products: this.$store.state.product.products,
            id:this.$store.state.user.id,
            productList:[],
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState: false,
            user: {
                name:"",
                email:"",
                password:"",
                alamat:"",
                kecamatan:null,
                kota:null,
                provinsi:null,
                phone:"",
                kodePos:"",
                isMember: 1
            },
            userLogin: {
                email:"",
                password:""
            },
            province: [],
            city: [],
            subdistrict: [],
            rules: {
                name : [
                    v => !!v || "Name is Required"
                ],
                email : [
                    v => !!v || "E-mail is Required",
                    v => /.+@.+\..+/.test(v) || 'E-mail is not Valid',
                ],
                alamat : [
                    v => !!v || "Address is Required"
                ],
                kecamatan : [
                    v => !!v || "SubDistrict is Required"
                ],
                kota : [
                    v => !!v || "City is Required"
                ],
                provinsi : [
                    v => !!v || "Province is Required"
                ],
                password : [
                    v => !!v || "Password is Required",
                    v => v.length>=8 || "Min. 8 Character"
                ],
                passwordLogin : [
                    v => !!v || "Password is Required",
                ],
                post : [
                    v => !!v || "Postal Code is Required",
                    v => v>0 || "Not Valid",
                    v => v.length<=5 || "Not Valid"
                ],
            }
        }
    },

    created() {
        this.get()
        // this.$store.dispatch("clear")
    },
    
    methods: {
        get() {
            this.getAllProduct()
        },
        getAllProduct() {
            this.productList = []
            productService.getAllProducts()
                .then((data) => {
                    data.forEach(e => {
                        this.productList.push(e)
                    });
                })
        },
        async registerDialogFunc() {
            this.registerDialog = true
            await Rajaongkir.getAllProvince()
                .then((response) => {
                    this.province = response.data.message.rajaongkir.results
                })
        },
        async getCity(item) {
            this.city = []
            this.subdistrict = []
            this.user.kota = null
            this.user.kecamatan = null
            await Rajaongkir.getAllCity(+this.province[item].province_id-1)
                .then((response) => {
                    this.city = response.data.message.rajaongkir.results
                })
        },
        async getSubDistrict(item) {
            this.subdistrict = []
            this.user.kecamatan = null
            // console.log(_.find(this.city, ['city_id', item]))
            await Rajaongkir.getAllSubdistrict(+_.find(this.city, ['city_id', item]).city_id)
                .then((response) => {
                    this.subdistrict = response.data.message.rajaongkir.results
                })
        },
        createNewCustomer() {
            if(this.$refs.registerForm.validate()) {
                this.loadingState = true
                Customer.registerCustomer(this.user)
                    .then(() => {
                        this.snackbarColor = "success"
                        this.snackbarMessage = "Registration Success"
                    }) .catch(err => {
                        this.snackbarColor = "error"
                        this.snackbarMessage = err.message
                    }) .finally(() => {
                        this.snackbar = true
                        this.loadingState = false
                        this.registerDialog = false
                    })
            }
        },
        loginCustomer() {
            if(this.$refs.loginForm.validate()) {
                this.loadingState = true
                Customer.loginCustomer(this.userLogin)
                    .then((response) => {
                        this.snackbarColor = "success"
                        this.snackbarMessage = "Login Success"
                        let user = {
                            id: response.data.message[0].id,
                            username: response.data.message[0].email,
                        }
                        this.$store.dispatch("login",user)
                        this.snackbar = true
                        this.loadingState = false
                        this.loginDialog = false
                    }) .catch(err => {
                        this.snackbarColor = "error"
                        this.snackbarMessage = err.message
                        this.snackbar = true
                        this.loadingState = false
                        this.loginDialog = false
                    })
            }
        },
        goTo(path) {
            this.$router.push(path)
            window.scroll(0,0)
        },
        changeCurr(val) {
            let temp = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'IDR' }).format(val)
            return temp.slice(0, -4)
        },
        discountCurr(val, disc, status) {
            if(status == "1") {
                return val-(val*disc/100)
            } return val
        },
        addToCart(product) {
            let item = {
                id: product.id,
            }
            this.$store.dispatch("addToCart",item)
        },
        removeFromCart(product) {
            let item = {
                id: product.id,
            }
            this.$store.dispatch("removeFromCart",item)
        },
        searchItem(product) {
            return this.productList.find(item => item.id == product.id)
        },
        cut(string) {
            if(string.length>20) {
                return string.substring(0,20)+"..."
            } else {
                return string
            }
        },
        logout() {
            this.id=this.$store.state.user.id,
            this.$store.dispatch("logout")
            this.$router.replace("/")
        },
    },

    computed: {
        totalPrice() {
            let total=0
            this.products.forEach(e => {
                total+=(+this.discountCurr(+this.searchItem(e).price,+this.searchItem(e).discount,+this.searchItem(e).discountStatus)*e.quantity)
            }); return total
        },
        cityComputed() {
            if(this.city.length>0) {
                let cities = this.city
                cities.forEach(e => {
                    e.city_name = e.type+" "+e.city_name
                });
                return cities
            } return []
        },
        searchBarangComputed: {
            get () {
                return this.searchBarang
            },
            set (value) {
                this.$store.commit('updateSearch', value)
            }
        }
    },

    watch: {
        'this.$store.state.product.products': function() {
            this.products = this.$store.state.product.products
        },
        'this.$store.state.user.id': function() {
            this.id = this.$store.state.user.id
        }
    }
}
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap');
    .changeFont {
        font-family: 'Open Sans', sans-serif;
    }
    .selectedMenu {
        cursor: pointer
    }
    .selectedMenu:after {
        content: '';
        display: block;
        margin: auto;
        height: 3px;
        width: 100%;
        background: #FFF;
        transition: width .5s ease, background-color .5s ease;
    }
    .hoverMenu {
        cursor: pointer
    }
    .hoverMenu:after {
        content: '';
        display: block;
        margin: auto;
        height: 3px;
        width: 0px;
        background: transparent;
        transition: width .5s ease, background-color .5s ease;
    }
    .hoverMenu:hover:after {
        width: 100%;
        background: #FFF;
    }
    .hoverMenuPromo {
        cursor: pointer
    }
    .searchBox .searchBoxInput{
        background: #FFF;
        outline: none;
        width: 0px;
        transition: all 0.5s ease;
    }
    .searchBox .searchBoxInput.active{
        background: #FFF;
        padding: 6px 16px;
        outline: none;
        width: 400px;
        border-radius: 4px;
        margin-right: 6px;
    }
</style>