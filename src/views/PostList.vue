<template>
  <section class="section content">
    <div class="container">
      <h1>All Posts</h1>

      <div class="box" v-if="postList.length == 0">
        No Posts Yet!
      </div>

      <div class="box level" v-for="(post, i) in postList" :key="i">
        <span class="level-left">
          <span class="post-card-item"><b>{{post.title}}</b></span>
          <span class="post-card-item"><i>Created by {{post.created_by}} at {{post.created_at}}</i></span>
        </span>

        <span class="buttons is-right level-right">
          <b-button
            class="is-success is-outlined"
            tag="router-link"
            :to="'/view/'+post.id"
          >View Full Post</b-button>

          <b-button v-if="isLoggedIn"
            class="is-info is-outlined"
            tag="router-link"
            :to="'/edit/'+post.id"
          >Edit Post</b-button>

          <b-button v-if="isLoggedIn"
            class="is-danger is-outlined"
            @click="deletePost()"
          >Delete Post</b-button>
        </span>
      </div>

    </div>
  </section>
</template>

<script>
import { login } from '../mixins/login.js'
export default {
  mixins: [login],

  data () {
    return {
      postList: []
    }
  },

  mounted () {
    this.$http.get(this.$api + '/posts').then(response => {
      this.postList = response.data
    })
  }
}
</script>

<style scoped>
.post-card-item {
  margin-right: 5px;
  max-width:500px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>
