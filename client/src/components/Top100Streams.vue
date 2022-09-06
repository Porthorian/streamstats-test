<template>
  <v-card>
    <v-card-title>
      Top 100 Streams By Viewer
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="items"
      :items-per-page="5"
      :loading="loading"
      loading-text="Loading... Please wait"
    />
  </v-card>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Top100Streams',

  data: () => ({
    headers: [
      { text: 'Streamer', value: 'streamer' },
      { text: 'Title', value: 'title' },
      { text: 'Date Started', value: 'date_started'},
      { text: 'Viewers', value: 'viewers' }
    ],
    items: [],
    loading: true
  }),

  async created() {
    try {
      const response = await axios.get('/streams/top100')
      const data = response['data']['data']
      for (const item of data) {
        this.items.push({
          streamer: item.streamer_name,
          title: item.stream_title,
          date_started: item.date_started,
          viewers: item.viewers
        });
      }
      this.loading = false
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
