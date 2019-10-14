<template>
  <section class="section content">
    <div class="container">
      <h1 class="title">{{post.title}}</h1>
      <p>Created by {{post.created_by}} at {{post.created_at}}</p>
      <div class="box wrap-text">{{post.content}}</div>

      <comments :postId="postId" />

    </div>
  </section>
</template>

<script>
import Comments from '../components/Comments'
export default {

  components: {Comments},

  data() {
    return {
      postId: this.$route.params.postId,
      post: {}
    }
  },

  mounted() {
    this.$http.get(this.$api+'/post/'+this.postId).then(response => {
      this.post = response.data[0];
    });
  }
};
</script>

<style>
.wrap-text {
  overflow: hidden;
  overflow-wrap: break-word;
}
</style>