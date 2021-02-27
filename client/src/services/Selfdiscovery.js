import axios from "axios"
const TABLE_NAME = "selfdiscovery"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')


const api = {

    createSelfDiscovery(selfDiscovery) {
        return new Promise((resolve, reject) => {
            const data = new FormData()
            data.append("title",selfDiscovery.title)
            data.append("content",selfDiscovery.content)
            data.append("image",selfDiscovery.image)
            data.append("X-API-KEY",API_KEY)
            axios.post(TABLE_NAME,data,{
                headers:{
                    "Authorization": `Basic ${token}`
                }
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err.response.data)
                })
        })
    },

    getAll() {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY}
            })
                .then((response) => {
                    resolve(response.data)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },

    getById(id) {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, "command":"getById", "id":id}
            })
                .then((response) => {
                    resolve(response.data)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },

    updateSelfDiscovery(datas) {
        return new Promise((resolve, reject) => {
            let data = {
                "id": datas.id,
                "title": datas.title,
                "content": datas.content,
                "image": datas.image,
                "X-API-KEY":API_KEY
            }
            axios.put(TABLE_NAME,data, {
                headers:{
                    "Authorization": `Basic ${token}`
                }
            })
                .then((response) => {
                    resolve(response.data.message)
                }) .catch(err => {
                    reject(err.response.data)
                })
        })
    },

    deleteSelfDiscovery(id){
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, id:id, command:"delete"}
            })
                .then((response) => {
                    resolve(response.data)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },

}; export default api