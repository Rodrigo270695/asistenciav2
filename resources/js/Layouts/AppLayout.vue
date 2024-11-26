<script setup>
import { ref, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import NavLink from "@/Components/NavLink.vue";
import ToastList from "@/Components/ToastList.vue";
import Navigation from "./Navigation.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(JSON.parse(localStorage.getItem('showingNavigationDropdown')) || false);
const showZonal = ref(JSON.parse(localStorage.getItem('showZonal')) || false);
const showUser = ref(JSON.parse(localStorage.getItem('showUser')) || false);
const showAssist = ref(JSON.parse(localStorage.getItem('showAssist')) || false);

const logout = () => {
    router.post(route("logout"));
};

const toggleSidebar = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
    localStorage.setItem('showingNavigationDropdown', JSON.stringify(showingNavigationDropdown.value));
};

const closeSidebarOnMobile = () => {
    if (window.innerWidth <= 768) {
        showingNavigationDropdown.value = false;
        localStorage.setItem('showingNavigationDropdown', JSON.stringify(showingNavigationDropdown.value));
    }
};

watch(showingNavigationDropdown, (newValue) => {
    localStorage.setItem('showingNavigationDropdown', JSON.stringify(newValue));
});

watch(showZonal, (newValue) => {
    localStorage.setItem('showZonal', JSON.stringify(newValue));
});

watch(showUser, (newValue) => {
    localStorage.setItem('showUser', JSON.stringify(newValue));
});

watch(showAssist, (newValue) => {
    localStorage.setItem('showAssist', JSON.stringify(newValue));
});

const menuItems = ref([
    {
        name: "Módulo Zonal",
        icon: "md-place-round",
        show: showZonal,
        toggle: () => (showZonal.value = !showZonal.value),
        subItems: [
            {
                name: "Zonales",
                icon: "md-place-outlined",
                route: "zonal.index",
            },
            {
                name: "PDV's",
                icon: "fa-store-alt",
                route: "pdv.index",
            },
            {
                name: "Horarios",
                icon: "bi-clock",
                route: "horary.index",
            },
        ]
    },
    {
        name: "Módulo Usuario",
        icon: "fa-user-alt",
        show: showUser,
        toggle: () => (showUser.value = !showUser.value),
        subItems: [
            {
                name: "Usuarios",
                icon: "pr-user-plus",
                route: "user.index",
            },
            {
                name: "Trabajadores",
                icon: "la-user-plus-solid",
                route: "worker.index",
            },
            {
                name: "Cargos",
                icon: "gi-shield-opposition",
                route: "charge.index",
            },
        ]
    },
    {
        name: "Módulo Asistencia",
        icon: "fa-check-double",
        show: showAssist,
        toggle: () => (showAssist.value = !showAssist.value),
        subItems: [
            {
                name: "Asistencias",
                icon: "ri-checkbox-line",
                route: "assist.index",
            },
            {
                name: "Inasistencias",
                icon: "md-cancelpresentation-sharp",
                route: "absence.index",
            },
            {
                name: "Motivos",
                icon: "si-reason",
                route: "reason.index",
            },
        ]
    },

]);

