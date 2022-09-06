<template>
  <v-container>
    <v-row class="text-center">
      <v-spacer></v-spacer>
      <v-col cols="6">
        <MedianNumberViewers/>
      </v-col>
      <v-spacer></v-spacer>
    </v-row>
    <v-row>
      <v-col cols="4">
        <TotalStreamsGame/>
      </v-col>
      <v-col cols="4">
        <TopGamesByViewerCount/>
      </v-col>
      <v-col cols="4">
        <TotalStreamsByDate/>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <Top100Streams/>
      </v-col>
    </v-row>
    <v-row><v-divider></v-divider></v-row>
    <v-row><h4>User Specific Aggregations</h4></v-row>
    <v-row>
      <v-col cols="6">
        <DistanceToTop1000User/>
      </v-col>
      <v-col cols="6">
        <SharedTagsTop1000Streams/>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <Top1000StreamsUserFollowing/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import TotalStreamsGame from '../components/TotalStreamsGame'
import TopGamesByViewerCount from '../components/TopGamesByViewerCount'
import MedianNumberViewers from '../components/MedianNumberViewers'
import Top100Streams from '../components/Top100Streams'
import TotalStreamsByDate from '../components/TotalStreamsByDate'
import Top1000StreamsUserFollowing from '../components/Top1000StreamsUserFollowing'
import SharedTagsTop1000Streams from '../components/SharedTagsTop1000Streams'
import DistanceToTop1000User from '../components/DistanceToTop1000User'
export default {
    name: 'HomeView',
    components: {
      TotalStreamsGame,
      TopGamesByViewerCount,
      MedianNumberViewers,
      Top100Streams,
      TotalStreamsByDate,
      Top1000StreamsUserFollowing,
      SharedTagsTop1000Streams,
      DistanceToTop1000User
    },
}
</script>