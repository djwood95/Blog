export const login = {
  data () {
    return {
      isLoggedIn: false
    }
  },

  created () {
    if (localStorage.getItem('loggedIn') == 'true') {
      this.isLoggedIn = true
    }

    // Global event handlers
    this.$root.$on('loggedIn', () => {
      this.isLoggedIn = true
    })

    this.$root.$on('loggedOut', () => {
      this.isLoggedIn = false
    })
  }
}
