import Vue from 'vue'
import VueRouter from 'vue-router'
import Logs from './pages/Logs'

Vue.use(VueRouter)

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Logs,
    }
  ],
})