import axios from "axios"

export default {
    permission: async function() {
        const data = await axios.get('/api/phan-quyen')
        return data;
    },
    hasPermission: function(data = []) {
        let permission = []
        data.map((item, index) => {
            item.permission.map(item => {
                permission.push(item.name)
            })
        })
        return permission;
    },
    harRole: function(data = {}) {
        let role = []
        data.roles.map((item, index) => {
            role.push(item.name)
        })
        return role;
    }
}