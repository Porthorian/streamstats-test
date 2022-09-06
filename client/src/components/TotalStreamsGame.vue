<template>
  <v-card>
      <v-card-title>
        Total number of Streams
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="items"
        :items-per-page="5"
      />
    </v-card>
</template>

<script>
import axios from 'axios'
export default {
  name: 'TotalStreamsGame',

  data: () => ({
    headers: [
      { text: 'Game', value: 'name' },
      { text: 'Total', value: 'total' }
    ],
    items: []
  }),

  async created() {
    try {
      const response = await axios.get('http://localhost/games/total')
      const data = response['data']['data']
      for (const item of data) {
        this.items.push({
          name: item.name,
          total: item.total_streams
        });
      }
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
