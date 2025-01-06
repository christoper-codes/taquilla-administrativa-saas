import { usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const cashRegisterPresent = ref(false);
const ticketOfficeId = ref(1);
const cashRegisterDataId = ref(1);
const sellerUserId = ref(1);

export default function useTicketOfficeState() {
  const userIsAuth = usePage().props.auth.user;
  onMounted(() => {
    const localCashRegister = localStorage.getItem('cashRegisterData');
    if (localCashRegister && userIsAuth) {
        const cashRegisterData = JSON.parse(localCashRegister);
        cashRegisterPresent.value = cashRegisterData.cash_register_type_id;
        cashRegisterDataId.value = cashRegisterData.id;
        sellerUserId.value = cashRegisterData.seller_user_opening_id;
        ticketOfficeId.value = cashRegisterData.ticket_office_id;
    }
  });

  return {
    cashRegisterPresent,
    cashRegisterDataId,
    sellerUserId,
    ticketOfficeId
  };
}

