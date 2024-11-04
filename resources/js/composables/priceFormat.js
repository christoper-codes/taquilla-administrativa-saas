import { ref } from "vue";

export default function usePriceFormat() {

    const formatPrice = (price) =>
        Number(price).toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD'
        });

    return {
        formatPrice
    };
}

