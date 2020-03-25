<template>
  <section>
    <div class="row w-100">
      Modulo de {{ component }}
    </div>
    <div class="row w-100">
      <div class="col-12">
        <component :is="component" />
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home') }
  },

  components: {
    clientes: () => import('./clientes'),
    usuarios: () => import('./usuarios')

  },

  computed: {
    ...mapGetters({
      component: 'menu/menu'
    })
  },

  methods: {
    handleOpen (key, keyPath) {
      console.log(key, keyPath)
    },
    handleClose (key, keyPath) {
      console.log(key, keyPath)
    }
  }
}
</script>
