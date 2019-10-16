<template>
  <section class="section content">
    <div class="container">
      <h1>New Post</h1>

      <b-notification
          type="is-danger"
          :active.sync="errorBoxActive"
          auto-close
          :duration="5000"
      >
      Please fix the following errors to continue:
      <ul>
        <li v-for="(error,i) in errorList" :key="i">{{error}}</li>
      </ul>
      </b-notification>

      <b-field label="Post Title" label-position="inside">
        <b-input type="text" size="is-medium" v-model="title" required :maxlength="255" ref="title" />
      </b-field>

      <b-field label="Post Contents" label-position="inside">
        <b-input type="textarea" v-model="contents" required :maxlength="65535" ref="contents"/>
      </b-field>

      <br />
      <br />

      <b-button type="is-success" size="is-large" @click="savePost()">Save</b-button>
    </div>
  </section>
</template>

<script>
export default {

  props: ['mode'],

  data () {
    return {
      title: '',
      contents: '',
      loading: false,
      unsavedChanges: false,
      errorBoxActive: false,
      errorList: []
    }
  },

  watch: {
    title () {
      this.unsavedChanges = true
    },

    contents () {
      this.unsavedChanges = true
    }
  },

  mounted () {
    if (this.mode == 'edit') this.loadPostData()
  },

  /*
  mounted() {
    window.addEventListener("beforeunload", event => {
      if (this.unsavedChanges) {
        event.preventDefault();
        event.returnValue = "";
      }
    });
  },
  */

  beforeRouteLeave (to, from, next) {
    if (this.unsavedChanges) {
      const answer = window.confirm('Do you really want to leave? You have unsaved changes!')
      if (answer) next()
      else next(false)
    } else {
      next()
    }
  },

  methods: {
    savePost () {
      this.loading = true

      // validate inputs
      let isValid = true
      isValid = this.$refs['title'].checkHtml5Validity()
      isValid = this.$refs['contents'].checkHtml5Validity()

      if (!isValid) return

      let data = {
        'title': this.title,
        'contents': this.contents
      }

      if (this.mode == 'new') this.saveNewPost(data)
      else if (this.mode == 'edit') this.saveEditPost(data)
    },

    saveNewPost (data) {
      this.$http.post(this.$api + '/posts/new', data).then(response => {
        this.$buefy.toast.open({
          message: 'Your post has been saved!',
          type: 'is-success',
          duration: 5000
        })

        this.unsavedChanges = false
        this.$router.push('/')
      }).finally(() => {
        this.loading = false
      })
    },

    saveEditPost (data) {
      let postId = this.$route.params.postId
      data['postId'] = postId
      this.$http.post(this.$api + '/posts/edit', data).then(response => {
        this.$buefy.toast.open({
          message: 'Your post has been saved!',
          type: 'is-success',
          duration: 5000
        })

        this.unsavedChanges = false
        this.$router.push('/')
      }).finally(() => {
        this.loading = false
      })
    },

    loadPostData () {
      this.loading = true
      let postId = this.$route.params.postId
      this.$http.get(this.$api + '/post/' + postId).then(response => {
        this.title = response.data[0].title
        this.contents = response.data[0].content
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
