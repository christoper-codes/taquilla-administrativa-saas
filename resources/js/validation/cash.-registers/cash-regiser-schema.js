export const  cashRegisterSchema = {

    cash_register_type_id(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }
        return true;
    },
    opening_balance(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }
        if (isNaN(value)) {
            return 'Este campo debe ser un número';
        }
        if (value < 0) {
            return 'Este campo debe ser igual o mayor a 0';
        }
        return true;
    }

}
