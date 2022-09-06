<template>
	<v-col-auto>

	</v-col-auto>
</template>

<script>
import axios from "axios"
export default {
	name: 'NumberOfStreams',
	async created() {
		try {
			await axios.get('http://localhost/games/total', {withCredentials: true})
		} catch (error) {
			console.log(error)
		}
	}
}
</script>
