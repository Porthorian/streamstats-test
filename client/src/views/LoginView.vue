<template>
  <v-container fill-height fluid>
     <v-row align="center" justify="center">
        <v-col cols="8">
          <v-card class="elevation-12">
           <v-card-subtitle class="text-center text-subtitle-1">
              See how the streams you follow compare to the Top 1000 Livestreams on Twitch.
           </v-card-subtitle>
           <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn href="http://localhost/twitch/redirect" target="_blank">
                <v-img max-height="20" width="20" src="/twitch.svg"  style="margin-right: 10px"/>
                Login from Twitch
              </v-btn>
              <v-spacer></v-spacer>
           </v-card-actions>
          </v-card>
        </v-col>
     </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'LoginView'
}
</script>