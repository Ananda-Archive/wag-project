import axios from "axios"
const TABLE_NAME = "orderhistory"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {

    createOrderHistory(promo, itemOrders, courier, user, note, email) {
        return new Promise((resolve, reject) => {
            let data = {
                "userId": user,
                "courier": courier.name,
                "courierType": courier.type,
                "shippingCost": courier.price,
                "promoId": promo,
                "itemOrders": itemOrders,
                "note": note,
                "email": email,
                "X-API-KEY": API_KEY,
                "command": "post"
            }
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

    uploadBukti(link,id) {
        return new Promise((resolve, reject) => {
            let data = {
                id:id,
                link:link,
                "X-API-KEY": API_KEY,
                "command": "uploadBukti"
            }
            axios.put(TABLE_NAME,data, {
                headers:{
                    "Authorization": `Basic ${token}`
                }
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err)
                })
        })
    },

    getById(id){
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, command:"getById", id:id}
            })
                .then((response) => {
                    resolve(response.data)
                }) .catch(err => {
                    reject(err.response)
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

    changeStatus(id,status) {
        return new Promise((resolve, reject) => {
            let data = {
                "id": id,
                "status": status,
                "command": "changeStatus",
                "X-API-KEY": API_KEY
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

    sendResi(id,resi) {
        return new Promise((resolve, reject) => {
            let data = {
                "id": id,
                "resi": resi,
                "command": "sendResi",
                "X-API-KEY": API_KEY
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

    remindCustomer() {
        return new Promise((resolve, reject) => {
            let data = {
                "X-API-KEY": API_KEY,
                "command": "remindCustomer"
            }
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
    }

};export default api