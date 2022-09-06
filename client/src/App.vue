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
      <HomeView v-if="isLoggedIn"/>
      <LoginView v-else/>
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

  async created() {
    this.login()
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
        this.$store.commit('setUser', data)
        this.$store.commit('login')
      } catch (error) {
        this.$store.commit('setUser', null)
        this.$store.commit('logout')
      }
    },
    async logout () {
      try {
        await axios.get('http://localhost/auth/logout')
      } catch (error) {
        console.log(error)
      }

      this.$store.commit('setUser', null)
      this.$store.commit('logout')
    }
  }
};
</script>
