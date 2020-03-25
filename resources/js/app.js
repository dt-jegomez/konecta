import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'

import '~/plugins'
import '~/components'

import * as rules from 'vee-validate/dist/rules'
import es from 'vee-validate/dist/locale/es'
import {
  extend,
  ValidationProvider,
  ValidationObserver
} from 'vee-validate'

Vue.config.productionTip = false
for (let rule in rules) {
  extend(rule, {
    ...rules[rule], // add the rule
    message: es.messages[rule] // add its message
  })
}
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)

Vue.prototype.$setErrorsFromResponse = function (errorResponse) {
  // only allow this function to be run if the validator exists
  if (!this.hasOwnProperty('$validator')) {
    return
  }

  // clear errors
  this.$validator.errors.clear()

  // check if errors exist
  if (!errorResponse.hasOwnProperty('errors')) {
    return
  }

  let errorFields = Object.keys(errorResponse.errors)

  // insert laravel errors
  errorFields.map(field => {
    let errorString = errorResponse.errors[field].join(', ')
    this.$validator.errors.add(field, errorString)
  })
}
/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
