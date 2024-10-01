/*
* |--------------------------------------------------------------------------|
* | Primaries and secondaries colors of companies | Colors
* |--------------------------------------------------------------------------|
*/

export function primariesAndSecondariesColorsHDX() {
    return {
        'tw-primary-950': '#3b0764',
        'tw-primary-900': '#581c87',
        'tw-primary-800': '#6b21a8',
        'tw-primary-700': '#7e22ce',
        'tw-primary-600': '#9333ea',
        'tw-primary-500': '#a855f7',
        'tw-primary-400': '#c084fc',
        'tw-primary-300': '#d8b4fe',
        'tw-primary-200': '#e9d5ff',
        'tw-primary-100': '#f3e8ff',
        'tw-primary-50': '#faf5ff',
        'tw-secondary-950': '#422006',
        'tw-secondary-900': '#713f12',
        'tw-secondary-800': '#854d0e',
        'tw-secondary-700': '#a16207',
        'tw-secondary-600': '#ca8a04',
        'tw-secondary-500': '#eab308',
        'tw-secondary-400': '#facc15',
        'tw-secondary-300': '#fde047',
        'tw-secondary-200': '#fef08a',
        'tw-secondary-100': '#fef9c3',
        'tw-secondary-50': '#fefce8',
    };
}

export function primariesAndSecondariesColorsBUAP() {
    return {
        'tw-primary-950': '#450a0a',
        'tw-primary-900': '#7f1d1d',
        'tw-primary-800': '#991b1b',
        'tw-primary-700': '#b91c1c',
        'tw-primary-600': '#dc2626',
        'tw-primary-500': '#ef4444',
        'tw-primary-400': '#f87171',
        'tw-primary-300': '#fca5a5',
        'tw-primary-200': '#fecaca',
        'tw-primary-100': '#fee2e2',
        'tw-primary-50': '#fef2f2',
        'tw-secondary-950': '#082f49',
        'tw-secondary-900': '#0c4a6e',
        'tw-secondary-800': '#075985',
        'tw-secondary-700': '#0369a1',
        'tw-secondary-600': '#0284c7',
        'tw-secondary-500': '#0ea5e9',
        'tw-secondary-400': '#38bdf8',
        'tw-secondary-300': '#7dd3fc',
        'tw-secondary-200': '#bae6fd',
        'tw-secondary-100': '#e0f2fe',
        'tw-secondary-50': '#f0f9ff',
    };
}

/*
* |--------------------------------------------------------------------------|
* | Management primary colors of club |
* |--------------------------------------------------------------------------|
*/
export function loadColors(colors) {
    for (const color in colors) {
        document.documentElement.style.setProperty(`--${color}`, colors[color]);
    }
}

/*
* |--------------------------------------------------------------------------|
* | Handle club setting in dom | DOM
* |--------------------------------------------------------------------------|
*/
export function handleClubSettings(loading = false, loadingScreen, screenReady, colors, club){

    if(loading){
        loadingScreen.classList.add('invisible', 'opacity-0', 'hidden');
        screenReady.classList.remove('invisible', 'opacity-0');
    }

    if(colors) loadColors(colors);
    localStorage.setItem('club', club);

}

