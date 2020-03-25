<template>
  <div class="row w-100">
    <ValidationObserver ref="validator" class="w-100">
      <div class="col-md-12 text-right my-2">
        <el-button type="primary" @click="modalNuevo">
          Agregar
        </el-button>
      </div>
      <div class="col-md-12">
        <el-table :data="tableData" style="width: 100%">
          <el-table-column label="Nombre" prop="name" />
          <el-table-column label="Correo" prop="email" />
          <el-table-column label="Rol" prop="rol" />
          <el-table-column align="right">
            <template slot="header" slot-scope="scope">
              <el-input
                v-model="search"
                size="mini"
                placeholder="Type to search"
              />
            </template>
            <template slot-scope="scope">
              <el-button size="mini" @click="handleEdit(scope.row)">
                Editar
              </el-button>
              <el-button size="mini" type="danger" :disabled="disabledButton(scope.row)" @click="handleDelete(scope.row)">
                Eliminar
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <el-dialog :title="modalTitutlo" :visible.sync="outerVisible">
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-md-right">Nombres</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required|max:255" name="Nombres">
              <input v-model="form.name" class="form-control" type="text" name="name">
              <p class="v-validate">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-md-right">Correo</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required|email|max:255" name="email">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
              <has-error :form="form" field="email" />
              <p class="v-validate">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-md-right">Tipo</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required" name="Tipo">
              <el-select v-model="form.id_rol" placeholder="Selecionar" class="w-100">
                <el-option
                  v-for="item in options"
                  :key="item.id"
                  :label="item.nombre"
                  :value="item.id"
                />
              </el-select>
              <p class="v-validate">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-md-right">Contraseña</label>
          <div class="col-md-7">
            <ValidationProvider
              v-slot="{ errors }"
              rules="password:@confirm|max:255"
              name="Contraseña"
            >
              <input v-model="form.password" class="form-control" type="password" name="password">
              <p class="v-validate">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-md-right">Confirmar Contraseña</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" name="confirm" vid="confirm" rules="">
              <input
                v-model="form.password_confirmation"
                class="form-control"
                type="password"
                name="password_confirmation"
              >
              <p class="v-validate">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>
        <div slot="footer" class="dialog-footer">
          <el-button @click="outerVisible = false">
            Cancel
          </el-button>
          <el-button type="primary" @click="handleCreate">
            Guardar
          </el-button>
        </div>
      </el-dialog>
    </ValidationObserver>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import { extend } from 'vee-validate'
import Form from 'vform'

extend('password', {
  params: ['target'],
  validate (value, { target }) {
    return value === target
  },
  message: 'La confirmación de contraseña no coincide'
})
export default {
  data () {
    return {
      options: [{
        id: 1,
        nombre: 'Administrador'
      }, {
        id: 2,
        nombre: 'Vendedor'
      }],
      //
      search: '',
      //
      outerVisible: false,
      modalTitutlo: 'Agregar usuario',
      id: null,
      form: new Form({
        name: null,
        email: null,
        id_rol: null,
        password: null,
        password_confirmation: null
      })
    }
  },
  computed: {
    ...mapGetters({
      lista: 'usuarios/lista',
      user: 'auth/user'

    }),
    tableData () {
      return this.lista.filter(
        data => !this.search ||
        data.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
        data.documento.toLowerCase().includes(this.search.toLowerCase()) ||
        data.correo.toLowerCase().includes(this.search.toLowerCase()) ||
        data.direccion.toLowerCase().includes(this.search.toLowerCase())
      )
    }
  },
  mounted () {
    this.$store.dispatch('usuarios/actionListar')
  },
  methods: {
    modalNuevo () {
      this.outerVisible = true
      this.limpiar()
    },
    handleEdit (row) {
      this.limpiar()
      this.modalTitutlo = 'Editar usuario'
      this.outerVisible = true
      this.id = row.id
      this.form.name = row.name
      this.form.email = row.email
      this.form.id_rol = row.id_rol
      this.form.password = null
      this.form.password_confirmation = null
    },
    handleDelete (row) {
      this.$store.dispatch('usuarios/actionDelete', row.id).then(() => {
        this.$notify({ title: 'Datos Elimados', type: 'success' })
      })
    },
    async handleCreate () {
      var valido = await this.$refs.validator.validate()
      if (valido) {
        if (this.id === null) {
          this.create()
        } else {
          this.update()
        }
      }
    },
    create () {
      this.form.post('/api/usuarios/create', this.form).then(res => {
        this.$notify({ title: 'Datos Almacenados', type: 'success' })
        this.outerVisible = false
        this.limpiar()
        this.$store.dispatch('usuarios/actionListar')
      })
    },
    update () {
      this.form.put(`/api/usuarios/${this.id}/updated`).then(res => {
        this.$notify({ title: 'Datos Almacenados', type: 'success' })
        this.outerVisible = false
        this.limpiar()
        this.$store.dispatch('usuarios/actionListar')
      })
    },
    limpiar () {
      this.modalTitutlo = 'Agregar usuario'
      this.id = null
      this.form.name = null
      this.form.email = null
      this.form.id_rol = null
      this.form.password = null
      this.form.password_confirmation = null
      this.$refs.validator.reset()
      this.form.reset()
      this.form.clear()
    },
    disabledButton (row) {
      // eslint-disable-next-line no-undef
      return _.get(this.user, 'id') === _.get(row, 'id')
    }
  }
}
</script>
<style lang="scss">
.v-validate {
  font-size: 80%;
  color: #dc3545 !important;
}

</style>
