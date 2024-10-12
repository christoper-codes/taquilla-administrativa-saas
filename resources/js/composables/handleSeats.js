import { onMounted } from "vue";

export default function useHandleSeats() {

    function handleSeat(seats, seatsSelected, emit) {
        seats.forEach(seat => {
        const elemento = document.getElementById(seat.seat_catalogue.code);
            if (elemento) {
            if (seat.seat_catalogue_status.name === 'disponible') {
            elemento.classList.add('tw-cursor-pointer', 'tw-fill-yellow-500');
            elemento.classList.remove('tw-cursor-not-allowed', 'tw-fill-purple-500', 'tw-fill-red-500');
            const handleClick = () => {
                const existSeat = seatsSelected.find(s => s.seat_catalogue.code === seat.seat_catalogue.code);
                if (existSeat) {
                elemento.classList.remove('tw-fill-green-500');
                elemento.classList.add('tw-fill-yellow-500');
                emit('add-seat', seat);
                } else {
                elemento.classList.remove('tw-fill-yellow-500');
                elemento.classList.add('tw-fill-green-500');
                emit('add-seat', seat);
                }
            };
            elemento.addEventListener('click', handleClick);
            elemento.addEventListener('touchstart', handleClick);
            } else if (seat.seat_catalogue_status.name === 'vendido') {
            elemento.classList.add('tw-cursor-not-allowed', 'tw-fill-purple-500');
            } else if (seat.seat_catalogue_status.name === 'reservado') {
            elemento.classList.add('tw-cursor-not-allowed', 'tw-fill-pink-500');
            } else if (seat.seat_catalogue_status.name === 'inhabilitado') {
            elemento.classList.add('tw-cursor-not-allowed', 'tw-fill-gray-600');
            }
        }
        });
    }

    return {
        handleSeat
    }

}
