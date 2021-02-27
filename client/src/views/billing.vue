<template>
    <v-main>
        <v-container fluid class="py-10 pb-16 mb-16 px-md-16 px-5" v-if="this.products.length>0">
            <div class="display-1 mb-5 "><span v-if="id==null">Guest</span> Checkout</div>
            <v-row no-gutters>
                <v-col cols="12" md="8">
                    <v-card shaped>
                        <v-toolbar color="blue-grey lighten-5" flat>
                            <v-card-title>Billing Detail</v-card-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-form ref="billingForm">
                                <v-row class="mt-2 px-4">
                                    <v-col cols="12">
                                        <v-text-field
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.name"
                                            label="Full Name"
                                            color="secondary"
                                            :rules="rules.name"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" class="mt-n4">
                                        <v-text-field
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.email"
                                            label="E-mail"
                                            color="secondary"
                                            :rules="rules.email"
                                        ><template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-at</v-icon></template></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" class="mt-n4">
                                        <v-text-field
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.phone"
                                            label="Phone Number"
                                            placeholder="83151253252"
                                            color="secondary"
                                            :rules="[ phoneRule ]"
                                        ><template v-slot:prepend-inner><span class="mr-2 mt-1 secondary--text font-weight-bold">+62</span></template></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" class="mt-n4">
                                        <v-select
                                            v-if="id==null"
                                            :items="province"
                                            outlined
                                            v-model="billing.provinsi"
                                            label="Province"
                                            color="secondary"
                                            item-text="province"
                                            item-value="province_id"
                                            @change="getCity"
                                            :rules="rules.provinsi"
                                        ></v-select>
                                        <v-text-field
                                            v-else
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.provinsi"
                                            label="Province"
                                            color="secondary"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" class="mt-n4">
                                        <v-select
                                            v-if="id==null"
                                            :items="cityComputed"
                                            outlined
                                            v-model="billing.kota"
                                            label="City"
                                            color="secondary"
                                            item-text="city_name"
                                            item-value="city_id"
                                            @change="getSubDistrict"
                                            :rules="rules.kota"
                                        ></v-select>
                                        <v-text-field
                                            v-else
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.kota"
                                            label="City"
                                            color="secondary"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" class="mt-n4">
                                        <v-select
                                            v-if="id==null"
                                            :items="subdistrict"
                                            outlined
                                            label="SubDistrict"
                                            color="secondary"
                                            item-text="subdistrict_name"
                                            item-value="subdistrict_id"
                                            :rules="rules.kecamatan"
                                            @change="setKecamatan"
                                        ></v-select>
                                        <v-text-field
                                            v-else
                                            :disabled="id!=null"
                                            outlined
                                            v-model="kecamatan"
                                            label="SubDistrict"
                                            color="secondary"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6" class="mt-n4">
                                        <v-text-field
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.kodePos"
                                            label="Postal Code"
                                            color="secondary"
                                            :rules="rules.post"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" class="mt-n4">
                                        <v-text-field
                                            :disabled="id!=null"
                                            outlined
                                            v-model="billing.alamat"
                                            label="Address"
                                            color="secondary"
                                            :rules="rules.alamat"
                                        ><template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-home</v-icon></template></v-text-field>
                                    </v-col>
                                    <v-col cols="12" class="mt-n4">
                                        <v-text-field
                                            outlined
                                            v-model="billing.note"
                                            label="Note (Optional)"
                                            color="secondary"
                                        ><template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-note-outline</v-icon></template></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="4" class="pl-md-16 mt-10 mt-md-0">
                    <div class="display-1 mb-5 secondary--text">Your Order</div>
                    <div v-if="products.length>0">
                        <div v-for="(item,idx) in products" :key="idx" class="body-1">
                            <v-card class="mb-4 orderList" outlined tile>
                                <v-card-title class="body-2"><div class="headerClass">{{searchItem(item).name}}</div></v-card-title>
                                <v-card-text class="mt-n2">
                                    <v-list-item-subtitle class="caption">Rp{{changeCurr(discountCurr(searchItem(item).price, searchItem(item).discount, searchItem(item).discountStatus))}} x <span class="red--text">{{item.quantity}}</span></v-list-item-subtitle>
                                </v-card-text>
                            </v-card>
                        </div>
                    </div>
                    <v-divider class="my-6"></v-divider>
                    <div class="headline mb-5">Shipping</div>
                    <v-form>
                        <v-select
                            :items="courierList"
                            outlined
                            v-model="courier.name"
                            label="Courier"
                            color="secondary"
                            item-text="val"
                            item-value="id"
                            @change="getOngkir"
                            :disabled="disableCour"
                        ></v-select>
                        <v-select
                            :items="courierServiceList"
                            outlined
                            label="Courier Service"
                            color="secondary"
                            item-text="name"
                            :disabled="disableCour"
                            @change="ongkirDetail"
                            return-object
                        ></v-select>
                    </v-form>
                    <v-divider class="mb-6"></v-divider>
                    <div class="headline mb-5">Have a coupon?</div>
                    <v-form ref="couponForm">
                        <v-text-field
                            outlined
                            placeholder="Enter Your Coupon Code"
                            color="secondary"
                            :rules="[ couponRule ]"
                            v-model="coupon"
                        >
                            <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-ticket-percent</v-icon></template>
                            <template v-slot:append><v-btn dark color="secondary mt-n2" @click="applyCoupon">apply</v-btn></template>
                        </v-text-field>
                        <div class="d-flex flex-no-wrap justify-space-between my-n2" v-if="couponText.length>0">
                            <div class="caption">{{couponText}} Discount</div>
                            <div><a class="secondary--text" @click="clearCoupon">Clear Coupon</a></div>
                        </div>
                    </v-form>
                    <v-divider class="my-6"></v-divider>
                    <div class="d-flex flex-no-wrap justify-space-between">
                        <div>Subtotal</div>
                        <div class="font-weight-bold">Rp{{changeCurr(calculateSubTotal)}}</div>
                    </div>
                    <v-divider class="my-6"></v-divider>
                    <div class="d-flex flex-no-wrap justify-space-between">
                        <div>Shipping</div>
                        <div class="font-weight-bold">Rp{{changeCurr(courier.price)}}</div>
                    </div>
                    <v-divider class="my-6"></v-divider>
                    <div class="d-flex flex-no-wrap justify-space-between">
                        <div>Discount</div>
                        <div class="font-weight-bold">Rp{{changeCurr(calculateDiscount)}}</div>
                    </div>
                    <v-divider class="my-6"></v-divider>
                    <div class="d-flex flex-no-wrap justify-space-between">
                        <div class="headline font-weight-bold secondary--text">TOTAL</div>
                        <div class="font-weight-bold secondary--text">Rp{{changeCurr(courier.price+calculateSubTotal-calculateDiscount)}}</div>
                    </div>
                    <v-divider class="my-6"></v-divider>
                    <v-checkbox v-model="checkboxTerm" color="secondary" label="I have read and accepted Terms and Conditions"></v-checkbox>
                    <v-btn x-large width="100%" class="mt-4 secondary" dark :disabled="checkoutDisable" @click="checkout">CHECKOUT</v-btn>
                </v-col>
            </v-row>
        </v-container>
        <v-container fluid fill-height v-else>
            <v-row no-gutters align="center" justify="center">
                <v-col cols="12" md="4" class="text-center secondary--text">
                    <v-icon class="display-4" color="secondary">mdi-cart-off</v-icon>
                    <div class="display-1 mt-2">Your Cart is Empty</div>
                </v-col>
            </v-row>
        </v-container>
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
        <v-overlay :value="loadingState">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </v-main>
