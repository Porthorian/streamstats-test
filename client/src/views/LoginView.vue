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
              <v-btn :loading="loading" v-on:click="startAuthentication">
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
import axios from 'axios'

let iterations = 0
export default {
  name: 'LoginView',

  data () {
    return {
      loading: false
    }
  },

  methods: {
    startAuthentication () {
      this.loading = true
      window.open('http://localhost/twitch/redirect', '','scrollbars=yes,menubar=no,height=800,width=640,left=0,top=0,screenX=0,screenY=0,resizable=no,toolbar=no,location=no,status=no')
      setTimeout(this.refresh, 1000)
    },
    refresh() {
      let timeout = setTimeout(this.refresh, 1000)
      axios.get('http://localhost/user')
        .then((res) => {
          this.$store.dispatch('login', res['data']['data'])
          clearTimeout(timeout)
          this.loading = false
        })
        .catch((err) => {
          iterations++
          console.log(err)

          if (iterations >= 15) {
            clearTimeout(timeout)
          }
        })
    }
  }
}
</script>