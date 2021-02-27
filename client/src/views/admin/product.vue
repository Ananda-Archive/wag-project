<template>
    <v-main class="other mr-md-0 pr-0 pr-md-6 py-16 py-md-6">
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-card class="white pa-0 pa-md-4 mt-n3 mt-md-0" elevation="2">
                        <v-col cols="12">
                            <h1 class="font-weight-regular">Produk</h1>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                outlined
                                label="Cari Nama Produk"
                                prepend-inner-icon="mdi-magnify"
                                color="secondary"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-data-table
                                :headers="header"
                                :items="products"
                            >
                                <template v-slot:[`item.index`]="{ item }">
                                    <span>{{products.indexOf(item)+1}}</span>
                                </template>
                                <template v-slot:[`item.price`]="{ item }">
                                    <span>{{changeCurr(item.price)}}</span>
                                </template>
                                <template v-slot:[`item.actions`]="{ item }">
                                    <v-icon @click.stop="updateProductFunc(item)" class="blue--text mr-2">mdi-pencil</v-icon>
                                    <v-icon @click.stop="deleteProductFunc(item)" class="red--text">mdi-delete</v-icon>
                                </template>
                            </v-data-table>
                        </v-col>
                        <!-- Add -->
                        <v-btn fab dark large color="secondary" fixed right bottom @click="createDialog = !createDialog">
                            <v-icon dark>mdi-plus</v-icon>
                        </v-btn>
                        <v-dialog v-model="createDialog" max-width="1100px" persistent>
                            <v-card :disabled="loadingState">
                                <v-toolbar dense flat dark color="secondary">
                                    <span class="title font-weight-light">Tambah Produk</span>
                                    <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                                </v-toolbar>
                                <v-card-text>
                                    <v-form ref="createForm">
                                        <v-row class="mt-4">
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="Nama Produk"
                                                    color="secondary"
                                                    v-model="product.name"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-select
                                                    outlined
                                                    label="Skin Type"
                                                    color="secondary"
                                                    :items="skinTypes"
                                                    item-text="name"
                                                    item-value="id"
                                                    class="mt-n4 mt-md-0"
                                                    v-model="product.skinType"
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="price"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.price"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="discount"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.discount"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="stock"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.stock"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="berat"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.weight"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" class="mt-n4">
                                                <vue-editor :editorToolbar="customToolbar" placeholder="Ingedients" v-model="product.ingredients"></vue-editor>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <file-pond
                                                    :allowDrop="!loadingState"
                                                    :allowBrowse="!loadingState"
                                                    style="cursor: pointer"
                                                    name="test"
                                                    ref="pond"
                                                    label-idle="<span class='filepondFormatText'>Upload Gambar Utama (Cover) </span><span class='filepondFormatText'>Format: JPG/PNG</span>"
                                                    v-bind:files="myLeadFile"
                                                    instant-upload="false"
                                                    v-on:updatefiles="handleFilePondUpdateFile"
                                                    labelInvalidField="remove"
                                                    accepted-file-types="image/*"
                                                    fileValidateTypeLabelExpectedTypes="Hanya menerima format JPG dan PNG"
                                                />
                                            </v-col>
                                             <v-col cols="12" md="6">
                                                <file-pond
                                                    :allowDrop="!loadingState"
                                                    :allowBrowse="!loadingState"
                                                    style="cursor: pointer"
                                                    name="test"
                                                    ref="pond"
                                                    label-idle="<span class='filepondFormatText'>Upload Gambar Pendukung </span><span class='filepondFormatText'>Format: JPG/PNG</span>"
                                                    v-bind:files="myFiles"
                                                    instant-upload="false"
                                                    v-on:updatefiles="handleFilesPondUpdateFile"
                                                    allowMultiple="true"
                                                    labelInvalidField="remove"
                                                    accepted-file-types="image/*"
                                                    fileValidateTypeLabelExpectedTypes="Hanya menerima format JPG dan PNG"
                                                />
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                    <v-card-actions class="py-0">
                                        <v-container>
                                            <v-row justify="center">
                                                <v-btn color="secondary white--text" @click="createProduct">Tambah Produk Baru</v-btn>
                                            </v-row>
                                        </v-container>
                                    </v-card-actions>
                                </v-card-text>
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
                        <!-- Update -->
                        <v-dialog v-model="updateDialog" max-width="1100px" persistent>
                            <v-card :disabled="loadingState">
                                <v-toolbar dense flat dark color="secondary">
                                    <span class="title font-weight-light">Tambah Produk</span>
                                    <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                                </v-toolbar>
                                <v-card-text>
                                    <v-form ref="createForm">
                                        <v-row class="mt-4">
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="Nama Produk"
                                                    color="secondary"
                                                    v-model="product.name"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-select
                                                    outlined
                                                    label="Skin Type"
                                                    color="secondary"
                                                    :items="skinTypes"
                                                    item-text="name"
                                                    item-value="id"
                                                    class="mt-n4 mt-md-0"
                                                    v-model="product.skinType"
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="price"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.price"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="discount"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.discount"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="stock"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.stock"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="6">
                                                <v-text-field
                                                    outlined
                                                    label="Berat"
                                                    color="secondary"
                                                    class="mt-n4"
                                                    v-model="product.weight"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12">
                                                <v-checkbox
                                                    class="mt-n4"
                                                    label="Discount Status"
                                                    v-model="product.discountStatus"
                                                    false-value="0"
                                                    true-value="1"
                                                    color="secondary"
                                                ></v-checkbox>
                                            </v-col>
                                            <v-col cols="12" class="mt-n4">
                                                <vue-editor :editorToolbar="customToolbar" placeholder="Ingedients" v-model="product.ingredients"></vue-editor>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <file-pond
                                                    :allowDrop="!loadingState"
                                                    :allowBrowse="!loadingState"
                                                    style="cursor: pointer"
                                                    name="test"
                                                    ref="pond"
                                                    label-idle="<span class='filepondFormatText'>Upload Gambar Utama (Cover) </span><span class='filepondFormatText'>Format: JPG/PNG</span>"
                                                    v-bind:files="myLeadFile"
                                                    instant-upload="false"
                                                    v-on:updatefiles="handleFilePondUpdateFile"
                                                    labelInvalidField="remove"
                                                    accepted-file-types="image/*"
                                                    fileValidateTypeLabelExpectedTypes="Hanya menerima format JPG dan PNG"
                                                />
                                            </v-col>
                                             <v-col cols="12" md="6">
                                                <file-pond
                                                    :allowDrop="!loadingState"
                                                    :allowBrowse="!loadingState"
                                                    style="cursor: pointer"
                                                    name="test"
                                                    ref="pond"
                                                    label-idle="<span class='filepondFormatText'>Upload Gambar Pendukung </span><span class='filepondFormatText'>Format: JPG/PNG</span>"
                                                    v-bind:files="myFiles"
                                                    instant-upload="false"
                                                    v-on:updatefiles="handleFilesPondUpdateFile"
                                                    allowMultiple="true"
                                                    labelInvalidField="remove"
                                                    accepted-file-types="image/*"
                                                    fileValidateTypeLabelExpectedTypes="Hanya menerima format JPG dan PNG"
                                                />
                                            </v-col>
                                            <v-col cols="12">
                                                <v-expansion-panels v-model="productLeadPanel" flat hover>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>Gambar Utama</v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-row align="center">
                                                                <v-col cols="12" sm="12" md="4">
                                                                    <v-card v-if="product.imageLead != ''">
                                                                        <v-img :src=product.imageLead :lazy-src=product.imageLead height="300px">
                                                                            <template v-slot:placeholder>
                                                                                <v-row
                                                                                    class="fill-height ma-0"
                                                                                    align="center"
                                                                                    justify="center"
                                                                                >
                                                                                    <v-progress-circular indeterminate color="black"></v-progress-circular>
                                                                                </v-row>
                                                                            </template>
                                                                            <v-col cols="12" class="text-right"><v-btn icon class="red--text" style="background-color:rgba(255,255,255,.5)" small @click="moveLeadTrash"><v-icon>mdi-close</v-icon></v-btn></v-col>
                                                                        </v-img>
                                                                    </v-card>
                                                                </v-col>
                                                            </v-row>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-col>
                                            <v-col cols="12">
                                                <v-expansion-panels v-model="productPanel" flat hover>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>Gambar Pendukung</v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-row align="center">
                                                                <v-col cols="12" sm="12" md="4" v-for="(img, idx) in product.images" :key="idx">
                                                                    <v-card>
                                                                        <v-img :src=img :lazy-src=img height="300px">
                                                                            <template v-slot:placeholder>
                                                                                <v-row
                                                                                    class="fill-height ma-0"
                                                                                    align="center"
                                                                                    justify="center"
                                                                                >
                                                                                    <v-progress-circular indeterminate color="black"></v-progress-circular>
                                                                                </v-row>
                                                                            </template>
                                                                            <v-col cols="12" class="text-right"><v-btn icon class="red--text" style="background-color:rgba(255,255,255,.5)" small @click="moveTrash(img)"><v-icon>mdi-close</v-icon></v-btn></v-col>
                                                                        </v-img>
                                                                    </v-card>
                                                                </v-col>
                                                            </v-row>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                    <v-card-actions class="py-0">
                                        <v-container>
                                            <v-row justify="center">
                                                <v-btn color="secondary white--text" @click="updateProduct">Update Produk</v-btn>
                                            </v-row>
                                        </v-container>
                                    </v-card-actions>
                                </v-card-text>
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
import _ from "lodash"
import { VueEditor } from "vue2-editor"
import productService from '@/services/Product'
import firebase from 'firebase';
import vueFilePond from 'vue-filepond'
import 'filepond/dist/filepond.min.css'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
)

