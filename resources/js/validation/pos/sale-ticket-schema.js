export const saleTicketSchema = {

    total(value) {
        if(isNaN(value)){
            return 'Este campo debe ser un número';
        }
        if (value < 0) {
            return 'Este campo debe ser igual o mayor a 0';
        }
        return true;
    },
    amount_returned(value) {
        if(isNaN(value)){
            return 'Este campo debe ser un número';
        }
        if (value < 0) {
            return 'Este campo debe ser igual o mayor a 0';
        }

        return true;
    },
    amount_received(value) {
        if(isNaN(value)){
            return 'Este campo debe ser un número';
        }
        if (value < 0) {
            return 'Este campo debe ser igual o mayor a 0';
        }

        return true;
    }
};
