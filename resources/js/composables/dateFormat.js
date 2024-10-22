import { computed } from "vue";

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

    const formatDate = computed(() => {
        return (date) =>
             new Date(date).toLocaleString('es-MX', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour12: true
              });
    })

    const formatHour = computed(() => {
        return (date) =>
             new Date(date).toLocaleString('es-MX', {
                hour: '2-digit',
                minute: '2-digit',
              });
    })

    return {
        formatDate,
        formatHour,
        dateFormat
    }
}
