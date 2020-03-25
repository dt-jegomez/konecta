import Axios from 'axios'

export const state = {
  lista: []
}
export const getters = {
  lista: state => state.lista
}
export const mutations = {
  setLIstar (state, payload) {
    state.lista = payload
  }
}
export const actions = {
  actionCreate ({ dispatch }, payload) {
    try {
      return new Promise(resolve => {
        Axios.post('/api/clientes/create', payload).then(Response => {
          if (Response.status === 201) {
            resolve()
            dispatch('actionListar')
          }
        }).catch(err => {
          console.error(err)
        })
      })
    } catch (error) {
      console.error(error)
    }
  },
  actionUpdate ({ dispatch }, payload) {
    try {
      return new Promise(resolve => {
        Axios.put(`/api/clientes/${payload.id}/updated`, payload.form).then(Response => {
          if (Response.data === 1) {
            resolve()
            dispatch('actionListar')
          }
        }).catch(err => {
          console.error(err)
        })
      })
    } catch (error) {
      console.error(error)
    }
  },
  actionDelete ({ dispatch }, payload) {
    try {
      return new Promise(resolve => {
        Axios.delete(`/api/clientes/${payload}/eliminar`, payload.form).then(Response => {
          if (Response.data === 1) {
            resolve()
            dispatch('actionListar')
          }
        }).catch(err => {
          console.error(err)
        })
      })
    } catch (error) {
      console.error(error)
    }
  },
  actionListar ({ commit }) {
    try {
      Axios.get(`/api/clientes/listar`).then(({ data }) => {
        commit('setLIstar', data)
      }).catch(err => {
        console.error(err)
      })
    } catch (error) {
      console.error(error)
    }
  }
}
