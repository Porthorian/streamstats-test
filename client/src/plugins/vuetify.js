// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

// Vuetify
import { createVuetify } from 'vuetify'

export default createVuetify({
  // https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
  theme: {
    dark: true,
    themes: {
      light: {
        primary: '#3B4FC1',
        secondary: '#75A5D8'
        // #A7C3F3
        // #BFD4F7
        // #BDBBF8
      },
      dark: {
        primary: '#141B41',
        secondary: '#306BAC'
        // #6F9CEB
        // #98B9F2
        // #918EF4
      }
    }
  }
})
