export const loginSchema = {
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
    remember(value) {
        return true;
    }
}
