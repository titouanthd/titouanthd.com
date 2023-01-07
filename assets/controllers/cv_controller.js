import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  rotate() {
    this.element.classList.toggle("rotate-180");
  }

  download_titouanthd_cv_small() {
    // cv short version is stored in /public/img/cv-titouanthd-full.pdf
    // this is a link to download the file
    window.open("/img/cv-titouanthd-small.pdf", "_blank");
  }

  download_titouanthd_cv_full() {
    window.open("/img/cv-titouanthd-full.pdf", "_blank");
  }
}
