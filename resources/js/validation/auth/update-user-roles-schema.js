export const updateUserRolesSchema = {

    roles(value) {
        if (!value) {
            return 'Este campo es obligatorio';
        }
        return true;
    }
}
