<template>
  <v-app>
    <v-app-bar
      app
      color="primary"
      dark
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
      <v-container>
        <v-row class="text-center">
          <v-spacer></v-spacer>
          <v-col cols="6">
            <MedianNumberViewers/>
          </v-col>
          <v-spacer></v-spacer>
        </v-row>
        <v-row class="text-center">
          <v-col cols="6">
            <TotalStreamsGame/>
          </v-col>
          <v-col cols="6">
            <TopGamesByViewerCount/>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import TotalStreamsGame from './components/TotalStreamsGame'
import TopGamesByViewerCount from './components/TopGamesByViewerCount'
import MedianNumberViewers from './components/MedianNumberViewers'
export default {
  name: 'App',

  components: {
    TotalStreamsGame,
    TopGamesByViewerCount,
    MedianNumberViewers
  },

  data: () => ({
    //
  }),
};
</script>
