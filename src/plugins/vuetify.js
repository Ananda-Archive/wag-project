import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#2196F3',
                accent: '#222D32',
                background: '#F2F4F8',
                other: '#fafafa'
            }
        }
    }
});