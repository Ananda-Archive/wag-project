<template>
  <v-app app>
    <navbar></navbar>
    <router-view></router-view>
    <homefooter v-if="this.$route.name != 'login' && !this.$route.meta.auth"></homefooter>
    <footerlayout v-if="this.$route.name != 'login' && !this.$route.meta.auth"></footerlayout>
  </v-app>
</template>

<script>

import navbar from "@/components/navbarcomponent"
import homefooter from "@/components/home/homefooter"
import footerlayout from "@/components/footer"

export default {

    components: {
        navbar,homefooter,footerlayout
    }

}
</script>

<style>

</style>