import axios from "axios"
const TABLE_NAME = "promo"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {

    createPromo(promo) {
        return new Promise((resolve, reject) => {
            const data = new FormData()
            data.append("name",promo.name)
            data.append("code",promo.code)
            data.append("discount",promo.discount)
            data.append("type",promo.type)
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

    updatePromo(promo) {
        return new Promise((resolve, reject) => {
            let data = {
                "id":promo.id,
                "name":promo.name,
                "code":promo.code,
                "discount":promo.discount,
                "type":promo.type,
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

    deletePromo(id){
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