</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <ToastList />

        <Navigation :title="title" :menuItems="menuItems" :showingNavigationDropdown="showingNavigationDropdown" @toggle-dropdown="toggleSidebar" />

        <aside
            :class="{
                '-translate-x-full': !showingNavigationDropdown,
                'translate-x-0': showingNavigationDropdown,
            }"
            class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform duration-500 bg-3D-50 border-r border-gray-200 shadow-abajo-1"
            aria-label="Sidebar"
        >
            <div class="h-full px-3 pb-4 overflow-y-auto bg-3D-50">
                <ul class="space-y-3 font-medium p-2">
                    <li class="">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="flex items-center p-3 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                            @click="closeSidebarOnMobile"
                        >
                            <div class="mt-[3px] -mb-[6px] text-lg">
                                <v-icon
                                    name="md-dashboardcustomize-round"
                                    class="text-slate-500 hover:text-slate-600 mx-[6px]"
                                />
                                <span class="ms-2">Dashboard</span>
                            </div>
                        </NavLink>
                    </li>

                    <li v-if="$page.props.user.roles.includes('administrador')" class="shadow-abajo-2 rounded-lg">
                        <button
                            @click="showZonal = !showZonal"
                            class="flex cursor-pointer justify-between items-center p-2 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                        >
                            <div class="">
                                <v-icon
                                    name="fa-user-alt"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <span class="ms-3">Módulo Zonal</span>
                            </div>
                            <div class="">
                                <v-icon
                                    v-if="!showZonal"
                                    name="hi-solid-plus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <v-icon
                                    v-else
                                    name="hi-minus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                            </div>
                        </button>
                        <div v-if="showZonal" class="flex flex-col">
                            <NavLink
                                :href="route('zonal.index')"
                                :active="route().current('zonal.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="md-place-outlined"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Zonales</p>
                                </div>
                            </NavLink>
                            <NavLink
                                :href="route('pdv.index')"
                                :active="route().current('pdv.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="fa-store-alt"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">PDV's</p>
                                </div>
                            </NavLink>
                            <NavLink
                                :href="route('horary.index')"
                                :active="route().current('horary.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="bi-clock"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Horarios</p>
                                </div>
                            </NavLink>
                        </div>
                    </li>

                    <li v-if="$page.props.user.roles.includes('administrador') " class="shadow-abajo-2 rounded-lg">
                        <button
                            @click="showUser = !showUser"
                            class="flex cursor-pointer justify-between items-center p-2 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                        >
                            <div class="">
                                <v-icon
                                    name="fa-user-alt"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <span class="ms-3">Módulo Usuarios</span>
                            </div>
                            <div class="">
                                <v-icon
                                    v-if="!showUser"
                                    name="hi-solid-plus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <v-icon
                                    v-else
                                    name="hi-minus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                            </div>
                        </button>
                        <div v-if="showUser" class="flex flex-col">
                            <NavLink
                                :href="route('user.index')"
                                :active="route().current('user.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="pr-user-plus"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Usuarios</p>
                                </div>
                            </NavLink>
                            <NavLink
                                :href="route('worker.index')"
                                :active="route().current('worker.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="la-user-plus-solid"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Trabajadores</p>
                                </div>
                            </NavLink>
                            <NavLink
                                :href="route('charge.index')"
                                :active="route().current('charge.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="gi-shield-opposition"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Cargos</p>
                                </div>
                            </NavLink>
                        </div>
                    </li>

                    <li  class="shadow-abajo-2 rounded-lg">
                        <button
                            @click="showAssist = !showAssist"
                            class="flex cursor-pointer justify-between items-center p-2 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                        >
                            <div class="">
                                <v-icon
                                    name="fa-check-double"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <span class="ms-3">Módulo Asistencia</span>
                            </div>
                            <div class="">
                                <v-icon
                                    v-if="!showAssist"
                                    name="hi-solid-plus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <v-icon
                                    v-else
                                    name="hi-minus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                            </div>
                        </button>
                        <div v-if="showAssist" class="flex flex-col">
                            <NavLink
                                v-if="!$page.props.user.roles.includes('visualizador') "
                                :href="route('assist.index')"
                                :active="route().current('assist.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="ri-checkbox-line"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Asistencias</p>
                                </div>
                            </NavLink>
                            <NavLink
                                v-if="!$page.props.user.roles.includes('asistencia') "
                                :href="route('absence.index')"
                                :active="route().current('absence.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="md-cancelpresentation-sharp"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Inasistencias</p>
                                </div>
                            </NavLink>
                            <NavLink
                                v-if="$page.props.user.roles.includes('administrador')"
                                :href="route('reason.index')"
                                :active="route().current('reason.index')"
                                class="rounded-lg"
                                @click="closeSidebarOnMobile"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        name="si-reason"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">Motivos</p>
                                </div>
                            </NavLink>
                        </div>
                    </li>
                    <li v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')">
                        <NavLink
                            :href="route('report.index')"
                            :active="route().current('report.index')"
                            class="flex items-center p-3 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                            @click="closeSidebarOnMobile"
                        >
                            <div class="mt-[3px] -mb-[6px] text-lg">
                                <v-icon
                                    name="hi-solid-document-report"
                                    class="text-slate-500 hover:text-slate-600 mx-[6px]"
                                />
                                <span class="ms-2">Reportes</span>
                            </div>
                        </NavLink>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="p-5" :class="{ 'md:ml-64': showingNavigationDropdown }">
            <header v-if="$slots.header" class="shadow-abajo-1 rounded-lg">
                <div class="mt-14 rounded-lg bg-3D-50 p-4">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
