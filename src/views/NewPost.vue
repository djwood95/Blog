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
  data() {
    return {
      title: "",
      contents: "",
      loading: false,
      unsavedChanges: false,
      errorBoxActive: false,
      errorList: []
    };
  },

  watch: {
    title() {
      this.unsavedChanges = true;
    },

    contents() {
      this.unsavedChanges = true;
    }
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

  beforeRouteLeave (to, from , next) {
      if(this.unsavedChanges) {
          const answer = window.confirm('Do you really want to leave? You have unsaved changes!')
          if (answer) next()
          else next(false)
      } else {
          next();
      }
  },

  methods: {
    savePost() {
      this.loading = true;

      //validate inputs
      /*
      this.errorList = [];
      this.errorBoxActive = false;
      if (this.title.length == 0) {
        this.errorList.push("Post title is required.");
      }

      if (this.contents.length >= 65535) { //65535 is the max chars that can be stored in MySQL TEXT type
        this.errorList.push("Post contents have a max length of 65,535 characters.");
      }

      if (this.title.length >= 255) { //255 is the max chars that can be stored in MySQL TINYTEXT type
        this.errorList.push("Post title has a max length of 255 characters.");
      }

      if(this.errorList.length > 0) {
        this.errorBoxActive = true;
        return;
      }
      */

      let isValid = true;
      isValid = this.$refs["title"].checkHtml5Validity();
      isValid = this.$refs["contents"].checkHtml5Validity();

      if(!isValid) return;

      let data = {
        'title': this.title,
        'contents': this.contents
      }

      this.$http.post(this.$api + "/posts/new", data).then(response => {

        this.$buefy.toast.open({
          message: "Your post has been saved!",
          type: "is-success",
          duration: 5000
        });

        this.unsavedChanges = false;
        this.$router.push("/");

      }).finally(() => {
        this.loading = false;
      });
    }
  }
};
</script>