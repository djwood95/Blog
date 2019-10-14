<template>
    <div class="modal-card" style="width:300px;">
        <div class="modal-card-body">
            <b-notification
                type="is-danger"
                :active.sync="errorMsgActive"
                auto-close
                :duration="5000"
            >Incorrect username or password!</b-notification>

            <b-field label="Username" label-position="inside">
                <b-input type="text" placeholder="username" v-model="username" />
            </b-field>

            <b-field label="Password" label-position="inside">
                <b-input
                    type="password"
                    placeholder="password"
                    v-model="password"
                    @keypress.native.enter="login()"
                />
            </b-field>

            <b-button
                type="is-success"
                style="width:100%;"
                @click="login()"
                :loading="loading"
            >Login</b-button>
        </div>
    </div>
</template>

<script>
export default {
  data () {
    return {
      username: '',
      password: '',
      loading: false,
      errorMsgActive: false
    }
  },

  methods: {
    login () {
      this.loading = true
      this.errorMsgActive = false
      let data = {
        username: this.username,
        password: this.password
      }

      this.$http
        .post(this.$api + '/checkLogin', data)
        .then(response => {
          if (response.data.status) {
            localStorage.setItem('loggedIn', 'true')
            localStorage.setItem('username', this.username)
            localStorage.setItem('userId', response.data.userId)
            localStorage.setItem('token', response.data.token)
            this.$http.defaults.headers.common['Authorization'] =
                            'Bearer ' + response.data.token

            this.$root.$emit('loggedIn')
          } else {
            this.errorMsgActive = true
          }
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
