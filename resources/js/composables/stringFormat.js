export default function useStringFormat() {

    const accentWords = {
        cortesia: 'cortesía',
        promocion: 'promoción',
        halcon: 'halcón',
      };


    const applyAccents = (string) => {

        if (!string){
            return '';
        }

        return string.split(' ') .map(word => {
            const lowerWord = word.toLowerCase();
            return accentWords[lowerWord] || word;
        }).join(' ');
    };

    const formatTitleCase = (string) => {

        if (!string){
            return '';
        };

        return applyAccents(string).toLowerCase().replace(/_/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    };

    const formatFirstLetterUppercase = (string) => {

        if (!string){
            return '';
        };

        return applyAccents(string).replace(/_/g, ' ').trim().toLowerCase().replace(/^./, char => char.toUpperCase());
    };

    return {
        formatTitleCase,
        formatFirstLetterUppercase
    }
}
