<template>
    <div>
        <v-app-bar app class="absolute px-5" flat color="primary" v-if="!this.$route.meta.auth && this.$route.meta.navbar">
            <v-btn x-large icon  @click="goTo('/')"><v-img src="@/assets/wag_logo.png" contain width="70"></v-img></v-btn>
            <v-btn disabled></v-btn>
            <v-btn disabled></v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title @click="goTo('/')" :class="[this.$route.name=='home'?'selectedMenu':'hoverMenu']" class="mr-10"><span>Home</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('')" :class="[this.$route.name==''?'selectedMenu':'hoverMenu']" class="mr-10"><span>About Us</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('/checkmeout')" :class="[this.$route.name=='checkmeout'?'selectedMenu':'hoverMenu']" class="mr-10"><span>Check Me Out</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('')" :class="[this.$route.name==''?'selectedMenu':'hoverMenu']" class="mr-10"><span>Self-Discovery</span></v-toolbar-title>
            <v-toolbar-title @click="goTo('')" :class="[this.$route.name==''?'selectedMenu':'hoverMenu']" class="mr-10"><span>Glammers Say</span></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon><v-icon @click="searchOverlay=!searchOverlay">mdi-magnify</v-icon></v-btn>
            <div class="searchBox"><input type="text" class="searchBoxInput" :class="[searchOverlay?'active':null]" placeholder="Search here..."></div>
            <v-btn icon><v-icon>mdi-cart</v-icon></v-btn>
            <v-btn icon><v-icon>mdi-account</v-icon></v-btn>
        </v-app-bar>
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
                    </v-list>
                </div>
            </v-layout>
        </v-navigation-drawer>
    </div>
</template>

<script>
// importing stuff


export default {
    data() {
        return {
            searchOverlay: false,
            drawer: null
        }
    },

    created() {
        
    },
    
    methods: {
        goTo(path) {
            this.$router.push(path)
            window.scroll(0,0)
        },
    },

    computed: {
        
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
        background: #292929;
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
        background: #292929;
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