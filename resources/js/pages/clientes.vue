<template>
  <div class="row w-100">
    <div class="col-md-12 text-right my-2">
      <el-button type="primary" @click="outerVisible = true">
        Agregar
      </el-button>
    </div>
    <div class="col-md-12">
      <el-table :data="tableData" style="width: 100%">
        <el-table-column label="Nombre" prop="nombre" />
        <el-table-column label="Documento" prop="documento" />
        <el-table-column label="Correo" prop="correo" />
        <el-table-column label="Dirreción" prop="direccion" />
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
            <el-button size="mini" type="danger" @click="handleDelete(scope.row)">
              Eliminar
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog :title="modalTitutlo" :visible.sync="outerVisible">
      <ValidationObserver ref="validator">
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Nombres</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required|max:255" name="Nombres">
              <input v-model="form.nombre" class="form-control" type="text" name="name">
              <p class="text-danger">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Documento</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required|max:255" name="Documento">
              <input
                v-model="form.documento"
                class="form-control"
                type="text"
                name="identificacion"
              >
              <p class="text-danger">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Correo</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required|email|max:255" name="correo">
              <input
                v-model="form.correo"
                class="form-control"
                type="text"
                name="identificacion"
              >
              <p class="text-danger">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Dirreción</label>
          <div class="col-md-7">
            <ValidationProvider v-slot="{ errors }" rules="required|max:255" name="direccion">
              <input
                v-model="form.direccion"
                class="form-control"
                type="text"
                name="identificacion"
              >
              <p class="text-danger">
                {{ errors[0] }}
              </p>
            </ValidationProvider>
          </div>
        </div>
      </ValidationObserver>
      <div slot="footer" class="dialog-footer">
        <el-button @click="outerVisible = false">
          Cancel
        </el-button>
        <el-button type="primary" @click="handleCreate">
          Guardar
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
export default {
  data () {
    return {
      search: '',
      //
      outerVisible: false,
      modalTitutlo: 'Agregar Cliente',
      id: null,
      form: {
        nombre: null,
        documento: null,
        correo: null,
        direccion: null
      }
    }
  },
  computed: {
    ...mapGetters({
      lista: 'clientes/lista'
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
    this.$store.dispatch('clientes/actionListar')
  },
  methods: {
    handleEdit (row) {
      this.id = row.id
      this.form = {
        nombre: row.nombre,
        documento: row.documento,
        correo: row.correo,
        direccion: row.direccion
      }
      this.modalTitutlo = 'Editar Cliente'
      this.outerVisible = true
    },
    handleDelete (row) {
      this.$store.dispatch('clientes/actionDelete', row.id).then(() => {
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
      this.$store.dispatch('clientes/actionCreate', this.form).then(() => {
        this.$notify({ title: 'Datos Almacenados', type: 'success' })
        this.limpiar()
      })
    },
    update () {
      this.$store.dispatch('clientes/actionUpdate', { 'form': this.form, 'id': this.id }).then(() => {
        this.limpiar()
        this.$notify({ title: 'Datos Actualizados', type: 'success' })
      })
    },
    limpiar () {
      this.modalTitutlo = 'Agregar Vendedor'
      this.outerVisible = false
      this.id = null
      this.form = {
        nombre: null,
        documento: null,
        correo: null,
        direccion: null
      }
      this.$refs.validator.reset()
    }
  }
}
</script>
