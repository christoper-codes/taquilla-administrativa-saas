<script setup>

</script>

<template>
    <section>
        <div class="tw-rounded-none tw-h-[170px] tw-relative tw-bg-slate-900">
            <img class="tw-w-[1300px] tw-h-auto tw-absolute tw-right-0 tw-top-0 lg:tw-mt-[-80px]" src="../../../public/img/hero.svg" alt="">
            <div class="d-flex flex-column fill-height justify-center align-center text-white tw-relative tw-px-4 lg:tw-px-0">
                <div class="tw-max-w-7xl tw-mx-auto tw-flex tw-flex-col tw-w-full tw-gap-10">
                    <div class="tw-flex tw-items-end tw-gap-0 lg:tw-gap-3">
                        <h2 class="tw-font-bold tw-text-3xl lg:tw-text-5xl tw-text-gray-50 tw-max-w-2xl lg:tw-ml-12">
                            <slot name="title" />
                            <span>
                                <svg class="tw-shrink-0 tw-ms-2 tw-size-10 tw-text-gray-50 tw-inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style  scoped>

</style>
