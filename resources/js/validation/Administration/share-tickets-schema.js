export const shareTicketSchema = {
    senderUserName(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    receiverUserName(value) {
        if (!value) {
          return 'Este campo es obligatorio';
        }

        return true;
    },
    ticketListOfSender(value) {
        if (!value) {
            return 'Este campo es obligatorio';
        }

        if (value.length == 0) {
            return 'Selecciona los tickets a enviar';
        }

        return true;
    }
}
