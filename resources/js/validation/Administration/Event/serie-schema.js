export const serieSchema = {
    id(value) {
        return true;
    },
    global_season_id(value) {
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
    description(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'La descripción debe tener al menos 5 caracteres';
        }

        return true;
    },
    start_date(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    end_date(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    is_active(value) {
        return true;
    },
}
