import { createI18n } from 'vue-i18n';
import axios from 'axios';
import { en } from './en';

const i18n = createI18n({
    locale: 'en', // setează localizarea implicită
    fallbackLocale: 'en', // setează localizarea de rezervă
    messages: { en }, // inițial, obiectul de mesaje va fi gol
});

const loadedLanguages = ['en']; // limba noastră implicită care este preîncărcată

function setI18nLanguage(lang) {
    i18n.global.locale = lang; // Vite folosește Composition API, deci 'locale' este un ref
    axios.defaults.headers.common['Accept-Language'] = lang;
    document.querySelector('html').setAttribute('lang', lang);
    return lang;
}

async function loadLanguageAsync(lang) {
    // Dacă aceeași limbă este deja selectată
    if (i18n.locale === lang) {
        return setI18nLanguage(lang);
    }

    // Dacă limba a fost deja încărcată
    if (loadedLanguages.includes(lang)) {
        return setI18nLanguage(lang);
    }

    // Dacă limba nu a fost încărcată încă
    const messages = await import(
        /* viteIgnore: true */
        `./${lang}.js` // Presupunem că fișierele de localizare sunt în format JSON
    );
    i18n.global.setLocaleMessage(lang, messages[lang]);
    loadedLanguages.push(lang);
    return setI18nLanguage(lang);
}

export { i18n, loadLanguageAsync };
