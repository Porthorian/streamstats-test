<template>
  <v-app>
    <v-app-bar
      app
    >
      <v-toolbar-title>
        StreamStats
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn
        href="http://localhost/twitch/redirect"
        target="_blank"
        text
      >
        <span class="mr-2">Login from Twitch</span>
      </v-btn>
    </v-app-bar>

    <v-main>
      <HomeView/>
    </v-main>
    <v-footer padless>
      <v-col
        class="text-center"
        cols="12"
      >
        {{ new Date().getFullYear() }} — <strong>Vincent Marone</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
import HomeView from './views/HomeView'
export default {
  name: 'App',

  components: {
    HomeView
  },

  data: () => ({
    //
  }),
};
</script>
