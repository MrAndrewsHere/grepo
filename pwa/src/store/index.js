import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    status: '',
    token: localStorage.getItem('token') || '',
    user : localStorage.getItem('user') || ''
  },
  getters:{
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
    authUser:state => state.user

  },
  mutations: {
    auth_request(state){
      state.status = 'loading'
    },

    auth_success(state, data){
      state.status = 'success'
      state.token = data.token
      state.user = data.user
    },

    auth_error(state){
      state.status = 'error'
    },
    logout(state){
      state.status = ''
      state.token = ''
      state.user = ''
    },
  },
  actions: {


    login({commit}, user){
      return new Promise((resolve, reject) => {
        commit('auth_request')
        axios({url: process.env.VUE_APP_API+'/api/login', data: user, method: 'POST' })
            .then(resp => {
              const token = resp.data.token
              const user = resp.data.user
              localStorage.setItem('token', token)
              axios.defaults.headers.common['Authorization'] = token

              let data = {
                'token': token,
                'user':user
              };
              commit('auth_success', data )
              resolve(resp)
            })
            .catch(err => {
              commit('auth_error')
              localStorage.removeItem('token')
              reject(err)
            })
      })
    },
    logout({commit}){
      return new Promise((resolve) => {
        commit('logout')
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
        resolve()
      })
    },


  },
  modules: {
  }
})
