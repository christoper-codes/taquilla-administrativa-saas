export default function useDateFormat() {

    const dateFormat = (dateString) => {
        const date = new Date(dateString);
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('es-ES', options);
    };

    const formatDateForDisplay = (date) => {
        return new Date(date).toLocaleString('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    const formatDateForDataInput = (date) => {
        return new Date(date);
    };

    const formatHourForTimePicker = (date) =>{

        return new Date(date).toLocaleString('es-MX', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    };

    const combineDateTimeForDatabase = (date, time) => {
        const fecha = new Date(date);
        const [hour, minute] = time.split(':');
        fecha.setHours(hour, minute, 0, 0);

        const formatComponent = (component) => String(component).padStart(2, '0');

        const formattedDate = [
            fecha.getFullYear(),
            formatComponent(fecha.getMonth() + 1),
            formatComponent(fecha.getDate())
        ].join('-');

        const formattedTime = [
            formatComponent(fecha.getHours()),
            formatComponent(fecha.getMinutes())
        ].join(':');

        return `${formattedDate} ${formattedTime}`;
    };

    return {
        formatDateForDataInput,
        formatHourForTimePicker,
        dateFormat,
        combineDateTimeForDatabase,
        formatDateForDisplay
    }
}
