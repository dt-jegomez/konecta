import Vue from 'vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
Vue.use(ElementUI)
locale.use(lang)
Vue.component('ElementUI', ElementUI)
