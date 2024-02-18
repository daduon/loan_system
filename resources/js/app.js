import './bootstrap';
import '../css/app.css';
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import { createApp } from 'vue';
import 'element-plus/dist/index.css';
import ElementPlus from 'element-plus';
import router from './router';

import App from '../views/App.vue';

const app = createApp(App)
app.use(ElementPlus)
app.use(router)
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
      app.component(key, component)
}
app.mount('#app')