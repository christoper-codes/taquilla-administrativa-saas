<script setup>
import { drawerClubState } from '@/composables/drawersStates';

</script>

<template>
  <div>
    <div class="tw-fixed tw-bottom-1/2 tw-right-3 tw-z-[60]" @click="drawerClubState = !drawerClubState">
        <div class="tw-flex tw-items-center tw-justify-center -tw-rotate-45 tw-cursor-pointer hover:tw-scale-110 tw-transition-transform tw-duration-700">
            <div class="tw-relative">
            <div class="tw-bg-gradient-to-r tw-from-purple-500 tw-to-purple-400 tw-w-12 tw-h-12 tw-rounded-full tw-flex tw-items-center tw-justify-center">
                <svg class="tw-animate-spin tw-shrink-0 tw-size-6 tw-block tw-text-white tw-z-20" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
            <div class="tw-z-10 tw-absolute tw-bottom-0 tw-left-1/2 tw-transform -tw-translate-x-1/2 tw-translate-y-[20%] tw-w-6 tw-h-6 tw-bg-purple-500 tw-rotate-45 tw-rounded-[4px]"></div>
            </div>
        </div>
    </div>

    <v-layout >
      <v-navigation-drawer v-model="drawerClubState" temporary>
        <div>
            <div class="tw-flex tw-justify-between tw-items-center tw-py-3 tw-px-4 tw-border-b tw-bg-gradient-to-r tw-from-tw-primary-500 tw-to-tw-secondary-300">
                <h3 id="hs-offcanvas-example-label" class="tw-font-bold tw-text-white">
                Clubs disponibles
                </h3>
            </div>
            <div class="tw-p-4 tw-flex tw-flex-col tw-gap-3">
                <div for="halcones" class="tw-flex  tw-p-3 tw-w-full tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-text-sm tw-cursor-pointer focus:tw-border-tw-primary-500 focus:tw-ring-tw-primary-500 hover:tw-scale-105 tw-transition-transform tw-duration-500">
                    <v-checkbox
                        color="primary"
                        label="Halcones de xalapa"
                        hint="Club deportivo a selecionar."
                        ></v-checkbox>
                </div>
            </div>
            </div>
      </v-navigation-drawer>
    </v-layout>
  </div>
</template>

<style scoped>
    .v-navigation-drawer__scrim{
        backdrop-filter: blur(4px) !important;
    }
    .tw-animate-spin {
        animation: tw-spin 2s linear infinite;
    }

    .v-navigation-drawer--temporary.v-navigation-drawer--active {
        width: 75% !important;
    }

    @media (min-width: 768px) {
        .v-navigation-drawer--temporary.v-navigation-drawer--active {
            width: 260px !important;
        }
    }
</style>
