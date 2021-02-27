<template>
    <v-container fluid class="pa-0 footer">
        <v-row no-gutters justify="center" class="px-10 px-md-16 py-12">
            <v-col cols="12" md="4" class="white--text">
                <v-row no-gutters>
                    <v-col cols="12" md="6" class="mb-8 mb-md-0">
                        <h2>Detail Info</h2>
                        <ul v-for="(item,idx) in detailInfo" :key="idx">
                            <li><span @click="goTo(link)" class="footerLink">{{item.name}}</span></li>
                        </ul>
                    </v-col>
                    <v-col cols="12" md="6" class="mb-8 mb-md-0">
                        <h2>Reach Us Up</h2>
                        <ul v-for="(item,idx) in reachUs" :key="idx">
                            <li><span @click="goTo(link)" class="footerLink">{{item.name}}</span></li>
                        </ul>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="0" md="3"></v-col>
            <v-col cols="12" md="3" class="white--text">
                <h2>Keep You In Touch</h2>
                <div class="my-1">
                    <v-icon class="mr-4" x-large dark>mdi-instagram</v-icon>
                    <v-icon class="mr-4" x-large dark>mdi-instagram</v-icon>
                    <v-icon class="mr-4" x-large dark>mdi-instagram</v-icon>
                    <v-icon x-large dark>mdi-instagram</v-icon>
                </div>
                <div class="d-flex pa-0 mt-4">
                    <v-text-field
                        outlined
                        dense
                        solo
                        placeholder="Your E-mail"
                        class="mr-3"
                    >
                    </v-text-field>
                    <v-btn class="secondary">Subscribe</v-btn>
                </div>
                <div class="mt-n3">Will update you with news & product!</div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            detailInfo:[
                {name:"Shipping Policy", link:""},
                {name:"FAQ", link:""},
                {name:"How to Order", link:""}
            ],
            reachUs:[
                {name:"Email", link:""},
                {name:"Business Opportunity", link:""},
                {name:"Careers", link:""}
            ]
        }
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
.footerLink {
    cursor: pointer;
}
.footerLink:hover {
    text-decoration: underline;
    text-decoration-color: #FFF2CC;
}
</style>