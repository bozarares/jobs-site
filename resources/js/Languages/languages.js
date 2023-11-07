import JapanFlagVue from '@/Components/UI/Icons/Flags/JapanFlag.vue';
import RomaniaFlagVue from '@/Components/UI/Icons/Flags/RomaniaFlag.vue';
import USAFlagVue from '@/Components/UI/Icons/Flags/USAFlag.vue';

export const languages = [
    {
        locale: 'en',
        flag: USAFlagVue,
        name: 'English',
    },
    {
        locale: 'ro',
        flag: RomaniaFlagVue,
        name: 'Română',
    },
    {
        locale: 'ja',
        flag: JapanFlagVue,
        name: '日本語',
    },
];