</template>

<script>
// importing stuff
import Rajaongkir from "@/services/Rajaongkir"
import Promo from "@/services/Promo"
import Orderhistory from "@/services/Orderhistory"
import Customer from "@/services/Customer"
import productService from '@/services/Product'
import _ from "lodash"


export default {
    data() {
        return {
            province: [],
            city: [],
            subdistrict: [],
            courierList: [
                {id:'jne', val:"JNE"},
                {id:'pos', val:"POS Indonesia"},
                {id:'tiki', val:"TIKI"},
                {id:'wahana', val:"WAHANA"},
                {id:'sicepat', val:"SICEPAT"},
                {id:'jnt', val:"J&T"},
                {id:'pcp', val:"Priority Cargo and Package (PCP)"},
                {id:'rpx', val:"RPX Holding (RPX)"},
                {id:'sap', val:"SAP Express (SAP)"},
                {id:'jet', val:"JET"},
                {id:'dse', val:"21 Express (DSE)"},
                {id:'first', val:"First Logistics (FIRST)"},
            ],
            billing: {
                name:"",
                email:"",
                alamat:"",
                kecamatan:null,
                kota:null,
                provinsi:null,
                phone:"",
                kodePos:"",
                isMember: 0,
                note:""
            },
            kecamatan:null,
            courier:{
                name:null,
                type:null,
                price:null
            },
            courierServiceList:[],
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
                post : [
                    v => !!v || "Postal Code is Required",
                    v => v>0 || "Not Valid",
                    v => v.length<=5 || "Not Valid"
                ],
            },
            products: this.$store.state.product.products,
            id:this.$store.state.user.id,
            productList:[],
            couponList: [],
            shipping:null,
            coupon:"",
            discount:{
                id:null,
                name:null,
                discount:null,
                code:null,
                type:null
            },
            discountDefault:{
                id:null,
                name:null,
                discount:null,
                code:null,
                type:null
            },
            couponText:"",
            checkboxTerm:false,
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState: false,
        }
    },

    created() {
        this.get()
    },
    
    methods: {
        goTo(path) {
            this.$router.push(path)
            window.scroll(0,0)
        },
        async checkout() {
            if(this.$refs.billingForm.validate()) {
                this.loadingState = true
                // guest checkout
                if(this.id == null) {
                    // Create customer with is_member = 0
                    // return id
                    // create order_history & item_order
                    // If success, clear the vuex
                    // send email
                    // to another page 
                    let guest = {
                        name: this.billing.name,
                        email: this.billing.email,
                        password: null,
                        alamat: this.billing.alamat,
                        kecamatan: this.billing.kecamatan,
                        kota: this.billing.kota,
                        provinsi: this.billing.provinsi,
                        kodePos: this.billing.kodePos,
                        phone: this.billing.phone,
                        isMember: 0,
                    }
                    await Customer.registerCustomer(guest)
                        .then((response) => {
                            let guestId = response.data.message
                            let itemOrders = []
                            this.products.forEach(e => {
                                itemOrders.push({
                                    price:+this.discountCurr(+this.searchItem(e).price,+this.searchItem(e).discount,+this.searchItem(e).discountStatus),
                                    amount:e.quantity,
                                    itemId:this.searchItem(e).id
                                })
                            });
                            let email = this.billing.email
                            Orderhistory.createOrderHistory(this.discount.id, itemOrders, this.courier, guestId, this.billing.note, email)
                                .then((response) => {
                                    this.$store.dispatch("clearItems")
                                    this.snackbarColor = "success"
                                    this.snackbarMessage = "Checkout Success"
                                    this.snackbar = true
                                    this.loadingState = false
                                    console.log(response.data.message)
                                    this.goTo("/payment/"+response.data.message)
                                }) .catch((err) => {
                                    this.snackbarColor = "error"
                                    this.snackbarMessage = err.message
                                    this.snackbar = true
                                    this.loadingState = false
                                })
                        }) .catch(err => {
                            this.snackbarColor = "error"
                            this.snackbarMessage = err.message
                            this.snackbar = true
                            this.loadingState = false
                        })
                } else { // Customer Checkout
                    // create order_history & item_order
                    // If success, clear the vuex
                    // send email
                    // to another page
                    let itemOrders = []
                    this.products.forEach(e => {
                        itemOrders.push({
                            price:+this.discountCurr(+this.searchItem(e).price,+this.searchItem(e).discount,+this.searchItem(e).discountStatus),
                            amount:e.quantity,
                            itemId:this.searchItem(e).id
                        })
                    });
                    let email = this.billing.email
                    Orderhistory.createOrderHistory(this.discount.id, itemOrders, this.courier, this.id, this.billing.note, email)
                        .then((response) => {
                            this.$store.dispatch("clearItems")
                            this.snackbarColor = "success"
                            this.snackbarMessage = "Checkout Success"
                            this.snackbar = true
                            this.loadingState = false
                            console.log(response.data.message)
                            this.goTo("/payment/"+response.data.message)
                        }) .catch((err) => {
                            this.snackbarColor = "error"
                            this.snackbarMessage = err.message
                            this.snackbar = true
                            this.loadingState = false
                        })
                }
            }
        },
        async getAllProvince() {
            await Rajaongkir.getAllProvince()
                .then((response) => {
                    this.province = response.data.message.rajaongkir.results
                })
        },
        async getAllProduct() {
            this.productList = []
            await productService.getAllProducts()
                .then((data) => {
                    data.forEach(e => {
                        this.productList.push(e)
                    });
                })
        },
        async getCustomerInfo() {
            if(this.id!=null) {
                await Customer.getById(this.id)
                    .then((response) => {
                        this.billing.id  = response.id
                        this.billing.name  = response.name
                        this.billing.email = response.email
                        this.billing.phone = response.phone
                        this.billing.alamat = response.alamat
                        this.billing.kodePos = response.kode_pos
                        Rajaongkir.getCustomerInfo(response.kota,response.kecamatan)
                            .then((response) => {
                                 let result = response.data.message.rajaongkir.results
                                 this.billing.provinsi = result.province
                                 this.billing.kota = result.city
                                 this.billing.kecamatan = result.subdistrict_id
                                 this.kecamatan = result.subdistrict_name
                            })
                    })
            }
        },
        async getAllPromo() {
            await Promo.getAll()
                .then((response) => this.couponList = response)
        },
        async get() {
            await this.getAllProduct()
            await this.getCustomerInfo()
            await this.getAllPromo()
            if(this.id == null) {
                await this.getAllProvince()
            }
        },
        async getCity(item) {
            this.city = []
            this.subdistrict = []
            this.courierServiceList = []
            this.billing.kota = null
            this.billing.kecamatan = null
            this.courier.name = null
            this.courier.type = null
            this.courier.price = null
            await Rajaongkir.getAllCity(+this.province[item].province_id-1)
                .then((response) => {
                    this.city = response.data.message.rajaongkir.results
                })
        },
        async getSubDistrict(item) {
            this.subdistrict = []
            this.courierServiceList = []
            this.billing.kecamatan = null
            this.courier.name = null
            this.courier.type = null
            this.courier.price = null
            // console.log(_.find(this.city, ['city_id', item]))
            await Rajaongkir.getAllSubdistrict(+_.find(this.city, ['city_id', item]).city_id)
                .then((response) => {
                    this.subdistrict = response.data.message.rajaongkir.results
                })
        },
        async getOngkir() {
            this.courier.type = null
            this.courier.price = null
            this.courierServiceList = []
            let wag = {
                kecamatan:"5512"
            }
            let totalWeight = 0
            this.products.forEach(e => {
                totalWeight+=+this.searchItem(e).weight
            });
            await Rajaongkir.getOngkir(this.billing, this.courier,wag, totalWeight)
                .then((response) => {
                    response.data.message.rajaongkir.results[0].costs.forEach(e => {
                        this.courierServiceList.push(
                            {
                                cost: e.cost[0].value,
                                est: e.cost[0].etd,
                                name: e.description+" ("+e.service+")"+" - "+e.cost[0].value+" ("+e.cost[0].etd+" Hari)"
                            }
                        )
                    });
                })
        },
        ongkirDetail(val) {
            this.courier.price = val.cost
            this.courier.type = val.name
        },
        setKecamatan(val) {
            this.courierServiceList = []
            this.courier.name = null
            this.courier.type = null
            this.courier.price = null
            this.billing.kecamatan = val
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
        searchItem(product) {
            return this.productList.find(item => item.id == product.id)
        },
        phoneRule(value) {
            if (value) {
                if (value.length > 0) {
                    if (value[0] != 0) {
                        return true
                    } return 'Phone Number is not valid (Ex: 821xxxxxx)'
                }
            } return 'Phone Number is Required'
        },
        couponRule(value) {
            if(value) {
                if(value.length > 0) {
                    if(_.find(this.couponList, ['code', value]) != undefined) {
                        return true
                    } return "Invalid Coupon"
                } 
            }
        },
        applyCoupon() {
            if(this.$refs.couponForm.validate() && this.coupon.length>0) {
                this.discount = _.find(this.couponList, ['code', this.coupon])
                if(this.discount.type == 1) {
                    this.couponText = this.discount.discount+"%"
                } else {
                    this.couponText = "Rp"+this.changeCurr(this.discount.discount)
                }
            }
        },
        clearCoupon() {
            this.discount = _.clone(this.discountDefault)
            this.couponText = ""
        }
    },

    computed: {
        cityComputed() {
            if(this.city.length>0) {
                let cities = this.city
                cities.forEach(e => {
                    e.city_name = e.type+" "+e.city_name
                });
                return cities
            } return []
        },
        disableCour() {
            if(this.billing.kecamatan==null || this.billing.kota==null || this.billing.provinsi==null) {
                return true
            } return false
        },
        calculateSubTotal() {
            let total = 0
            this.products.forEach(e => {
                total+=(+this.discountCurr(+this.searchItem(e).price,+this.searchItem(e).discount,+this.searchItem(e).discountStatus)*e.quantity)
            }); return +total
        },
        calculateDiscount() {
            if(this.discount.type != null) {
                if(this.discount.type==1) {
                    return +(this.calculateSubTotal*this.discount.discount)/100
                } else {
                    return +this.discount.discount
                }
            } else {
                return 0
            }
        },
        checkoutDisable() {
            if(this.courier.type == null || !this.checkboxTerm) {
                return true
            } return false
        }
    },

    watch: {
        'this.$store.state.product.products': function() {
            this.products = this.$store.state.product.products
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
.orderList{
  border-left: 5px solid #819C4B !important;
}
</style>