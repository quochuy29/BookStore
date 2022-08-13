export default {
    foo: function(id, formData) {
        axios
            .post("/api/gio-hang/add-to-cart/" + id, formData)
            .then((response) => {
                if (response.status == 200) {
                    sessionStorage.setItem("count", response.data.count)
                }
            });
    }
}