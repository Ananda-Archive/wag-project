import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: "#FFF2CC",
                secondary: "#819C4B",
                footer: "#292929",
                background: '#F2F4F8',
                other: '#fafafa'
            }
        }
    }
});