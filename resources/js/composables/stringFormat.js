import { computed } from "vue";

export default function useStringFormat() {

    const formatTitleCase = computed(() => {
        return (string) => string != null ? string.replace(/_/g, ' ').split(' ')
                                         .map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') : '';
    });


    const formatFirstLetterUppercase = computed(() => {
        return (string) =>  string != null ? string.replace(/_/g, ' ').trim().toLowerCase()
                                 .replace(/^./, char => char.toUpperCase()) : '';
    });

    return {
        formatTitleCase,
        formatFirstLetterUppercase
    }
}
