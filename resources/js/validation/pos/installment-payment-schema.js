export const installmentPaymentSchema = {

    sale_ticket_id(value) {
        if(isNaN(value)){
            return 'Este campo debe ser un número';
        }
        return true;
    },
    cash_register_id(value) {
        if(isNaN(value)){
            return 'Este campo debe ser un número';
        }
        return true;
    },
    amount_received(value) {
        return true;
    },
    total_amount(value) {
        return true;
    },
    total_returned(value) {
        return true;
    },
    global_payment_type_sale_ticket(value) {
        return true;
    },
    payment_types(value) {
        if (!Array.isArray(value) || value.length === 0) {
            return 'Este campo es obligatorio';
        }
        return true;
    },
    card_types(value) {
        return true;
    },
    amount_card_to_pay(value) {
        return true;
    },
    amount_cash_received(value) {
        return true;
    },
    amount_cash_to_pay(value) {
        return true;
    },
    amount_cash_returned(value) {
        return true;
    }
};
