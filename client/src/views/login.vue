<template>
    <v-main class="secondary">
        <v-container fluid fill-height>
            <v-row align="center" justify="center">
                <v-col cols="12" sm="12" md="5" xl="4">
                    <v-card :disabled="loadingState" class="text-center rounded-lg">
                        <!-- Loading Active when loadingState is TRUE -->
                        <v-progress-linear
                            v-if="loadingState"
                            :indeterminate="loadingState"
                            color="green"
                        ></v-progress-linear>
                        <!-- 
                            Loading Inactive when loadingState is FALSE 
                            Dibuat 2 - di mana 1 transparant, agar saat loading aktif tidak tiba tiba menambah tempat di design, sehingga di "reserve" terlebih dahulu tempatnya
                        -->
                        <v-progress-linear
                            v-else
                            :indeterminate="loadingState"
                            color="transparent"
                        ></v-progress-linear>
                        <v-card-title class="justify-center font-weight-bold text-h3 pt-8">Sign In</v-card-title>
                        <div class="px-3">
                            <v-card-text class="mt-4">
                                <v-form ref="form">
                                    <v-text-field
                                        outlined
                                        placeholder="Username"
                                        :rules="rules.usernameRule"
                                        v-model="userLogin.username"
                                        color="secondary"
                                    >
                                        <template v-slot:prepend-inner><v-icon class="mr-2 primary--text">mdi-account</v-icon></template>
                                    </v-text-field>
                                    <v-text-field
                                        outlined
                                        :type="showPasswordLogin ? 'text' : 'password'"
                                        placeholder="password"
                                        :rules="rules.passLoginRule"
                                        v-model="userLogin.password"
                                        color="secondary"
                                    >
                                        <template v-slot:prepend-inner><v-icon class="mr-2 primary--text">mdi-key</v-icon></template>
                                        <template v-slot:append>
                                            <v-icon @click="showPasswordLogin = !showPasswordLogin" v-if="showPasswordLogin">mdi-eye</v-icon>
                                            <v-icon @click="showPasswordLogin = !showPasswordLogin" v-else>mdi-eye-off</v-icon>
                                        </template>
                                    </v-text-field>
                                </v-form>
                            </v-card-text>
                        </div>
                        <div class="px-3">
                            <v-card-actions>
                                <v-container>
                                    <v-row justify="center">
                                        <v-btn class="mt-n3 mb-3" color="green" dark width="98%" x-large @click="login">LOG IN</v-btn>
                                    </v-row>
                                </v-container>
                            </v-card-actions>
                        </div>
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
import User from "@/services/User"

export default {
    data() {
        return {
            snackbar: false,
            snackbarColor: null,
            snackbarMessage: null,
            loadingState: false,
            showPasswordLogin: false,
            userLogin: {
                username:"",
                password:""
            },
            rules: {
                usernameRule: [
                    v => !!v || "Username Wajib Diisi"
                ],
                passLoginRule: [
                    v => !!v || "Password Wajib Diisi"
                ],
            }
        }
    },

    created() {
        
    },
    
    methods: {
        login() {
            if(this.$refs.form.validate()) {
                this.loadingState = true
                User.login(this.userLogin)
                    .then((response) => {
                        let user = {
                            id: response.data.message[0].id,
                            username: response.data.message[0].username,
                        }
                        this.$store.dispatch("login",user)
                            this.$router.replace("admproduct")
                    }) .catch(err => {
                        this.snackbarColor = "error"
                        this.snackbarMessage = err.message
                        this.snackbar = true
                    }) .finally(() => {
                        this.loadingState = false
                    })
            }
        },
    },

    computed: {
        
    }
}
</script>