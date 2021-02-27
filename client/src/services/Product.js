import axios from "axios"
const TABLE_NAME = "product"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {
    createProduct(product) {
        return new Promise((resolve, reject) => {
            const data = new FormData()
            data.append("name",product.name)
            data.append("ingredients",product.ingredients)
            data.append("skinType",product.skinType)
            data.append("price",product.price)
            data.append("discount",product.discount)
            data.append("stock",product.stock)
            data.append("weight",product.weight)
            data.append("imageLead",product.imageLead)
            data.append("images",product.images)
            product.images.forEach(e => {
                data.append("images[]", e)
            });
            data.append("X-API-KEY",API_KEY)
            data.append("command","insertProduct")
            for (let [key, value] of data) {
                console.log(`${key}: ${value}`)
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
    getAllProducts() {
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
    updateProduct(product) {
        return new Promise((resolve, reject) => {
            let data = {
                "id": product.id,
                "name": product.name,
                "skinType": product.skinType,
                "price": product.price,
                "discount": product.discount,
                "discountStatus": product.discountStatus,
                "stock": product.stock,
                "ingredients": product.ingredients,
                "weight": product.weight,
                "imageLead": product.imageLead,
                "images": product.images,
                "command": "updateProduct",
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
    getById(id){
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, command:"getById", id:id}
            })
                .then((response) => {
                    resolve(response.data[0])
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },
    deleteProduct(id){
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