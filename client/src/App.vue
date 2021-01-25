<template>
  <v-app app>
    <navbar></navbar>
    <router-view></router-view>
  </v-app>
</template>

<script>

import navbar from "@/components/navbarcomponent"

export default {

    components: {
        navbar
    }

}
</script>

<style>

</style>