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
  actionUpdate ({
    dispatch
  }, payload) {
    try {
      return new Promise(resolve => {
        Axios.put(`/api/usuarios/${payload.id}/updated`, payload.form).then(Response => {
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
  actionDelete ({
    dispatch
  }, payload) {
    try {
      return new Promise(resolve => {
        Axios.delete(`/api/usuarios/${payload}/eliminar`, payload.form).then(Response => {
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
  actionListar ({
    commit
  }) {
    try {
      Axios.get(`/api/usuarios/listar`).then(({
        data
      }) => {
        commit('setLIstar', data)
      }).catch(err => {
        console.error(err)
      })
    } catch (error) {
      console.error(error)
    }
  }
}
