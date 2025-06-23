export const walletSchema = {
    id(value) {
        return true;
    },
    user_id(value) {
        return true;
    },
    wallet_account_type_id(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }
        return true;
    },
    role_type_id(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }
        return true;
    },
    current_balance(value) {
        return true;
    },
    account_number(value) {
        return true;
    },
    is_active(value) {
        return true;
    },
    expiration_date(value) {
        return true;
    },
}
