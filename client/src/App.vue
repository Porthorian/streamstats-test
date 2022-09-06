<template>
  <v-app>
    <v-theme-provider>
      <v-app-bar>
        <v-app-bar-title>
          StreamStats
        </v-app-bar-title>
        <v-btn
        prepend-icon=""
        href="http://localhost/twitch/redirect"
        >
          Login with Twitch
        </v-btn>
      </v-app-bar>

      <v-main>
        <v-container fluid>
          <NumberOfStreams/>
        </v-container>
      </v-main>
    </v-theme-provider>
  </v-app>
</template>

<script>
import NumberOfStreams from '@/components/NumberOfStreams'
export default {
  components: {
    NumberOfStreams
  }
}
</script>

