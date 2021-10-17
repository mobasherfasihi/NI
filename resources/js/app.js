import { createApp } from 'vue'
import ExampleComponent from './components/ExampleComponent.vue';

const app = createApp({});
app.component('example-component', ExampleComponent)
    .mount('#app');

require('./bootstrap');
