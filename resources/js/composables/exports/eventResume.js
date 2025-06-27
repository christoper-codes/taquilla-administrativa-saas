import { toast } from 'vue3-toastify';

export default function useEventResume() {

    const exportSummaryByTickets = (eventId) => {
        const url = `/indicadores/export-summary-by-tickets?event_id=${eventId}`;
        window.open(url, '_blank');

        toast('¡Descarga iniciada!', {
            "theme": "auto",
            "type": "success",
            "dangerouslyHTMLString": true
        });
    }

    return {
        exportSummaryByTickets
    }
}
