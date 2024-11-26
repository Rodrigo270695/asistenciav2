import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { OhVueIcon, addIcons } from "oh-vue-icons";
import VueApexCharts from "vue3-apexcharts";

import
{
    FaFlag, RiZhihuFill, IoAddCircleSharp, MdModeeditRound, GiCancel, FaCheck,
    PrImages, BiTrash, FaEye, BiCartFill, BiClockFill, IoReloadCircleSharp,
    ViFileTypePdf, ViFileTypeWord, ViFileTypeImage, ViDefaultFile, ViFileTypeExcel, BiFileEarmarkExcelFill,
    FaSearch, BiEyeFill, BiEyeSlashFill, GiExitDoor, MdDelete, MdChangecircleRound, MdPassword, MdAddphotoalternateRound, BiUpload,
    GiHamburgerMenu, MdMonitorTwotone, HiSolidPlusSm, HiMinusSm, BiArrowLeftShort, AiAfricarxivSquare, BiWindows, HiSolidChevronDown,
    FaBars, FaTimes, FaUserCircle, FaAngleDown, FaCog, FaSignOutAlt, IoDesktop, IoClose, IoChevronDown, FaMinus, OiApps, IoCarSportSharp,
    FaUsersCog, MdDonutlargeOutlined, PrUserPlus, RiRouteFill, MdRouteSharp, LaSortNumericDownSolid, MdInvertcolorsRound, BiBox, BiJustifyLeft,
    BiLayoutWtf, MdPlaceRound, RiFindReplaceLine, GiMineTruck, RiImageAddFill, FaArrowLeft, FaMapMarkerAlt, CoToggleOn, FaToggleOff, HiSolidUserAdd,
    BiBezier, MdDomainverification, BiCalendarDateFill, MdLocalactivity, MdAdsclick, BiBasket3Fill, MdDashboardcustomizeRound, LaProductHunt, BiBorderOuter,
    MdCategoryOutlined, BiBoxSeam, MdStoreOutlined, BiListTask, MdArrowbackiosRound, RiBarcodeLine, FaStoreAlt, MdPlaceOutlined, FaUserAlt, LaUserPlusSolid,
    FaCheckDouble, RiCheckboxLine, MdCancelpresentationSharp, HiSolidDocumentReport, GiShieldOpposition, SiReason, BiClock, CoClockify, FaDownload,
    IoReloadSharp, MdFullscreen, MdFullscreenexit, BiCheck, BiX, RiArrowDropDownLine, MdPlace, RiUserFill
} from "oh-vue-icons/icons";

addIcons(
FaFlag, RiZhihuFill, IoAddCircleSharp, MdModeeditRound, GiCancel, FaCheck,
PrImages, BiTrash, FaEye, BiCartFill, BiClockFill, IoReloadCircleSharp,
ViFileTypePdf, ViFileTypeWord, ViFileTypeImage, ViDefaultFile, ViFileTypeExcel, BiFileEarmarkExcelFill,
FaSearch, BiEyeFill, BiEyeSlashFill, GiExitDoor, MdDelete, MdChangecircleRound, MdPassword, MdAddphotoalternateRound, BiUpload,
GiHamburgerMenu, MdMonitorTwotone, HiSolidPlusSm, HiMinusSm, BiArrowLeftShort, AiAfricarxivSquare, BiWindows, HiSolidChevronDown,
FaBars, FaTimes, FaUserCircle, FaAngleDown, FaCog, FaSignOutAlt, IoDesktop, IoClose, IoChevronDown, FaMinus, OiApps, IoCarSportSharp,
FaUsersCog, MdDonutlargeOutlined, PrUserPlus, RiRouteFill, MdRouteSharp, LaSortNumericDownSolid, MdInvertcolorsRound, BiBox, BiJustifyLeft,
BiLayoutWtf, MdPlaceRound, RiFindReplaceLine, GiMineTruck, RiImageAddFill, FaArrowLeft, FaMapMarkerAlt, CoToggleOn, FaToggleOff, HiSolidUserAdd,
BiBezier, MdDomainverification, BiCalendarDateFill, MdLocalactivity, MdAdsclick, BiBasket3Fill, MdDashboardcustomizeRound, LaProductHunt, BiBorderOuter,
MdCategoryOutlined, BiBoxSeam, MdStoreOutlined, BiListTask, MdArrowbackiosRound, RiBarcodeLine, FaStoreAlt, MdPlaceOutlined, FaUserAlt, LaUserPlusSolid,
FaCheckDouble, RiCheckboxLine, MdCancelpresentationSharp, HiSolidDocumentReport, GiShieldOpposition, SiReason, BiClock, CoClockify, FaDownload,
IoReloadSharp, MdFullscreen, MdFullscreenexit, BiCheck, BiX, RiArrowDropDownLine, MdPlace, RiUserFill
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueApexCharts);

        app.component("v-icon", OhVueIcon);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
