import Vue from 'vue'

const product = {

    state: {
        // Id, Price, quantity, quantity max
        products:[]
    },

    mutations: {
        clearProduct(state) {
            state.products = []
        },
        addProduct(state, item) {
            let found = state.products.find(product => product.id == item.id)

            if(found) {
                found.quantity++;
            } else {
                state.products.push(item)
                Vue.set(item, 'quantity',1)
            }
        },
        removeProduct(state, item) {
            let found = state.products.find(product => product.id == item.id)
            let idx = state.products.indexOf(found)
            console.log(idx)
            if(idx > -1) {
                state.products[idx].quantity--
                if(state.products[idx].quantity<=0) {
                    state.products.splice(idx,1)
                }
            }
        }
    },

    actions: {
        addToCart( {commit}, product ) {
            commit("addProduct", product)
        },
        removeFromCart( {commit}, product ) {
            commit("removeProduct", product)
        },
        clearItems({commit}) {
            commit("clearProduct")
        }
    }
    
}; export default product