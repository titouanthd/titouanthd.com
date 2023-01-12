import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  // switch(event) {
  //   // find the current language
  //   // current language is the first element before / in the url
  //   // if the url is /en/... then current language is en
  //   // if the url is /fr/... then current language is fr
  //   // if the url is /... then current language is en
  //   const current_language = window.location.pathname.split("/")[1];

  //   // from event target, get the language to switch to
  //   const language_to_switch_to = event.target.dataset.language;

  //   // if the language to switch to is the same as the current language
  //   // then do nothing
  //   if (language_to_switch_to === current_language) {
  //     return;
  //   }

  //   // if the language to switch to is not the same as the current language
  //   // then switch to the language to switch to
  //   // if the language to switch to is en
  //   // then switch to /en/... and keep the rest of the url
  //   // if the language to switch to is fr
  //   // then switch to /fr/... and keep the rest of the url
  //   if (language_to_switch_to === "en") {
  //     window.location.href = "/en" + window.location.pathname.slice(3);
  //   } else if (language_to_switch_to === "fr") {
  //     window.location.href = "/fr" + window.location.pathname.slice(3);
  //   }
  // }

  switch(event) {
    // get the language to switch to from the event target
    const language_to_switch_to = event.target.dataset.language;
    console.log(language_to_switch_to);

    // set the _locale cookie to the language to switch to
    document.cookie = `_locale=${language_to_switch_to}; path=/`;
  }
}
