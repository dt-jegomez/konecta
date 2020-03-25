export const state = {
  menu: 'clientes'
}
export const getters = {
  menu: state => state.menu
}
export const mutations = {
  setMenu (state, payload) {
    state.menu = payload
  }
}
