export const getRole = () => {
    axios.get(`/admin/get-info`)
        .then(response => {
            if(response.data.success) {
                console.log(response.data.user);
                return response.data.user;
            }
        });
};
