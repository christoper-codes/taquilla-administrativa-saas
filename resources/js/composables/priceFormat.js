import { computed } from "vue";

export default function usePriceFormat() {

    const formatPrice = computed(() => {
        return (price) =>
            Number(price).toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD'
            })
    })

    return {
        formatPrice
    }
}
