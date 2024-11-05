export const institutionSchema = {
    id(value) {
        return true;
    },
    stadium_id(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    global_image(value) {
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
    description(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'La descripción debe tener al menos 5 caracteres';
        }

        return true;
    },
    is_active(value) {
        return true;
    },
}
