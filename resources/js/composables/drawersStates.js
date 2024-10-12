import { ref } from 'vue';

export const drawerNavState = ref(false);
export const drawerClubState = ref(false);
export const draweAppNavState = ref(true);
export const drawerPaymentState = ref(false);

if(window.innerWidth <= 1024) {
    draweAppNavState.value = false;
}
