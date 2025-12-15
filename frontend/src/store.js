import { reactive } from 'vue'

export const store = reactive({
  isLoggedIn: false,
  state: {
    user: null,
    token: null,
    role: null,
    user_id: null,
    name: "Guest"
  },

  login(token, user) {
    this.state.user = user
    this.state.token = token
    this.state.role = user.role
    this.isLoggedIn = true
    this.state.name = user.full_name
    this.state.user_id = user.id
    console.log('Logged in as:', user.name)
    localStorage.setItem('auth_token', token)
  },

  logout() {
    localStorage.setItem('auth_token', null)
    this.state.user = null
    this.state.token = null
    this.state.role = null
    this.state.user_id = null
    this.isLoggedIn = false
    this.state.name = "Guest"
    console.log('Logged out')

  },

  getUser() {
    return this.state.user
  },

  getToken() {
    return this.state.token
  }
})
