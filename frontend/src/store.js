import { reactive } from 'vue'

export const store = reactive({
  isLoggedIn: false,
  userName: 'Guest',
  userRole: null, // 'admin' | 'teller' | 'customer'

  login(name, role) {
    this.isLoggedIn = true
    this.userName = name
    this.userRole = role
  },

  logout() {
    this.isLoggedIn = false
    this.userName = 'Guest'
    this.userRole = null
  }
})
