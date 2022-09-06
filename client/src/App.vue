<template>
  <v-app>
    <v-app-bar
      app
    >
      <v-toolbar-title>
        StreamStats
      </v-toolbar-title>

      <v-spacer></v-spacer>
      <span v-if="isLoggedIn" style="margin-right: 10px;">{{ getUsername }}</span>
      <v-btn text v-if="isLoggedIn" v-on:click="logout">
        <v-icon>mdi-window-close</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main>
      <template v-if="loading">
        <v-container fill-height fluid>
          <v-row align="center" justify="center">
            <v-progress-circular
              :size="50"
              indeterminate
            ></v-progress-circular>
          </v-row>
        </v-container>
      </template>
      <template v-else>
        <HomeView v-if="isLoggedIn"/>
        <LoginView v-else/>
      </template>
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
import LoginView from './views/LoginView'
import axios from 'axios'

export default {
  name: 'App',

  components: {
    HomeView,
    LoginView
  },

  data () {
    return {
      loading: true
    }
  },

  async created() {
    this.loading = true
    this.login()
    this.loading = false
  },

  computed: {
    isLoggedIn () {
      return this.$store.getters.isLoggedIn
    },
    getUsername () {
      return this.$store.getters.getUser.username
    }
  },

  methods: {
    async login () {
      try {
        const response = await axios.get('http://localhost/user')
        const data = response['data']['data']
        this.$store.dispatch('login', data)
      } catch (error) {
        this.$store.dispatch('logout')
      }
    },
    async logout () {
      try {
        await axios.get('http://localhost/auth/logout')
      } catch (error) {
        console.log(error)
      }

      this.$store.dispatch('logout')
    }
  }
};
</script>
