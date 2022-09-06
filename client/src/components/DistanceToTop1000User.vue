<template>
  <v-card
    :loading="loading"
  >
    <v-card-title>
      Distance to the Top 1000
    </v-card-title>
    <v-card-subtitle>
      What does it take for your lowest following stream to make it into the Top 1000?
    </v-card-subtitle>
    <v-card-text>
      <v-list-item>
        <v-list-item-title>Streamer:</v-list-item-title>
        <v-list-item-subtitle>
          {{ stream.name }}
        </v-list-item-subtitle>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Title:</v-list-item-title>
        <v-tooltip bottom>
          <span>{{ stream.title }}</span>
          <template v-slot:activator="{ on, attrs }">
            <v-list-item-subtitle v-bind="attrs" v-on="on">{{ stream.title }}</v-list-item-subtitle>
          </template>
        </v-tooltip>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Date Started:</v-list-item-title>
        <v-list-item-subtitle>
          {{ stream.date_started }}
        </v-list-item-subtitle>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Current Viewers:</v-list-item-title>
        <v-list-item-subtitle>
          {{ stream.current_viewers }}
        </v-list-item-subtitle>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Viewers Needed:</v-list-item-title>
        <v-list-item-subtitle>{{ viewers_needed }}</v-list-item-subtitle>
      </v-list-item>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'
export default {
  name: 'DistanceToTop1000User',

  data: () => ({
    loading: true,
    stream: {
      title: '',
      name: '',
      date_started: '',
      current_viewers: 0
    },
    viewers_needed: 0
  }),

  async created() {
    try {
      const response = await axios.get('/streams/lowest_following_calc')
      const data = response['data']['data']
      this.stream.title = data['stream']['stream_title']
      this.stream.name = data['stream']['streamer_name']
      this.stream.date_started = data['stream']['date_started']
      this.stream.current_viewers = data['stream']['viewers']
      this.viewers_needed = data['viewers_needed']
      this.loading = false
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
