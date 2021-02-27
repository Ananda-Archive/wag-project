import axios from "axios"
const TABLE_NAME = "itemorder"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {

    writeReview(product) {
        return new Promise((resolve, reject) => {
            let data = {
                "id": product.itemOrderId,
                "review": product.review,
                "reviewMessage": product.reviewMessage,
                "command": "writeReview",
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

}; export default api