export const registerSchema = {
    first_name(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'El nombre debe tener al menos 3 caracteres';
        }

        return true;
    },
    last_name(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'El apellido debe tener al menos 3 caracteres';
        }

        return true;
    },
    middle_name(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 3) {
          return 'El segundo nombre debe tener al menos 3 caracteres';
        }

        return true;
    },
    username(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }
        if (value.length < 3) {
          return 'El nombre de usuario debe tener al menos 3 caracteres';
        }

        return true;
    },
    user_gender(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    birthdate(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    global_image(value) {
        return true;
    },
    email(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (!regex.test(value)) {
          return 'Email no válido';
        }
        return true;
    },
    password(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        if (value.length < 8) {
          return 'La contraseña debe tener al menos 8 caracteres';
        }

        return true;
    },
    password_confirmation(value) {
        if (!value) {
            return 'Este campo es obligatorio';
        }

        if (value.length < 8) {
        return 'La contraseña debe tener al menos 8 caracteres';
        }

          return true;
    }
}
