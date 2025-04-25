import { computed } from "vue";

export default function useStringFormat() {

    const formatTitleCase = (string) => {
        return string != null ? string.toLowerCase().replace(/_/g, ' ').split(' ')
                                         .map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') : '';
    };

    const formatFirstLetterUppercase = (string) => {
        return  string != null ? string.replace(/_/g, ' ').trim().toLowerCase()
                                 .replace(/^./, char => char.toUpperCase()) : '';
    };

    return {
        formatTitleCase,
        formatFirstLetterUppercase
    }
}
