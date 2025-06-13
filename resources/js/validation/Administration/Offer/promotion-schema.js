export const promotionSchema = {
    id(value) {
        return true;
    },
    promotion_type_id(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    stadium_id(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    name(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'El nombre debe tener al menos 3 caracteres';
        }

        return true;
    },
    availability_sale(value){
        if (!value) {
            return 'Este campo es obligatorio';
          }
          return true;
    },
    description(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'La descripción debe tener al menos 5 caracteres';
        }

        return true;
    },
    generic_seats_allowed(value) {


        return true;
    },
    promotional_seats_allowed(value) {

        return true;
    },
    maximun_promotions_allowed(value) {

        return true;
    },
    percent_allow(value) {

        return true;
    },
    is_active(value) {
        return true;
    },
    is_active_online(value) {
        return true;
    },
}
