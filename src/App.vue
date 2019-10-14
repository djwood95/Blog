<template>
  <div id="app">
    <b-navbar>
      <template slot="brand">
        <b-navbar-item tag="router-link" :to="{ path: '/' }">Dan's Blog</b-navbar-item>
        <b-navbar-item v-if="loggedIn" tag="router-link" :to="{ path: '/' }">Posts</b-navbar-item>
        <b-navbar-item v-if="loggedIn" tag="router-link" :to="{ path: '/newPost' }">New Post</b-navbar-item>
      </template>

      <template slot="end">
        <b-dropdown position="is-bottom-left" aria-role="menu" trap-focus v-if="!loggedIn">
          <a class="navbar-item" slot="trigger" role="button">
            <span>Login</span>
            <b-icon icon="menu-down"></b-icon>
          </a>

          <b-dropdown-item aria-role="menu-item" :focusable="false" custom paddingless>
            <login />
          </b-dropdown-item>
        </b-dropdown>

        <b-navbar-item v-if="loggedIn" tag="div">
			Welcome, {{username}}
			<b-button type="is-text" @click="$root.$emit('loggedOut')">Logout</b-button>
		</b-navbar-item>
      </template>
    </b-navbar>
    <router-view />
  </div>
</template>

<script>
import Login from './components/Login'
export default {
  components: {
    Login
  },

  data () {
    return {
      loggedIn: false,
      username: ''
    }
  },

  created () {
    // Create http error interceptors
    this.$http.interceptors.response.use(
      response => {
        let message = 'Sorry, there was an unknown error'
        if (response.status === 401) {
          message = 'Please Login!'
        }

        if (response.status !== 200) {
          this.$buefy.toast.open({
            message: message,
            type: 'is-danger',
            position: 'is-top',
            duration: 5000
          })
        }

        return response
      },
      error => {

		this.$buefy.toast.open({
			message: "Sorry, there was an unknown error",
			type: 'is-danger',
			position: 'is-top',
			duration: 5000
		})

        if (error.response && error.response.data) {
          return Promise.reject(error.response.data)
        }
        return Promise.reject(error.message)
      }
    )

    // check if already logged in using localStorage
    if (localStorage.getItem('loggedIn') == 'true') {
      this.setLoggedIn()
    }

    // Global event handlers
    this.$root.$on('loggedIn', () => {
      this.setLoggedIn()
    })

    this.$root.$on('loggedOut', () => {
      this.setLoggedOut()
    })
  },

  methods: {
    setLoggedIn () {
      this.loggedIn = true
      this.username = localStorage.getItem('username')
      this.$http.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token')
    },

    setLoggedOut () {
      this.$router.push("/") // in case we are on protected page
      this.loggedIn = false
      this.username = ''
      localStorage.setItem('loggedIn', false)
      localStorage.setItem('username', '')
      localStorage.setItem('token', null)
      this.$http.defaults.headers.common['Authorization'] = null
    }
  }
}
</script>

<style>
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>
