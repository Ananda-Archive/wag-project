<template>
    <v-main class="other mr-md-0 pr-0 pr-md-6 py-16 py-md-6">
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-card class="white pa-0 pa-md-4 mt-n3 mt-md-0" elevation="2">
                        <v-col cols="12">
                            <h1 class="font-weight-regular">Order</h1>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                outlined
                                label="Cari Kode Invoice"
                                prepend-inner-icon="mdi-magnify"
                                color="secondary"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="mb-n4 mt-n8">
                            <v-btn dark class="warning" @click="remindCustomer">Remind Buyer</v-btn>
                        </v-col>
                        <v-col cols="12">
                            <v-data-table
                                :headers="header"
                                :items="orders"
                                @click:row="detail"
                                style="cursor: pointer"
                            >
                                <template v-slot:[`item.index`]="{ item }">
                                    <span>{{orders.indexOf(item)+1}}</span>
                                </template>
                                <template v-slot:[`item.status`]="{ item }">
                                    <span v-if="item.status==0">Proses Pembayaran</span>
                                    <span v-if="item.status==1">Menunggu Konfirmasi</span>
                                    <span v-if="item.status==2">Dikirim</span>
                                    <span v-if="item.status==3">Selesai</span>
                                    <span v-if="item.status==4">Ditolak</span>
                                </template>
                                <template v-slot:[`item.actions`]="{ item }">
                                    <v-icon @click.stop="sendResiDialogFunc(item)" class="blue--text mr-2" :disabled="item.status != 2">mdi-open-in-new</v-icon>
                                    <v-icon @click.stop="deleteDialog(item)" class="red--text">mdi-delete</v-icon>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="updateDialog" width="800px" persistent>
            <v-card :disabled="loadingState">
                <v-toolbar dense flat dark color="secondary">
                    <span class="title font-weight-light">Invoice</span>
                    <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text class="mt-8">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                outlined
                                v-model="order.customer.name"
                                readonly
                                color="secondary"
                                label="Nama"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.customer.email"
                                readonly
                                color="secondary"
                                label="E-Mail"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.courier"
                                readonly
                                color="secondary"
                                label="Kurir"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.courierType"
                                readonly
                                color="secondary"
                                label="Jenis"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="mt-n6">
                            <v-text-field
                                v-if="order.promoId==null"
                                outlined
                                v-model="order.promoId"
                                readonly
                                color="secondary"
                                label="Kode Voucher"
                            ></v-text-field>
                            <v-text-field
                                v-else
                                outlined
                                v-model="order.promo.name"
                                readonly
                                color="secondary"
                                label="Kode Voucher"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.customer.provinsi"
                                readonly
                                color="secondary"
                                label="Provinsi"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.customer.kota"
                                readonly
                                color="secondary"
                                label="Kota"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.customer.kecamatan"
                                readonly
                                color="secondary"
                                label="Kecamatan"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="mt-n6">
                            <v-text-field
                                outlined
                                v-model="order.customer.kode_pos"
                                readonly
                                color="secondary"
                                label="Kode Pos"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="my-n6 pb-0">
                            <v-textarea
                                outlined
                                v-model="order.customer.alamat"
                                label="Alamat"
                                readonly
                                color="secondary"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                            <v-card-text>Products</v-card-text>
                            <v-list dense>
                                <v-list-item v-for="(item, idx) in order.products" :key="idx">
                                    <v-list-item-avatar size="80"><img :src="item.imageLead[0].image"></v-list-item-avatar>
                                    <v-list-item-content>
                                        <div class="d-flex flex-no-wrap justify-space-between align-center">
                                            <div>
                                                <v-list-item-title>{{item.name}}</v-list-item-title>
                                                <v-list-item-text>Rp{{changeCurr(discountCurr(item.price, item.discount, item.discountStatus))}} x <span class="red--text">{{item.quantity}}</span></v-list-item-text>
                                            </div>
                                        </div>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-col>
                        <v-col cols="12">
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div>Subtotal</div>
                                <div class="font-weight-bold">Rp{{changeCurr(calculateSubTotal(order.products))}}</div>
                            </div>
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div>Shipping</div>
                                <div class="font-weight-bold">Rp{{changeCurr(order.shippingCost)}}</div>
                            </div>
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div>Discount</div>
                                <div class="font-weight-bold" v-if="order.promoId != null">Rp{{changeCurr(calculateDiscount(order.promo,order.products))}}</div>
                                <div class="font-weight-bold" v-else>Rp{{changeCurr(0)}}</div>
                            </div>
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div class="font-weight-bold secondary--text">TOTAL</div>
                                <div class="font-weight-bold secondary--text" v-if="order.promoId != null">Rp{{changeCurr(+calculateSubTotal(order.products)+(+order.shippingCost)-calculateDiscount(order.promo,order.products))}}</div>
                                <div class="font-weight-bold secondary--text" v-else>Rp{{changeCurr(+calculateSubTotal(order.products)+(+order.shippingCost))}}</div>
                            </div>
                        </v-col>
                        <v-col cols="12">
                            <v-expansion-panels v-model="orderPanel" flat hover>
                                <v-expansion-panel>
                                    <v-expansion-panel-header>Bukti</v-expansion-panel-header>
                                    <v-expansion-panel-content>
                                        <v-img @click="clickBukti(order.bukti)" v-if="order.bukti!=null" :src=order.bukti :lazy-src=order.bukti height="1000px">
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
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-container>
                        <v-row justify="center" class="mb-8" v-if="order.status != 4 && order.status != 3 && order.status != 2">
                            <v-btn large color="red" text class="mr-4" @click="paymentConfirmation(4)" :disabled="order.status == 2">Tolak</v-btn>
                            <v-btn large color="secondary white--text" @click="paymentConfirmation(2)" :disabled="order.status==0 || order.status == 2">Terima</v-btn>
                        </v-row>
                        <v-row justify="center" class="mb-8" v-if="order.status == 2">
                            <v-btn large color="secondary" dark @click="confirmPayment">Selesai</v-btn>
                        </v-row>
                    </v-container>
                </v-card-actions>
                <v-progress-linear
                    v-if="loadingState"
                    :indeterminate="loadingState"
                    color="green"
                    height="7"
                ></v-progress-linear>
                <!-- 
                    Loading Inactive when loadingState is FALSE 
                    Dibuat 2 - di mana 1 transparant, agar saat loading aktif tidak tiba tiba menambah tempat di design, sehingga di "reserve" terlebih dahulu tempatnya
                -->
                <v-progress-linear
                    v-else
                    :indeterminate="loadingState"
                    color="transparent"
                    height="7"
                ></v-progress-linear>
            </v-card>
        </v-dialog>
        <v-dialog v-model="sendResiDialog" max-width="600px">
            <v-card :disabled="loadingState">
                <v-toolbar dense flat dark color="secondary">
                    <span class="title font-weight-light">Kirim Resi</span>
                    <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-form ref="resiForm">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    class="mt-7"
                                    outlined
                                    v-model="order.resi"
                                    color="secondary"
                                    label="Resi"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-container>
                        <v-row justify="center">
                            <v-btn class="mt-n8 secondary" dark large @click="sendResi">Kirim Resi</v-btn>
                        </v-row>
                    </v-container>
                </v-card-actions>
                 <v-progress-linear
                    v-if="loadingState"
                    :indeterminate="loadingState"
                    color="green"
                    height="7"
                ></v-progress-linear>
                <!-- 
                    Loading Inactive when loadingState is FALSE 
                    Dibuat 2 - di mana 1 transparant, agar saat loading aktif tidak tiba tiba menambah tempat di design, sehingga di "reserve" terlebih dahulu tempatnya
                -->
                <v-progress-linear
                    v-else
                    :indeterminate="loadingState"
                    color="transparent"
                    height="7"
                ></v-progress-linear>
            </v-card>
        </v-dialog>
        <v-overlay :value="loadingStateOverlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
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
    </v-main>
