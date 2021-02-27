<template>
    <v-main class="other mr-md-0 pr-0 pr-md-6 py-16 py-md-6">
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-card class="white pa-0 pa-md-4 mt-n3 mt-md-0" elevation="2">
                        <v-col cols="12">
                            <h1 class="font-weight-regular">Self Discovery</h1>
                        </v-col>
                        <v-col cols="12">
                            <v-data-table
                                :headers="header"
                                :items="selfDiscoveries"
                                style="cursor: pointer"
                            >
                                <template v-slot:[`item.ind`]="{ item }">
                                    <span>{{selfDiscoveries.indexOf(item)+1}}</span>
                                </template>
                                <template v-slot:[`item.actions`]="{ item }">
                                    <v-icon @click.stop="updateSelfFunc(item)" class="blue--text mr-2">mdi-pencil</v-icon>
                                    <v-icon @click.stop="deleteSelfFunc(item)" class="red--text">mdi-delete</v-icon>
                                </template>
                            </v-data-table>
                        </v-col>
                        <!-- Add -->
                        <v-btn fab dark large color="secondary" fixed right bottom @click="createDialog = !createDialog">
                            <v-icon dark>mdi-plus</v-icon>
                        </v-btn>
                    </v-card>
                    <v-dialog v-model="createDialog" max-width="800px">
                        <v-card :disabled="loadingState">
                            <v-toolbar dense flat dark color="secondary">
                                <span class="title font-weight-light">Tambah Artikel</span>
                                <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                            </v-toolbar>
                            <v-card-text class="mt-6">
                                <v-form ref="createForm">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                outlined
                                                v-model="selfDiscovery.title"
                                                label="Title"
                                                color="secondary"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" class="mt-n4">
                                            <vue-editor :editorToolbar="customToolbar" placeholder="Content" v-model="selfDiscovery.content"></vue-editor>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-file-input
                                                v-model="file"
                                                outlined
                                                placeholder="Select Your Image"
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
                            <v-card-actions class="mt-n7">
                                <v-container>
                                    <v-row justify="center">
                                        <v-btn x-large dark  color="secondary" @click="createSelf">Buat</v-btn>
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
                                <span class="title font-weight-light">Update Artikel</span>
                                <v-btn absolute right icon @click="close"><v-icon>mdi-close</v-icon></v-btn>
                            </v-toolbar>
                            <v-card-text class="mt-6">
                                <v-form ref="updateForm">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                outlined
                                                v-model="selfDiscovery.title"
                                                label="Title"
                                                color="secondary"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" class="mt-n4">
                                            <vue-editor :editorToolbar="customToolbar" placeholder="Content" v-model="selfDiscovery.content"></vue-editor>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-file-input
                                                v-model="file"
                                                outlined
                                                placeholder="Select Your Image"
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
                            <v-card-actions class="mt-n7">
                                <v-container>
                                    <v-row justify="center">
                                        <v-btn x-large dark  color="secondary" @click="updateSelf">Update</v-btn>
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
import Selfdiscovery from "@/services/Selfdiscovery"
import _ from "lodash"
import { VueEditor } from "vue2-editor"
import firebase from "firebase"

export default {
    components: {
        VueEditor,
    },
    data() {
        return {
            file:null,
            createDialog: false,
            updateDialog: false,
            selfDiscovery: {
                id:null,
                title:null,
                content:null,
                image:null
            },
            selfDiscoveryDefault: {
                id:null,
                title:null,
                content:null,
                image:null
            },
            selectedIndex:-1,
            // Message
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState:false,
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
            selfDiscoveries:[]
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
        async getAll() {
            await Selfdiscovery.getAll()
                .then((response) => {
                    this.selfDiscoveries = response
                })
        },

        async get() {
            await this.getAll()
        },

        close() {
            this.selectedIndex = -1
            this.createDialog = false
            this.updateDialog = false
            this.selfDiscovery = _.clone(this.selfDiscoveryDefault)
            this.file = null
        },
        updateSelfFunc(item) {
            this.selectedIndex = this.selfDiscoveries.indexOf(item)
            this.selfDiscovery = _.clone(item)
            this.updateDialog = true
        },
        uploadImg(imgFile) {
            return new Promise((resolve, reject) => {
                var storageRef = firebase.storage().ref('selfDiscovery/'+Math.random() + '_'  + imgFile.name) 
                storageRef.put(imgFile)
                .then((snapshot) => {
                    resolve(snapshot.ref.getDownloadURL())
                })
                .catch((err) => {
                    reject(err)
                })
            })
        },
        async createSelf() {
            if(this.$refs.createForm.validate()) {
                this.loadingState = true
                if(this.file != null) {
                    await this.uploadImg(this.file)
                    .then((response) => {
                        this.selfDiscovery.image = response
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                }
                await Selfdiscovery.createSelfDiscovery(this.selfDiscovery)
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

        async updateSelf() {
            if(this.$refs.updateForm.validate()) {
                this.loadingState = true
                if(this.file != null) {
                    await this.uploadImg(this.file)
                    .then((response) => {
                        this.selfDiscovery.image = response
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                } else {
                    this.selfDiscovery.file = null
                }
                await Selfdiscovery.updateSelfDiscovery(this.selfDiscovery)
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

        deleteSelfFunc(item) {
            this.selectedIndex = this.selfDiscoveries.indexOf(item)
            this.selfDiscovery = _.clone(item)
            var a = confirm("Apakah anda yakin ingin menghapus data ini?")
            if(a) {
                Selfdiscovery.deleteSelfDiscovery(this.selfDiscovery.id)
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
        }
    },

    computed: {
        header() {
            return [
                {text:"No", value:"ind", width:"8%"},
                {text:"Title", value:"title"},
                {value:"actions", width:"10%"}
            ]
        }
    }
}
</script>