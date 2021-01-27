const user = {

    state: {
        id: null,
        username: null
    },

    mutations: {
        setNewUser(state) {
            state.id = null,
            state.username = null
        },
        setUser(state, user) {
            state.id = user.id
            state.username = user.username
        }
    },

    actions: {
        login( {commit}, user ) {
            commit("setUser", user)
        },
        logout( {commit}, user  ) {
            commit("setNewUser", user)
        }
    }
    
}; export default user