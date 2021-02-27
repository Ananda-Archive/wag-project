import axios from "axios"
const TABLE_NAME = "customer"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {
    registerCustomer(customer) {
        return new Promise((resolve,reject) => {
            const data = new FormData()
            data.append("name", customer.name)
            data.append("email", customer.email)
            data.append("password", customer.password)
            data.append("alamat", customer.alamat)
            data.append("kecamatan", customer.kecamatan)
            data.append("kota", customer.kota)
            data.append("provinsi", customer.provinsi)
            data.append("kodePos", customer.kodePos)
            data.append("phone", customer.phone)
            data.append("isMember", customer.isMember)
            data.append("X-API-KEY",API_KEY)
            data.append("command", "register")
            axios.post(TABLE_NAME,data,{
                headers:{
                    "Content-Type": "application/x-www-form-urlencoded",
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
    loginCustomer(customer) {
        return new Promise((resolve,reject) => {
            const data = new FormData()
            data.append("command","login")
            data.append("email",customer.email)
            data.append("password",customer.password)
            data.append("X-API-KEY",API_KEY)
            axios.post(TABLE_NAME,data,{
                headers:{
                    "Content-Type": "application/x-www-form-urlencoded",
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
    }
}; export default api