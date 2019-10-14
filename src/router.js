import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'postList',
      component: () => import('./views/PostList.vue')
    },
    {
      path: '/newPost',
      name: 'newPost',
      component: () => import('./views/NewPost.vue')
    },
    {
      path: '/view/:postId',
      name: 'viewPost',
      component: () => import('./views/Post.vue')
    }
  ]
})
