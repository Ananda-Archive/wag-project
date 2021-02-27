import axios from "axios"
const TABLE_NAME = "rajaongkir"
const API_KEY = "f4l9JvEx3S1wogVlUAvZZLHE5DGbPUVd"
const username = "adminwag"
const password = "244466666"
const token = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')

const api = {
    getAllProvince() {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, command:"getAllProvince"}
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },
    getAllCity(provinceId) {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, command:"getAllCity", provinceId:provinceId}
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },
    getAllSubdistrict(cityId) {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, command:"getAllSubdistrict", cityId:+cityId}
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },
    getCustomerInfo(cityId,subdistrictId) {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{"X-API-KEY": API_KEY, command:"getCustomerInfo", cityId:+cityId, subdistrictId:+subdistrictId}
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    },
    getOngkir(billing,courier,wag,weight) {
        return new Promise((resolve, reject) => {
            axios.get(TABLE_NAME, {
                headers:{'Authorization': `Basic ${token}`},
                params:{
                    "X-API-KEY": API_KEY,
                    command:"getOngkir",
                    origin:wag.kecamatan,
                    destination:billing.kecamatan,
                    weight: weight,
                    courier: courier.name
                }
            })
                .then((response) => {
                    resolve(response)
                }) .catch(err => {
                    reject(err.response)
                })
        })
    }
}; export default api