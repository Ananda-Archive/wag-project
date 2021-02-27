<template>
    <v-main>
        <v-container fluid fill-height class="px-md-16">
            <v-row no-gutters>
                <v-col cols="12" md="6">
                    <div class="display-1 mb-5 text-center">Log In</div>
                    <v-form ref="loginForm">
                        <v-row no-gutters justify="center">
                            <v-col cols="12" md="6" class="text-center">
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
                                    <v-btn width="50%" color="secondary" :disabled="loadingState" large @click="loginCustomer">Log in</v-btn>
                                </v-col>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-col>
                <v-col cols="1">
                    <v-divider vertical class="mx-10" v-if="!$vuetify.breakpoint.smAndDown"></v-divider>
                </v-col>
                <v-col cols="12" md="5" class="text-center my-8 my-md-0">
                    <div class="mb-7">
                        <div class="display-1 mb-3">New Customer</div>
                        <div>By creating an account with us, you will be able to move through the checkout process faster</div>
                        <v-btn class="mt-4 blackCustom" width="60%" tile dark @click="registerDialog = !registerDialog">Register</v-btn>
                    </div>
                    <div>
                        <div class="display-1 mb-3">Guest Checkout</div>
                        <v-btn class="mt-1 blackCustom" width="60%" tile dark @click="goTo('/checkout/billing')">Checkout</v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        <!-- Register Dialog -->
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
import Rajaongkir from "@/services/Rajaongkir"
import Customer from "@/services/Customer"
import _ from "lodash"

export default {
    data() {
        return {
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState: false,
            registerDialog: false,
            showPasswordRegister: false,
            id:this.$store.state.user.id,
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
        if(this.id == null) {
            this.get()
        } else {
            this.$router.push("/checkout/billing")
        }
    },
    
    methods: {
        goTo(path) {
            this.$router.push(path)
            window.scroll(0,0)
        },
        async getAllProvince() {
            await Rajaongkir.getAllProvince()
                .then((response) => {
                    this.province = response.data.message.rajaongkir.results
                })
        },
        async get() {
            await this.getAllProvince()
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
                            this.$router.push("/checkout/billing")
                        this.snackbar = true
                        this.loadingState = false
                    }) .catch(err => {
                        this.snackbarColor = "error"
                        this.snackbarMessage = err.message
                        this.snackbar = true
                        this.loadingState = false
                    })
            }
        },
        phoneRule(value) {
            if (value) {
                if (value.length > 0) {
                    if (value[0] != 0) {
                        return true
                    } return 'Phone Number is not valid (Ex: 821xxxxxx)'
                }
            } return 'Phone Number is Required'
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
        }
    }
}
</script>