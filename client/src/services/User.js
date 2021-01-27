import axios from "axios"

const TABLE_NAME = "user"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {
    login(user) {
        return new Promise((resolve,reject) => {
            const data = new FormData()
            data.append("command","login")
            data.append("username",user.username)
            data.append("password",user.password)
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
    }
}; export default api