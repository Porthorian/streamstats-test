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
      <v-container>
        <v-row class="text-center">
          <v-spacer></v-spacer>
          <v-col cols="6">
            <MedianNumberViewers/>
          </v-col>
          <v-spacer></v-spacer>
        </v-row>
        <v-row>
          <v-col cols="6">
            <TotalStreamsGame/>
          </v-col>
          <v-col cols="6">
            <TopGamesByViewerCount/>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="6">
            <Top100Streams/>
          </v-col>
          <v-col cols="6">
            <TotalStreamsByDate/>
          </v-col>
        </v-row>
      </v-container>
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
import TotalStreamsGame from './components/TotalStreamsGame'
import TopGamesByViewerCount from './components/TopGamesByViewerCount'
import MedianNumberViewers from './components/MedianNumberViewers'
import Top100Streams from './components/Top100Streams'
import TotalStreamsByDate from './components/TotalStreamsByDate'
export default {
  name: 'App',

  components: {
    TotalStreamsGame,
    TopGamesByViewerCount,
    MedianNumberViewers,
    Top100Streams,
    TotalStreamsByDate
  },

  data: () => ({
    //
  }),
};
</script>
