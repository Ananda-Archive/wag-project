<template>
    <v-main class="other mr-md-0 pr-0 pr-md-6 py-16 py-md-6">
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-card class="white pa-0 pa-md-4 mt-n3 mt-md-0" elevation="2">
                        <v-col cols="12">
                            <h1 class="font-weight-regular">Promo</h1>
                        </v-col>
                        <v-col cols="12">
                            <v-data-table
                                :headers="header"
                                :items="promos"
                                style="cursor: pointer"
                            >
                                <template v-slot:[`item.ind`]="{ item }">
                                    <span>{{promos.indexOf(item)+1}}</span>
                                </template>
                                <template v-slot:[`item.actions`]="{ item }">
                                    <v-icon @click.stop="updatePromoFunc(item)" class="blue--text mr-2">mdi-pencil</v-icon>
                                    <v-icon @click.stop="deletePromoFunc(item)" class="red--text">mdi-delete</v-icon>
                                </template>
                            </v-data-table>
                        </v-col>
                        <!-- Add -->
                        <v-btn fab dark large color="secondary" fixed right bottom @click="createDialog = !createDialog">
                            <v-icon dark>mdi-plus</v-icon>
                        </v-btn>
                        <v-dialog v-model="createDialog" max-width="800px">
                            <v-card :disabled="loadingState">
                                <v-toolbar dense flat dark color="secondary">
                                    <span class="title font-weight-light">Tambah Promo</span>
                                    <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                                </v-toolbar>
                                <v-card-text class="mt-6">
                                    <v-form ref="createForm">
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    v-model="promo.name"
                                                    color="secondary"
                                                    label="Nama Promo"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" class="mt-md-0 mt-n4">
                                                <v-text-field
                                                    outlined
                                                    v-model="promo.code"
                                                    color="secondary"
                                                    label="Kode Promo"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" class="mt-n4">
                                                <v-text-field
                                                    outlined
                                                    v-model="promo.discount"
                                                    color="secondary"
                                                    label="Diskon"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" class="mt-n4">
                                                <v-select
                                                    :items="typePromo"
                                                    item-value="id"
                                                    item-text="name"
                                                    v-model="promo.type"
                                                    label="Tipe Promo"
                                                    color="secondary"
                                                    outlined
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                </v-card-text>
                                <v-card-actions class="mt-n7">
                                    <v-container>
                                        <v-row justify="center">
                                            <v-btn x-large dark  color="secondary" @click="createPromo">Buat</v-btn>
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
                        <v-dialog v-model="updateDialog" max-width="800px">
                            <v-card :disabled="loadingState">
                                <v-toolbar dense flat dark color="secondary">
                                    <span class="title font-weight-light">Update Promo</span>
                                    <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                                </v-toolbar>
                                <v-card-text class="mt-6">
                                    <v-form ref="updateForm">
                                        <v-row>
                                            <v-col cols="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    v-model="promo.name"
                                                    color="secondary"
                                                    label="Nama Promo"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" class="mt-md-0 mt-n4">
                                                <v-text-field
                                                    outlined
                                                    v-model="promo.code"
                                                    color="secondary"
                                                    label="Kode Promo"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" class="mt-n4">
                                                <v-text-field
                                                    outlined
                                                    v-model="promo.discount"
                                                    color="secondary"
                                                    label="Diskon"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6" class="mt-n4">
                                                <v-select
                                                    :items="typePromo"
                                                    item-value="id"
                                                    item-text="name"
                                                    v-model="promo.type"
                                                    label="Tipe Promo"
                                                    color="secondary"
                                                    outlined
                                                ></v-select>
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                </v-card-text>
                                <v-card-actions class="mt-n7">
                                    <v-container>
                                        <v-row justify="center">
                                            <v-btn x-large dark  color="secondary" @click="updatePromo">Update</v-btn>
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
    </v-main>
</template>

<script>
// importing stuff
import Promo from "@/services/Promo"
import _ from "lodash"

export default {
    data() {
        return {
            promos:[],
            promo: {
                id:null,
                name:"",
                code:"",
                discount:"",
                type:""
            },
            promoDefault: {
                id:null,
                name:"",
                code:"",
                discount:"",
                type:""
            },
            createDialog:false,
            updateDialog:false,
            typePromo: [
                {id:"1",name:"persen"},
                {id:"2",name:"Rupiah"},
            ],
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState:false,
            selectedIndex: -1,
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
        async getAllPromo() {
            await Promo.getAll()
                .then((response) => this.promos = response)
        },
        close() {
            this.selectedIndex = -1
            this.createDialog = false
            this.updateDialog = false
            this.promo = _.clone(this.promoDefault)
        },
        async get() {
            await this.getAllPromo()
        },
        updatePromoFunc(item) {
            this.selectedIndex = this.promos.indexOf(item)
            this.promo = _.clone(item)
            this.updateDialog = true
        },
        async createPromo() {
            if(this.$refs.createForm.validate()) {
                this.loadingState = true
                await Promo.createPromo(this.promo)
                    .then((response) => {
                        this.snackbarColor = 'success'
                        this.snackbarMessage = response.data.message
                    }) .catch(error => {
                        this.snackbarColor = 'error'
                        this.snackbarMessage = error
                    }) .finally(() => {
                        this.snackbar = true
                        this.close()
                        this.get()
                        this.loadingState = false
                    })
            }
        },
        async updatePromo() {
            if(this.$refs.updateForm.validate()) {
                this.loadingState = true
                await Promo.updatePromo(this.promo)
                    .then((response) => {
                        this.snackbarColor = 'success'
                        this.snackbarMessage = response
                    }) .catch(error => {
                        this.snackbarColor = 'error'
                        this.snackbarMessage = error
                    }) .finally(() => {
                        this.snackbar = true
                        this.close()
                        this.get()
                        this.loadingState = false
                    })
            }
        },
        deletePromoFunc(item) {
            this.selectedIndex = this.promos.indexOf(item)
            this.promo = _.clone(item)
            var a = confirm("Apakah anda yakin ingin menghapus data ini?")
            if(a) {
                Promo.deletePromo(this.promo.id)
                    .then((response) => {
                        this.snackbarColor = 'success'
                        this.snackbarMessage = response
                    }) .catch(error => {
                        this.snackbarColor = 'error'
                        this.snackbarMessage = error
                    }) .finally(() => {
                        this.snackbar = true
                        this.close()
                        this.get()
                        this.loadingState = false
                    })
            } else {
                close()
            }
        }
    },

    computed: {
        header() {
            return [
                {text:"No", value:"ind", width:"8%"},
                {text:"nama", value:"name"},
                {value:"actions", width:"10%"}
            ]
        }
    }
}
</script>