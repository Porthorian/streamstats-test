<template>
  <v-card>
    <v-card-title>
      Top Games by Viewer
    </v-card-title>
    <v-data-table
      :headers="headers"
      item-key="name"
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
  name: 'TopGamesByViewerCount',

  data: () => ({
    headers: [
      { text: 'Game', value: 'name' },
      { text: 'Total', value: 'total' }
    ],
    items: [],
    loading: true
  }),

  async created() {
    try {
      const response = await axios.get('http://localhost/games/top')
      const data = response['data']['data']
      for (const item of data) {
        this.items.push({
          name: item.name,
          total: item.total_viewers
        });
      }
      this.loading = false
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
