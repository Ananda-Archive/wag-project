<template>
    <v-main class="px-md-16">
        <v-container fluid fill-height>
            <v-row justify="center" align="center">
                <v-col cols="12" md="8" xl="4" class="my-8">
                    <v-card class="pt-4">
                        <v-card-title class="justify-center font-weight-regular text-h3 mb-4">
                            <div>Payment <span v-if="orderDetail.status == 0 || orderDetail.status == 1">Method</span><span v-else>Status</span></div>
                        </v-card-title>
                        <v-card-subtitle class="text-center">{{orderDetail.name}}</v-card-subtitle>
                        <v-card-subtitle class="text-center mt-n6">Nomor Resi <span class="font-weight-bold">{{orderDetail.resi}}</span></v-card-subtitle>
                        <v-row no-gutters justify="center">
                            <v-col cols="8"><v-divider></v-divider></v-col>
                        </v-row>
                        <v-card-text class="mt-4 mb-n4" v-if="orderDetail.status == 0">
                            <ol>
                                <li>Link ini sudah kami kirim ke e-mail pembeli bersama dengan invoice</li>
                                <li>Lakukan Pembayaran <span class="font-weight-bold">Hanya ke Rekening Berikut (Pilih Salah Satu):</span></li>
                                <ul>
                                    <li><span class="font-weight-bold">Bank Mandiri</span></li>
                                    No. Rekening XXX An. CV GLAM ESTETIKA
                                    <li><span class="font-weight-bold">Bank BCA</span></li>
                                    No. Rekening XXX An. CV GLAM ESTETIKA
                                    <li><span class="font-weight-bold">Bank BNI</span></li>
                                    No. Rekening XXX An. CV GLAM ESTETIKA
                                </ul>
                                <li>Pembayaran akan diverifikasi dalam 1x24 jam kerja</li>
                                <ul>
                                    <li>Senin - Jum'at: 09:00 - 16:00 WIB</li>
                                    <li>Sabtu: 09:00 - 15:00 WIB</li>
                                </ul>
                                <li>Konfirmasi Payment dapat dilakukan dengan mengupload bukti transfer</li>
                            </ol>
                            <v-btn class="mt-7 secondary" dark width="100%" x-large @click="uploadDialog = !uploadDialog">Upload Bukti Transfer</v-btn>
                            <div class="mt-4 text-center"><a href="" class="secondary--text">Our Shipping Policy</a></div>
                        </v-card-text>
                        <v-card-text class="mt-4 mb-n4" v-else>
                            <ol>
                                <li>Pembayaran akan diverifikasi dalam 1x24 jam kerja</li>
                                <ul>
                                    <li>Senin - Jum'at: 09:00 - 16:00 WIB</li>
                                    <li>Sabtu: 09:00 - 15:00 WIB</li>
                                </ul>
                            </ol>
                            <div class="text-center headline mt-4 secondary--text">Status <span v-if="orderDetail.status == 0 || orderDetail.status == 1">Pembayaran</span><span v-else>Pesanan</span></div>
                            <div class="text-center body-1 my-4" v-if="orderDetail.status == 1">Proses</div>
                            <div class="text-center body-1 my-4" v-if="orderDetail.status == 2">Dikirim</div>
                            <div class="text-center body-1 my-4" v-if="orderDetail.status == 3">Selesai</div>
                            <div class="text-center body-1 my-4" v-if="orderDetail.status == 4">Ditolak</div>
                        </v-card-text>
                        <div class="px-6 pb-6">
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div>Subtotal</div>
                                <div class="font-weight-bold">Rp{{changeCurr(calculateSubTotal(orderDetail.products))}}</div>
                            </div>
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div>Shipping</div>
                                <div class="font-weight-bold">Rp{{changeCurr(orderDetail.shippingCost)}}</div>
                            </div>
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div>Discount</div>
                                <div class="font-weight-bold" v-if="orderDetail.promoId != null">Rp{{changeCurr(calculateDiscount(orderDetail.promo,orderDetail.products))}}</div>
                                <div class="font-weight-bold" v-else>Rp{{changeCurr(0)}}</div>
                            </div>
                            <v-divider class="my-6"></v-divider>
                            <div class="d-flex flex-no-wrap justify-space-between align-center body-1">
                                <div class="font-weight-bold secondary--text">TOTAL</div>
                                <div class="font-weight-bold secondary--text" v-if="orderDetail.promoId != null">Rp{{changeCurr(+calculateSubTotal(orderDetail.products)+(+orderDetail.shippingCost)-calculateDiscount(orderDetail.promo,orderDetail.products))}}</div>
                                <div class="font-weight-bold secondary--text" v-else>Rp{{changeCurr(+calculateSubTotal(orderDetail.products)+(+orderDetail.shippingCost))}}</div>
                            </div>
                        </div>
                        <v-card-text class="font-weight-bold mt-4">Products</v-card-text>
                        <v-list dense class="mt-n4 mb-8 pb-8">
                            <v-list-item v-for="(item, idx) in orderDetail.products" :key="idx">
                                <v-list-item-avatar size="80"><img :src="item.imageLead[0].image"></v-list-item-avatar>
                                <v-list-item-content>
                                    <div class="d-flex flex-no-wrap justify-space-between align-center">
                                        <div>
                                            <v-list-item-title class="body-1 mb-2">{{item.name}}</v-list-item-title>
                                            <v-list-item-text>Rp{{changeCurr(discountCurr(item.price, item.discount, item.discountStatus))}} x <span class="red--text">{{item.quantity}}</span></v-list-item-text>
                                        </div>
                                    </div>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                        <div v-if="orderDetail.status ==3">
                            <v-divider class="my-6 mx-6"></v-divider>
                            <v-card-title class="justify-center">Write Us A Review</v-card-title>
                            <v-card-subtitle class="text-center font-italic">"Your comment is a key to our success!"</v-card-subtitle>
                            <div>
                                <v-list dense class="mt-n4 mb-8 pb-8">
                                    <div v-for="(item, idx) in products" :key="idx">
                                        <v-list-item>
                                            <v-list-item-avatar size="80"><img :src="item.imageLead[0].image"></v-list-item-avatar>
                                            <v-list-item-content>
                                                <div class="d-flex flex-no-wrap justify-space-between align-center">
                                                    <div>
                                                        <v-list-item-title class="body-1 mb-2">{{item.name}}</v-list-item-title>
                                                        <v-rating
                                                            empty-icon="mdi-star-outline"
                                                            full-icon="mdi-star"
                                                            half-icon="mdi-star-half-full"
                                                            hover
                                                            length="5"
                                                            color="secondary"
                                                            v-model="item.review"
                                                        ></v-rating>
                                                    </div>
                                                </div>
                                            </v-list-item-content>
                                        </v-list-item>
                                        <div class="px-6 pt-4">
                                            <v-text-field
                                                outlined
                                                label="Message"
                                                v-model="item.reviewMessage"
                                                color="secondary"
                                            ></v-text-field>
                                        </div>
                                    </div>
                                </v-list>
                            </div>
                        </div>
                        <v-btn v-if="orderDetail.status == 2" x-large width="100%" color="secondary" dark tile @click="confirmPaymentDialog = !confirmPaymentDialog">Selesai</v-btn>
                        <v-btn v-if="orderDetail.status == 3" x-large width="100%" color="secondary" dark tile @click="writeReview">Kirim Review</v-btn>
                    </v-card>
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
        <v-dialog v-model="uploadDialog" max-width="600px">
            <v-card>
                <v-card-title class="justify-center font-weight-regular text-h4 pt-8">Payment Confirmation</v-card-title>
                <div>
                    <v-card-text class="mt-4 px-8">
                        <v-form ref="registerForm">
                            <v-row>
                                <v-col cols="12">
                                    <v-file-input
                                        v-model="file"
                                        outlined
                                        placeholder="Select Your File"
                                        prepend-icon=""
                                        append-icon="mdi-upload"
                                        accept="application/pdf,.jpg, .jpeg, .png"
                                        show-size
                                        color="secondary"
                                    ></v-file-input>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                    <v-card-actions class="mt-n5 pb-8">
                        <v-container>
                            <v-row justify="center">
                                <v-btn large width="50%" color="secondary white--text" @click="uploadBukti">Upload</v-btn>
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
        <v-dialog persistent v-model="confirmPaymentDialog" width="500px">
            <v-card>
                <v-card-title>Konfirmasi</v-card-title>
                <v-card-text>Apakah Anda Yakin ingin menyelesaikan pesanan ini?</v-card-text>
                <v-card-actions>
                    <v-container>
                        <v-row justify="center">
                            <v-btn :disabled="loadingState" class="mt-n5" color="red darken-1" text @click="confirmPaymentDialog = !confirmPaymentDialog">Tidak</v-btn>
                            <v-btn :disabled="loadingState" class="mt-n5" color="blue darken-1" text @click="confirmPayment">Ya</v-btn>
                        </v-row>
                    </v-container>
                </v-card-actions>
                    <v-progress-linear v-if="loadingState" color="primary" :indeterminate="loadingState"></v-progress-linear>
            </v-card>
        </v-dialog>
        <v-overlay :value="loadingStateOverlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </v-main>
