<template>
  <v-card>
    <v-card-title>
      Total Streams By Start Time
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
  name: 'TotalStreamsByDate',

  data: () => ({
    headers: [
      { text: 'Date', value: 'date_started' },
      { text: 'Total', value: 'total' }
    ],
    items: [],
    loading: true
  }),

  async created() {
    try {
      const response = await axios.get('http://localhost/streams/total_start')
      const data = response['data']['data']
      for (const item of data) {
        this.items.push({
          date_started: item.date_started,
          total: item.total_streams
        });
      }
      this.loading = false
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
