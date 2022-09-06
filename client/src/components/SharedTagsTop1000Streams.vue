<template>
  <v-card>
    <v-card-title>
      Your Shared Tags with Top 1000 Streams
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
  name: 'SharedTagsTop1000Streams',

  data: () => ({
    headers: [
      { text: 'Name', value: 'name' },
      { text: 'Description', value: 'description' },
    ],
    items: [],
    loading: true
  }),

  async created() {
    try {
      const response = await axios.get('http://localhost/streams/tags_shared')
      const data = response['data']['data']
      for (const item of data) {
        this.items.push({
          name: item.name,
          description: item.description
        });
      }
      this.loading = false
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