</template>

<script>
// importing stuff
import firebase from "firebase"
import Orderhistory from "@/services/Orderhistory"
import Itemorder from "@/services/Itemorder"

export default {
    data() {
        return {
            id:this.$route.params.id,
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState: false,
            loadingStateOverlay: false,
            uploadDialog:false,
            file:null,
            confirmPaymentDialog:false,
            orderDetail:{},
            products:[],
        }
    },

    created() {
        this.get()
    },
    
    methods: {
        async get() {
            await this.getOrderDetail()
        },
        async getOrderDetail() {
            await Orderhistory.getById(this.id)
                .then((response) => {
                    this.orderDetail = response
                    this.products = response.products
                })
        },
        uploadImg(imgFile) {
            return new Promise((resolve, reject) => {
                var storageRef = firebase.storage().ref('bukti/'+Math.random() + '_'  + imgFile.name) 
                storageRef.put(imgFile)
                .then((snapshot) => {
                    resolve(snapshot.ref.getDownloadURL())
                })
                .catch((err) => {
                    reject(err)
                })
            })
        },
        async uploadBukti() {
            this.loadingState = true
            await this.uploadImg(this.file)
                .then((response) => {
                    Orderhistory.uploadBukti(response,this.id)
                        .then((response) => {
                            this.snackbarColor = 'success'
                            this.snackbarMessage = response.data.message
                        }) .catch(error => {
                            this.snackbarColor = 'error'
                            this.snackbarMessage = error
                        }) .finally(() => {
                            this.snackbar = true
                            this.uploadDialog = false
                            this.loadingState = false
                            this.get()
                        })
                })
        },
        async confirmPayment() {
            this.loadingState = true
            await Orderhistory.changeStatus(this.orderDetail.id,3)
                .then((response) => {
                    this.snackbarColor = 'success'
                    this.snackbarMessage = response
                }) .catch(error => {
                    this.snackbarColor = 'error'
                    this.snackbarMessage = error
                }) .finally(() => {
                    this.snackbar = true
                    this.confirmPaymentDialog = false
                    this.loadingState = false
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
        writeReview() {
            this.loadingStateOverlay = true
            this.orderDetail.products.forEach(e => {
                Itemorder.writeReview(e)
                    .then((response) => {
                        this.snackbarColor = 'success'
                        this.snackbarMessage = response
                    }) .catch(error => {
                        this.snackbarColor = 'error'
                        this.snackbarMessage = error
                    }) .finally(() => {
                        this.snackbar = true
                        this.loadingStateOverlay = false
                        this.get()
                    })
            });
        }
    },

    computed: {
        
    }
}
</script>