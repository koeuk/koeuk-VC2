import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/css/bootstrap.min.css' 
import 'bootstrap/dist/js/bootstrap.min.js' 

import '@fortawesome/fontawesome-free/css/all.css';


import './assets/main.css' 
import { registerLicense } from "@syncfusion/ej2-base";

registerLicense("Ngo9BigBOggjHTQxAR8/V1NCaF5cXmZCdkx3QHxbf1x0ZFxMYFxbRnVPIiBoS35RckVkWHlccXRVQmdbUk11");
import { createApp } from 'vue'
import { createPinia } from 'pinia'
export const GOOGLE_CLIENT_ID = "1061846549456-ap7pb6q2sn155qvvtuikofc581jdsqte.apps.googleusercontent.com";

import App from './App.vue'
import { router, simpleAcl } from './router'

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

import axios from './plugins/axios'
import 'uno.css'

import { configure } from 'vee-validate'
const app = createApp(App)
configure({
  validateOnInput: true
})

app.use(createPinia())

app.use(router)

app.use(simpleAcl)
app.use(ElementPlus)
app.config.globalProperties.$axios = axios
app.mount('#app')
