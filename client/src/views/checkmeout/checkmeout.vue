<template>
    <v-main>
        <v-container fluid class="pt-10 px-16">
            <v-row no-gutters>
                <v-col cols="3"></v-col>
                <v-col cols="9" class="pl-md-16 mb-6"><div class="display-1 mb-3">Produk</div><v-divider></v-divider></v-col>
                <v-col cols="3">
                    <v-card elevation="2" width="100%" class="pt-4 toolbar pb-n4">
                        <h3 class="ml-4 mb-2">Filter</h3>
                        <v-navigation-drawer width="100%" floating permanent>
                            <v-col cols="12">
                                <v-text-field class="mb-n5" color="secondary" outlined dense label="Cari Disini">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-magnify</v-icon></template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-select class="mb-n5" color="secondary" outlined dense label="Jenis Kulit">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-face-woman</v-icon></template>
                                </v-select>
                            </v-col>
                            <v-col cols="12">
                                <v-select color="secondary" outlined dense label="Urutkan Berdasarkan">
                                    <template v-slot:prepend-inner><v-icon class="mr-2 secondary--text">mdi-sort</v-icon></template>
                                </v-select>
                            </v-col>
                        </v-navigation-drawer>
                    </v-card>
                </v-col>
                <v-col cols="9" class="pl-md-16">
                    t
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</template>

<script>
// importing stuff


export default {
    data() {
        return {

        }
    },

    created() {
        
    },
    
    methods: {

    },

    computed: {
        
    }
}
</script>

<style>
.toolbar {
  position: -webkit-sticky!important;
  position: sticky!important;
  top: 15%!important;
  z-index: 1!important;
  overflow-y:auto!important;
}
.toolbar::-webkit-scrollbar{
  display: none!important;
}
.v-dialog > .v-card > .v-toolbar {
  position: sticky;
  top: 0;
  z-index: 999;
}
</style>