export default {
    components: {
        VueEditor,
        FilePond
    },
    data() {
        return {
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState: false,
            createDialog: false,
            updateDialog: false,
            productPanel: false,
            productLeadPanel: false,
            selectedIdx: -1,
            selectedIndexImg: -1,
            selectedImages: [],
            products: [],
            product: {
                id:"",
                name:"",
                skinType:"",
                price:"",
                discount:null,
                discountStatus:null,
                stock:null,
                ingredients:"",
                weight:null,
                imageLead:"",
                images:[],
            },
            productDefault: {
                id:"",
                name:"",
                skinType:"",
                price:"",
                discount:null,
                discountStatus:null,
                stock:null,
                ingredients:"",
                weight:null,
                imageLead:"",
                images:[],
            },
            myLeadFile:[],
            myFiles:[],
            skinTypes: [
                {id:"1", name:"Have my own glam! - Normal skin"},
                {id:"2", name:"Bye bye shiny! - Oily skin"},
                {id:"3", name:"Make me dewy! - Dry skin"},
                {id:"4", name:"Acne go away! - Acne prone"}
            ],
            customToolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link'],
                [{ 'direction': 'rtl' }],
                ['clean'],
            ],
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

        close() {
            this.createDialog = false
            this.updateDialog = false
            this.productPanel = false
            this.productLeadPanel = false
            this.product = _.cloneDeep(this.productDefault)
            this.myLeadFile = []
            this.myFiles = []
            this.selectedImages = []
            this.selectedIdx= -1
        },
        deleteProductFunc(item) {
            this.selectedIdx = this.products.indexOf(item)
            this.product = _.clone(item)
            var a = confirm("Apakah anda yakin ingin menghapus data ini?")
            if(a) {
                productService.deleteProduct(this.product.id)
                    .then((response) => {
                        this.snackbarColor = 'success'
                        this.snackbarMessage = response.message
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
        },
        updateProductFunc(item) {
            this.selectedIdx = this.products.indexOf(item)
            this.product = _.clone(item)
            const a = []
            this.product.images.forEach(e => {
                a.push(e.image)
            });
            this.product.imageLead = this.product.imageLead[0].image
            this.product.images = a
            this.updateDialog = true
        },
        moveLeadTrash() {
            this.product.imageLead = ""
        },
        moveTrash(img) {
            this.selectedIndexImg = this.product.images.indexOf(img)
            this.selectedImages.push(img)
            this.product.images.splice(this.selectedIndexImg, 1)
        },
        deleteImage(imageFile) {
            return new Promise((resolve, reject) => {
                let image = firebase.storage().refFromURL(imageFile)
                image.delete()
                .then(() => {
                    resolve()
                }) .catch((err) => {
                    reject(err)
                })
            })
        },
        handleFilePondUpdateFile(files) {
            this.myLeadFile = files.map(files => files.file);
        },
        handleFilesPondUpdateFile(files) {
            this.myFiles = files.map(files => files.file);
        },
        uploadImg(imgFile) {
            return new Promise((resolve, reject) => {
                var storageRef = firebase.storage().ref('product/'+Math.random() + '_'  + imgFile.name) 
                storageRef.put(imgFile)
                .then((snapshot) => {
                    resolve(snapshot.ref.getDownloadURL())
                })
                .catch((err) => {
                    reject(err)
                })
            })
        },
        // Create Product
        async createProduct() {
            // if(this.$refs.form.validate()) {
                this.loadingState = true
                // Upload Image lead
                await this.uploadImg(this.myLeadFile[0])
                    .then((response) => {
                        this.product.imageLead = response
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                // Upload Images
                for(let i=0; i<this.myFiles.length; i++) {
                    await this.uploadImg(this.myFiles[i])
                        .then((response) => {
                            this.product.images.push(response)
                        })
                        .catch((err) => {
                            console.log(err)
                        })
                }
                productService.createProduct(this.product)
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

            // }
        },

        // Update
        async updateProduct() {
            // if(this.$refs.form.validate()) {
                this.loadingState = true
                // Upload Image lead
                await this.uploadImg(this.myLeadFile[0])
                    .then((response) => {
                        this.product.imageLead = response
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                // Upload Images
                for(let i=0; i<this.myFiles.length; i++) {
                    await this.uploadImg(this.myFiles[i])
                        .then((response) => {
                            this.product.images.push(response)
                        })
                        .catch((err) => {
                            console.log(err)
                        })
                }
                for(let i=0; i<this.selectedImages.length; i++) {
                    await this.deleteImage(this.selectedImages[i])
                        .then((response) => {
                            console.log(response)
                        })
                        .catch((err) => {
                            console.log(err)
                        })
                }
                productService.updateProduct(this.product)
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
            // }
        },

        get() {
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
            return temp.slice(0, -7)
        },

        changeBool(val) {
            if (val == "1") {
                return true
            } return false
        }
    },

    computed: {
        header() {
            return [
                {text:"No", value:"index", width:"5%"},
                {text:"Nama Produk", value:"name"},
                {text:"Harga", value:"price", width:"15%"},
                {text:"Stok", value:"stock", width:"5%"},
                {value:"actions", width:"10%"},
            ]
        }   
    },
}
</script>
<style>
.v-dialog > .v-card > .v-toolbar {
  position: sticky;
  top: 0;
  z-index: 999;
}
</style>