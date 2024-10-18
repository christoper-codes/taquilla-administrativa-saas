import { usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const cashRegisterPresent = ref(false);

export default function useTicketOfficeState() {
  const userIsAuth = usePage().props.auth.user;
  onMounted(() => {
    const localCashRegister = localStorage.getItem('cashRegisterData');
    if (localCashRegister && userIsAuth) {
        const cashRegisterData = JSON.parse(localCashRegister);
        cashRegisterPresent.value = cashRegisterData.cash_register_type_id;
    }
  });

  return {
    cashRegisterPresent
  };
}

