import { reactive } from 'vue'

export const store = reactive({
  isLoggedIn: false,
  userName: 'Guest',
  userRole: null, // 'admin' | 'teller' | 'customer'

  state: {
    user: null,
    token: null // placeholder if you later use API tokens
  },

  login(username, role) {
    this.state.user = { username, role }
    // for now, we fake a token
    this.state.token = 'fake-token'
    console.log('Logged in as:', this.state.user)
  },

  logout() {
    this.isLoggedIn = false
    this.userName = 'Guest'
    this.userRole = null
  }

  
})