</template>

<script>
// importing stuff
import Orderhistory from "@/services/Orderhistory"
import Product from "@/services/Product"
import Rajaongkir from "@/services/Rajaongkir"
import _ from "lodash"

export default {
    data() {
        return {
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            orders:[],
            updateDialog: false,
            orderPanel: false,
            sendResiDialog: false,
            selectedIndex: -1,
            order: {
                bukti:null,
                courier:"",
                courierType:"",
                customer:{

                },
                id:"",
                name:"",
                note:"",
                products:[],
                promoId:null,
                promo:{

                },
                shippingCost:"",
                status:null,
                userId:"",
                resei:""
            },
            orderDefault: {
                bukti:null,
                courier:"",
                courierType:"",
                customer:{

                },
                id:"",
                name:"",
                note:"",
                products:[],
                promoId:null,
                promo:{

                },
                shippingCost:"",
                status:null,
                userId:"",
                resi:""
            },
            loadingState: false,
            loadingStateOverlay: false
        }
    },

    created() {
        if(this.$store.state.user.username == "wagadm") {
            this.get()
        } else {
            this.$router.push("/")
        }
    },
    
    methods: {

        remindCustomer() {
            var result = confirm("Ingin Mengirim E-mail Pengingat kepada Pembeli?")
            this.loadingStateOverlay = true
            if(result) {
                Orderhistory.remindCustomer()
                    .then((response) => {
                        this.snackbarColor = 'success'
                        this.snackbarMessage = response.data.message
                        console.log(response.data.message)
                    }) .catch(error => {
                        this.snackbarColor = 'error'
                        this.snackbarMessage = error
                    }) .finally(() => {
                        this.snackbar = true
                        this.loadingStateOverlay = false
                    })
            }
        },

        async get() {
            await this.getAllOrders()
        },

        async getAllOrders() {
            await Orderhistory.getAll()
                .then((response) => {
                    this.orders = response
                })
        },

        async confirmPayment() {
            this.loadingState = true
            await Orderhistory.changeStatus(this.order.id,3)
                .then((response) => {
                    this.snackbarColor = 'success'
                    this.snackbarMessage = response
                }) .catch(error => {
                    this.snackbarColor = 'error'
                    this.snackbarMessage = error
                }) .finally(() => {
                    this.snackbar = true
                    this.loadingState = false
                    this.get()
                    this.close()
                })
        },

        async paymentConfirmation(status) {
            this.loadingState = true
            await Orderhistory.changeStatus(this.order.id,status)
                .then(() => {
                    if(status != 4) {
                        this.order.products.forEach(e => {
                            let product = {
                                id:e.id,
                                stock:(+e.stock)-(+e.quantity)
                            }
                            Product.updateProduct(product)
                                .then((response) => {
                                    console.log(response)
                                }) .catch((err) => {
                                    console.log(err)
                                })
                        });
                    }
                }) .then(() => {
                        this.loadingState = false
                        this.snackbarColor = 'success'
                        this.snackbarMessage = "Update Success"
                        this.snackbar = true
                        this.close()
                        this.get()
                }) .catch((err) => {
                    console.log(err)
                    this.loadingState = false
                    this.snackbarColor = 'error'
                    this.snackbarMessage = err
                    this.snackbar = true
                    this.close()
                    this.get()
                })
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
        async detail(item){
            this.selectedIndex = this.orders.indexOf(item)
            this.order = _.clone(item)
            this.updateDialog = true
            await Rajaongkir.getCustomerInfo(this.order.customer.kota,this.order.customer.kecamatan)
                .then((response) => {
                    let result = response.data.message.rajaongkir.results
                    this.order.customer.provinsi = result.province
                    this.order.customer.kota = result.city
                    this.order.customer.kecamatan = result.subdistrict_name
                })
        },

        sendResiDialogFunc(item) {
            this.selectedIndex = this.orders.indexOf(item)
            this.order = _.clone(item)
            this.sendResiDialog = true
        },

        async sendResi() {
            this.loadingState = true
            await Orderhistory.sendResi(this.order.id,this.order.resi)
                .then((response) => {
                    this.snackbarColor = 'success'
                    this.snackbarMessage = response
                }) .catch(error => {
                    this.snackbarColor = 'error'
                    this.snackbarMessage = error
                }) .finally(() => {
                    this.snackbar = true
                    this.loadingState = false
                    this.get()
                    this.close()
                })
        },

        close() {
            this.updateDialog = false
            this.sendResiDialog = false
            this.order = _.clone(this.orderDefault)
            this.selectedIndex = -1
            this.orderPanel = false
        },
        clickBukti(img) {
            window.open(img, '_blank');
        },
        calculateSubTotal(products) {
            let total = 0
            products.forEach(e => {
                total+=(+this.discountCurr(+e.price,+e.discount,+e.discountStatus)*e.quantity)
            }); return +total
        },
        calculateDiscount(discount,products) {
            if(discount.type != null) {
                if(discount.type==1) {
                    return +(this.calculateSubTotal(products)*discount.discount)/100
                } else {
                    return +discount.discount
                }
            } else {
                return 0
            }
        },
    },

    computed: {
        header() {
            return [
                {text:"No", value:"index", width:"8%"},
                {text:"Invoice", value:"name"},
                {text:"Status", value:"status", width:"20%"},
                {value:"actions", width:"10%"},
            ]
        },
    }
}
</script>

<style>
.v-dialog > .v-card > .v-toolbar {
  position: sticky;
  top: 0;
  z-index: 999;
}
</style>