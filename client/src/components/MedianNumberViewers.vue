<template>
  <v-card>
    <v-card-title class="justify-center">
      Median Viewers for all Streams
    </v-card-title>
    <v-card-text>
      {{ median }}
    </v-card-text>
  </v-card>
</template>

<script>
import axios from 'axios'
export default {
  name: 'MedianNumberViewers',

  data: () => ({
    median: 0
  }),

  async created() {
    try {
      const response = await axios.get('/streams/median')
      this.median = response['data']['data']['median']
    } catch (error) {
      console.log(error)
    }
  }
}
</script>
