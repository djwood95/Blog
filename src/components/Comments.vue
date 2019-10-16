<template>
  <section class="section">
      <h2 class="title is-2">Comments ({{commentList.length}})</h2>

      <div class="box">
        <h3 class="title is-3">New Comment</h3>

        <b-field label="Your Name" label-position="inside">
          <b-input type="text" v-model="name" required :maxlength="255" ref="name" />
        </b-field>

        <b-field label="Comment" label-position="inside">
          <b-input type="textarea" v-model="comment" required :maxlength="255" :has-counter="false" ref="comment"/>
        </b-field>

        <b-button type="is-success" size="is-medium" @click="addComment()" :loading="loading">Add Comment</b-button>
      </div>

      <div class="box wrap-text" v-for="comment in commentList" :key="comment.id">
        <h4 class="title is-4">{{comment.created_by}} said...</h4>
        <i>On {{comment.created_at}}</i><br/>
        {{comment.comment}}
      </div>
  </section>
</template>

<script>
export default {
  props: ['postId'],

  data () {
    return {
      name: '',
      comment: '',
      commentList: [],
      loading: false
    }
  },

  mounted () {
    this.getComments()
  },

  methods: {
    getComments () {
      this.$http.get(this.$api + '/comments/' + this.postId).then(response => {
        this.commentList = response.data
      })
    },

    addComment () {
      let data = {
        'name': this.name,
        'comment': this.comment,
        'postId': this.postId
      }

      let isValid = true
      isValid = this.$refs['name'].checkHtml5Validity()
      isValid = this.$refs['comment'].checkHtml5Validity()
      console.log(isValid)

      if (!isValid) return

      this.loading = true
      this.$http.post(this.$api + '/comments/new', data).then(response => {
        this.commentList = response.data

        this.name = ''
        this.comment = ''

        this.$buefy.toast.open({
          message: 'Your comment has been added!',
          type: 'is-success',
          duration: 5000
        })
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
